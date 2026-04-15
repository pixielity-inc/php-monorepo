<?php

declare(strict_types=1);

/**
 * Export Manager Service.
 *
 * Orchestrates all export operations: dispatching async export jobs,
 * generating import templates with column headers, and serving
 * completed export files for download. Queries the EntityRegistry
 * for entity configurations and builds DynamicEntityExport instances
 * from #[Exportable] attribute metadata.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\ExportManagerInterface
 * @see \Pixielity\ImportExport\Concerns\DynamicEntityExport
 * @see \Pixielity\ImportExport\Jobs\ExportEntityJob
 */

namespace Pixielity\ImportExport\Services;

use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Pixielity\ImportExport\Concerns\DynamicEntityExport;
use Pixielity\ImportExport\Contracts\EntityRegistryInterface;
use Pixielity\ImportExport\Contracts\ExportManagerInterface;
use Pixielity\ImportExport\Data\CsvSettings;
use Pixielity\ImportExport\Data\ExportRequestData;
use Pixielity\ImportExport\Enums\ExportFormat;
use Pixielity\ImportExport\Jobs\ExportEntityJob;

/**
 * Implementation of the ExportManagerInterface.
 *
 * Handles export orchestration: resolves entity config from the
 * registry, builds CsvSettings from request overrides or config
 * defaults, generates UUID job IDs, and dispatches ExportEntityJob
 * to the queue. Also provides synchronous template generation and
 * completed file downloads.
 *
 * Usage:
 *   ```php
 *   $jobId = $exportManager->export($request, auth()->id());
 *   $file  = $exportManager->template('users', ExportFormat::XLSX);
 *   $download = $exportManager->downloadCompleted($jobId);
 *   ```
 */
class ExportManager implements ExportManagerInterface
{
    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new ExportManager instance.
     *
     * @param  EntityRegistryInterface  $entityRegistry  The entity registry for config lookups.
     * @param  string                   $storageDisk     The storage disk for exports.
     * @param  string                   $storagePath     The storage path for exports.
     */
    public function __construct(
        /**
         * @var EntityRegistryInterface The entity registry for config lookups.
         */
        private readonly EntityRegistryInterface $entityRegistry,
            /**
             * @var string The storage disk for exports.
             */
        #[Config('import-export.export.storage_disk', 'local')]
        private readonly string $storageDisk = 'local',
            /**
             * @var string The storage path for exports.
             */
        #[Config('import-export.export.storage_path', 'exports')]
        private readonly string $storagePath = 'exports',
    ) {
    }

    // =========================================================================
    // ExportManagerInterface Implementation
    // =========================================================================

    /**
     * Dispatch an async export job for the given entity.
     *
     * Resolves the entity's export configuration from the registry,
     * builds CsvSettings from request overrides or config defaults,
     * generates a UUID job ID, and dispatches an ExportEntityJob
     * to the configured queue.
     *
     * @param  ExportRequestData  $request  The export request parameters.
     * @param  int|string         $userId   The authenticated user who initiated the export.
     *
     * @return string The dispatched job ID.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or has no export config.
     */
    public function export(ExportRequestData $request, int|string $userId): string
    {
        $exportConfig = $this->entityRegistry->getExportConfig($request->entity);

        if ($exportConfig === null) {
            throw new \InvalidArgumentException(
                "Unknown or non-exportable entity: {$request->entity}",
            );
        }

        $format = ExportFormat::from($request->format);

        // Validate format is allowed for this entity
        if (!empty($exportConfig->formats) && !\in_array($format, $exportConfig->formats, true)) {
            throw new \InvalidArgumentException(
                "Format '{$format->value}' is not supported for entity '{$request->entity}'.",
            );
        }

        $jobId = (string) Str::uuid();

        ExportEntityJob::dispatch(
            jobId: $jobId,
            userId: $userId,
            request: $request,
            entityKey: $request->entity,
            format: $format->value,
        );

        return $jobId;
    }

    /**
     * Generate an import template file with column headers (sync).
     *
     * Retrieves the entity's #[Importable] field map from the registry
     * and creates a DynamicEntityExport with an empty query but correct
     * headings. Returns a download response via Laravel Excel.
     *
     * @param  string        $entityKey  The entity identifier (e.g., 'users').
     * @param  ExportFormat  $format     The desired template format.
     *
     * @return mixed The downloadable file response.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or has no import config.
     */
    public function template(string $entityKey, ExportFormat $format): mixed
    {
        $importConfig = $this->entityRegistry->getImportConfig($entityKey);

        if ($importConfig === null) {
            throw new \InvalidArgumentException(
                "Unknown or non-importable entity: {$entityKey}",
            );
        }

        $modelClass = $this->entityRegistry->getModelClass($entityKey);

        // Build field map from importable config (column headers as keys → attr names as values)
        // For template, we need the reverse: attr names → column headers
        $fieldMap = array_flip($importConfig->fields);

        $csvSettings = CsvSettings::fromConfig();

        $export = new DynamicEntityExport(
            queryBuilder: $modelClass::query()->whereRaw('1 = 0'),
            fieldMap: $fieldMap,
            formatters: [],
            chunkSize: $importConfig->chunkSize,
            csvSettings: $csvSettings,
        );

        $fileName = "{$entityKey}-template.{$format->extension()}";

        return Excel::download($export, $fileName, $format->laravelExcelType());
    }

    /**
     * Download the completed export file for a finished job.
     *
     * Reads the export file from the configured storage disk and
     * returns a download response.
     *
     * @param  string  $jobId  The export job ID.
     *
     * @return mixed The downloadable file response.
     *
     * @throws \InvalidArgumentException If the job file does not exist.
     */
    public function downloadCompleted(string $jobId): mixed
    {
        $storage = Storage::disk($this->storageDisk);

        // Find the file by job ID prefix (extension may vary)
        $files = $storage->files($this->storagePath);
        $matchingFile = null;

        foreach ($files as $file) {
            if (str_contains($file, $jobId)) {
                $matchingFile = $file;
                break;
            }
        }

        if ($matchingFile === null) {
            throw new \InvalidArgumentException(
                "Export file not found for job: {$jobId}",
            );
        }

        return $storage->download($matchingFile);
    }
}
