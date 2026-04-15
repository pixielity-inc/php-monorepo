<?php

declare(strict_types=1);

/**
 * Import Manager Service.
 *
 * Orchestrates all import operations: storing uploaded files,
 * dispatching async import jobs, and running synchronous dry-run
 * validations. Queries the EntityRegistry for entity configurations
 * and builds DynamicEntityImport instances from #[Importable]
 * attribute metadata.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\ImportManagerInterface
 * @see \Pixielity\ImportExport\Concerns\DynamicEntityImport
 * @see \Pixielity\ImportExport\Jobs\ImportEntityJob
 */

namespace Pixielity\ImportExport\Services;

use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Pixielity\ImportExport\Concerns\DynamicEntityImport;
use Pixielity\ImportExport\Contracts\EntityRegistryInterface;
use Pixielity\ImportExport\Contracts\ImportManagerInterface;
use Pixielity\ImportExport\Data\CsvSettings;
use Pixielity\ImportExport\Data\ImportRequestData;
use Pixielity\ImportExport\Data\ImportResultData;
use Pixielity\ImportExport\Jobs\ImportEntityJob;
use Pixielity\Tenancy\Concerns\BelongsToTenant;

/**
 * Implementation of the ImportManagerInterface.
 *
 * Handles import orchestration: stores uploaded files to the import
 * disk, resolves entity config from the registry, generates UUID
 * job IDs, and dispatches ImportEntityJob to the queue. Also provides
 * synchronous dry-run validation wrapped in a rolled-back transaction.
 *
 * Usage:
 *   ```php
 *   // Async import
 *   $jobId = $importManager->import($request, auth()->id());
 *
 *   // Dry-run validation
 *   $result = $importManager->dryRun($request);
 *   ```
 */
class ImportManager implements ImportManagerInterface
{
    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new ImportManager instance.
     *
     * @param  EntityRegistryInterface  $entityRegistry  The entity registry for config lookups.
     * @param  string                   $storageDisk     The storage disk for imports.
     * @param  string                   $storagePath     The storage path for imports.
     */
    public function __construct(
        /**
         * @var EntityRegistryInterface The entity registry for config lookups.
         */
        private readonly EntityRegistryInterface $entityRegistry,
            /**
             * @var string The storage disk for imports.
             */
        #[Config('import-export.import.storage_disk', 'local')]
        private readonly string $storageDisk = 'local',
            /**
             * @var string The storage path for imports.
             */
        #[Config('import-export.import.storage_path', 'imports')]
        private readonly string $storagePath = 'imports',
    ) {
    }

    // =========================================================================
    // ImportManagerInterface Implementation
    // =========================================================================

    /**
     * Dispatch an async import job for the given entity.
     *
     * Stores the uploaded file to the configured import disk, resolves
     * the entity's import configuration from the registry, generates a
     * UUID job ID, and dispatches an ImportEntityJob to the queue.
     *
     * @param  ImportRequestData  $request  The import request parameters including the uploaded file.
     * @param  int|string         $userId   The authenticated user who initiated the import.
     *
     * @return string The dispatched job ID.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or has no import config.
     */
    public function import(ImportRequestData $request, int|string $userId): string
    {
        $importConfig = $this->entityRegistry->getImportConfig($request->entity);

        if ($importConfig === null) {
            throw new \InvalidArgumentException(
                "Unknown or non-importable entity: {$request->entity}",
            );
        }

        $filePath = $request->file->store($this->storagePath, $this->storageDisk);

        $csvSettings = CsvSettings::fromRequest(
            fieldSeparator: $request->fieldSeparator,
            multiValueSeparator: $request->multiValueSeparator,
            enclosure: $request->enclosure,
        );

        $jobId = (string) Str::uuid();

        ImportEntityJob::dispatch(
            jobId: $jobId,
            userId: $userId,
            entityKey: $request->entity,
            filePath: $filePath,
            csvSettings: $csvSettings,
        );

        return $jobId;
    }

    /**
     * Run a synchronous dry-run import (validation only, no persistence).
     *
     * Stores the uploaded file, builds a DynamicEntityImport from the
     * entity's #[Importable] config, wraps the import in a database
     * transaction that is rolled back after processing, and returns
     * the full ImportResultData with counts and validation errors.
     *
     * @param  ImportRequestData  $request  The import request parameters including the uploaded file.
     *
     * @return ImportResultData The validation result with row counts and errors.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or has no import config.
     */
    public function dryRun(ImportRequestData $request): ImportResultData
    {
        $importConfig = $this->entityRegistry->getImportConfig($request->entity);

        if ($importConfig === null) {
            throw new \InvalidArgumentException(
                "Unknown or non-importable entity: {$request->entity}",
            );
        }

        $modelClass = $this->entityRegistry->getModelClass($request->entity);

        $filePath = $request->file->store($this->storagePath, $this->storageDisk);

        $csvSettings = CsvSettings::fromRequest(
            fieldSeparator: $request->fieldSeparator,
            multiValueSeparator: $request->multiValueSeparator,
            enclosure: $request->enclosure,
        );

        // Detect tenant scoping
        $tenantId = null;
        if (\in_array(BelongsToTenant::class, class_uses_recursive($modelClass), true)) {
            $tenantId = (string) app(\Pixielity\Tenancy\Contracts\TenancyManagerInterface::class)->getTenant()?->getTenantKey();
        }

        $import = new DynamicEntityImport(
            modelClass: $modelClass,
            fieldMap: $importConfig->fields,
            rules: $importConfig->rules,
            uniqueBy: $importConfig->uniqueBy,
            transformers: $importConfig->transformers,
            chunkSize: $importConfig->chunkSize,
            tenantId: $tenantId,
            csvSettings: $csvSettings,
        );

        $result = new ImportResultData();

        DB::beginTransaction();

        try {
            Excel::import($import, $filePath, $this->storageDisk);

            $failures = $import->failures();
            $errors = [];

            foreach ($failures as $failure) {
                foreach ($failure->errors() as $message) {
                    $errors[] = [
                        'row' => $failure->row(),
                        'field' => $failure->attribute(),
                        'message' => $message,
                    ];
                }
            }

            $result = new ImportResultData(
                totalRows: $import->getRowCount ?? 0,
                created: 0,
                updated: 0,
                skipped: \count($failures),
                errors: $errors,
            );
        } finally {
            DB::rollBack();
        }

        // Clean up the stored file
        Storage::disk($this->storageDisk)->delete($filePath);

        return $result;
    }
}
