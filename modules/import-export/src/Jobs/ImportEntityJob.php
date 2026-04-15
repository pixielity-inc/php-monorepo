<?php

declare(strict_types=1);

/**
 * Import Entity Job.
 *
 * Queued job that executes an import operation asynchronously.
 * Wraps DynamicEntityImport with event dispatching and broadcasting.
 * Builds the import from registry config, delegates to Laravel Excel,
 * collects failures via SkipsFailures, and builds ImportResultData.
 *
 * @category Jobs
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Services\ImportManager
 * @see \Pixielity\ImportExport\Concerns\DynamicEntityImport
 */

namespace Pixielity\ImportExport\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Container\Attributes\Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Pixielity\ImportExport\Concerns\DynamicEntityImport;
use Pixielity\ImportExport\Contracts\EntityRegistryInterface;
use Pixielity\ImportExport\Data\CsvSettings;
use Pixielity\ImportExport\Data\ImportResultData;
use Pixielity\ImportExport\Events\ImportCompleted;
use Pixielity\ImportExport\Events\ImportFailed;
use Pixielity\ImportExport\Events\ImportStarted;
use Pixielity\Tenancy\Concerns\BelongsToTenant;
use Throwable;

/**
 * Queued Import Entity Job.
 *
 * Implements ShouldQueue for async processing. Stores the job ID,
 * user ID, entity key, file path, and CSV settings. On handle,
 * dispatches ImportStarted, builds DynamicEntityImport from registry
 * config, runs Laravel Excel import, collects failures, builds
 * ImportResultData, and dispatches ImportCompleted. On failure,
 * dispatches ImportFailed.
 *
 * Usage:
 *   ImportEntityJob::dispatch(
 *       jobId: 'uuid',
 *       userId: 1,
 *       entityKey: 'users',
 *       filePath: 'imports/file.csv',
 *       csvSettings: CsvSettings::fromConfig(),
 *   );
 */
class ImportEntityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new ImportEntityJob instance.
     *
     * @param  string       $jobId        The unique job identifier.
     * @param  int|string   $userId       The ID of the user who initiated the import.
     * @param  string       $entityKey    The entity key being imported.
     * @param  string       $filePath     The storage path of the uploaded import file.
     * @param  CsvSettings  $csvSettings  The CSV formatting configuration.
     */
    public function __construct(
        /**
         * @var string The unique job identifier.
         */
        public readonly string $jobId,

        /**
         * @var int|string The ID of the user who initiated the import.
         */
        public readonly int|string $userId,

        /**
         * @var string The entity key being imported.
         */
        public readonly string $entityKey,

        /**
         * @var string The storage path of the uploaded import file.
         */
        public readonly string $filePath,

        /**
         * @var CsvSettings The CSV formatting configuration.
         */
        public readonly CsvSettings $csvSettings,
    ) {
        $this->connection = config('import-export.queue.connection');
        $this->queue = config('import-export.queue.queue_name', 'import-export');
    }

    // =========================================================================
    // Job Handler
    // =========================================================================

    /**
     * Execute the import job.
     *
     * Dispatches ImportStarted event, resolves entity config from the
     * registry, builds a DynamicEntityImport instance, runs Laravel
     * Excel import, collects failures via SkipsFailures, builds
     * ImportResultData, and dispatches ImportCompleted.
     *
     * @param  EntityRegistryInterface  $entityRegistry  The entity registry (injected by container).
     *
     * @return void
     */
    public function handle(
        EntityRegistryInterface $entityRegistry,
        #[Config('import-export.import.storage_disk', 'local')] string $storageDisk = 'local',
    ): void {
        $fileName = basename($this->filePath);

        event(new ImportStarted(
            jobId: $this->jobId,
            userId: $this->userId,
            entityKey: $this->entityKey,
            fileName: $fileName,
        ));

        $importConfig = $entityRegistry->getImportConfig($this->entityKey);
        $modelClass = $entityRegistry->getModelClass($this->entityKey);

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
            csvSettings: $this->csvSettings,
        );

        Excel::import($import, $this->filePath, $storageDisk);

        // Collect failures from SkipsFailures trait
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

        $skipped = \count($failures);

        $result = new ImportResultData(
            totalRows: $skipped,
            created: 0,
            updated: 0,
            skipped: $skipped,
            errors: $errors,
        );

        event(new ImportCompleted(
            jobId: $this->jobId,
            userId: $this->userId,
            totalRows: $result->totalRows,
            created: $result->created,
            updated: $result->updated,
            skipped: $result->skipped,
            errorCount: \count($result->errors),
        ));
    }

    // =========================================================================
    // Failure Handler
    // =========================================================================

    /**
     * Handle a job failure.
     *
     * Dispatches an ImportFailed event with the error message so the
     * frontend can display the failure via broadcasting.
     *
     * @param  Throwable  $exception  The exception that caused the failure.
     *
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        event(new ImportFailed(
            jobId: $this->jobId,
            userId: $this->userId,
            errorMessage: $exception->getMessage(),
        ));
    }
}
