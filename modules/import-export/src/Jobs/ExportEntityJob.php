<?php

declare(strict_types=1);

/**
 * Export Entity Job.
 *
 * Queued job that executes an export operation asynchronously.
 * Wraps DynamicEntityExport with event dispatching and broadcasting.
 * For CSV/XLSX/PDF formats, delegates to Laravel Excel's store method.
 * For JSON format, executes the query directly and writes a JSON file.
 *
 * @category Jobs
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Services\ExportManager
 * @see \Pixielity\ImportExport\Concerns\DynamicEntityExport
 */

namespace Pixielity\ImportExport\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Container\Attributes\Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Pixielity\ImportExport\Concerns\DynamicEntityExport;
use Pixielity\ImportExport\Contracts\EntityRegistryInterface;
use Pixielity\ImportExport\Data\CsvSettings;
use Pixielity\ImportExport\Data\ExportRequestData;
use Pixielity\ImportExport\Enums\ExportFormat;
use Pixielity\ImportExport\Events\ExportCompleted;
use Pixielity\ImportExport\Events\ExportFailed;
use Pixielity\ImportExport\Events\ExportStarted;
use Pixielity\Tenancy\Concerns\BelongsToTenant;
use Throwable;

/**
 * Queued Export Entity Job.
 *
 * Implements ShouldQueue for async processing. Stores the job ID,
 * user ID, export request data, entity key, and format. On handle,
 * dispatches ExportStarted, builds DynamicEntityExport from registry
 * config, stores the file via Laravel Excel (or JSON directly), and
 * dispatches ExportCompleted. On failure, dispatches ExportFailed.
 *
 * Usage:
 *   ExportEntityJob::dispatch(
 *       jobId: 'uuid',
 *       userId: 1,
 *       request: $exportRequest,
 *       entityKey: 'users',
 *       format: 'xlsx',
 *   );
 */
class ExportEntityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new ExportEntityJob instance.
     *
     * @param  string             $jobId      The unique job identifier.
     * @param  int|string         $userId     The ID of the user who initiated the export.
     * @param  ExportRequestData  $request    The export request parameters.
     * @param  string             $entityKey  The entity key being exported.
     * @param  string             $format     The requested export format value.
     */
    public function __construct(
        /**
         * @var string The unique job identifier.
         */
        public readonly string $jobId,

        /**
         * @var int|string The ID of the user who initiated the export.
         */
        public readonly int|string $userId,

        /**
         * @var ExportRequestData The export request parameters.
         */
        public readonly ExportRequestData $request,

        /**
         * @var string The entity key being exported.
         */
        public readonly string $entityKey,

        /**
         * @var string The requested export format value.
         */
        public readonly string $format,
    ) {
        $this->connection = config('import-export.queue.connection');
        $this->queue = config('import-export.queue.queue_name', 'import-export');
    }

    // =========================================================================
    // Job Handler
    // =========================================================================

    /**
     * Execute the export job.
     *
     * Dispatches ExportStarted event, resolves entity config from the
     * registry, builds a DynamicEntityExport instance, stores the file
     * via Laravel Excel (or writes JSON directly), and dispatches
     * ExportCompleted on success.
     *
     * @param  EntityRegistryInterface  $entityRegistry  The entity registry (injected by container).
     *
     * @return void
     */
    public function handle(
        EntityRegistryInterface $entityRegistry,
        #[Config('import-export.export.storage_disk', 'local')] string $storageDisk = 'local',
        #[Config('import-export.export.storage_path', 'exports')] string $storagePath = 'exports',
    ): void {
        $exportFormat = ExportFormat::from($this->format);

        event(new ExportStarted(
            jobId: $this->jobId,
            userId: $this->userId,
            entityKey: $this->entityKey,
            format: $this->format,
        ));

        $exportConfig = $entityRegistry->getExportConfig($this->entityKey);
        $modelClass = $entityRegistry->getModelClass($this->entityKey);

        // Build query
        $query = $modelClass::query();

        // Apply column selection if specified
        $fieldMap = $exportConfig->fields;
        if (!empty($this->request->columns)) {
            $fieldMap = array_intersect_key($fieldMap, array_flip($this->request->columns));
        }

        // Apply filters if specified
        if (!empty($this->request->filters)) {
            foreach ($this->request->filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        $filePath = "{$storagePath}/{$this->jobId}.{$exportFormat->extension()}";

        if ($exportFormat === ExportFormat::JSON) {
            // JSON export handled directly (not via Laravel Excel)
            $data = $query->get()->map(function ($model) use ($fieldMap, $exportConfig) {
                $row = [];
                foreach ($fieldMap as $attribute => $header) {
                    $value = $model->{$attribute};

                    if (isset($exportConfig->formatters[$attribute])) {
                        $value = resolve($exportConfig->formatters[$attribute])->format($value);
                    }

                    $row[$header] = $value;
                }

                return $row;
            })->values()->toArray();

            Storage::disk($storageDisk)->put($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $totalRows = \count($data);
        } else {
            // CSV/XLSX/PDF via Laravel Excel
            $csvSettings = CsvSettings::fromRequest(
                fieldSeparator: $this->request->fieldSeparator,
                multiValueSeparator: $this->request->multiValueSeparator,
                enclosure: $this->request->enclosure,
            );

            $export = new DynamicEntityExport(
                queryBuilder: $query,
                fieldMap: $fieldMap,
                formatters: $exportConfig->formatters,
                chunkSize: $exportConfig->chunkSize,
                csvSettings: $csvSettings,
            );

            Excel::store($export, $filePath, $storageDisk, $exportFormat->laravelExcelType());

            $totalRows = $query->count();
        }

        event(new ExportCompleted(
            jobId: $this->jobId,
            userId: $this->userId,
            filePath: $filePath,
            totalRows: $totalRows,
        ));
    }

    // =========================================================================
    // Failure Handler
    // =========================================================================

    /**
     * Handle a job failure.
     *
     * Dispatches an ExportFailed event with the error message so the
     * frontend can display the failure via broadcasting.
     *
     * @param  Throwable  $exception  The exception that caused the failure.
     *
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        event(new ExportFailed(
            jobId: $this->jobId,
            userId: $this->userId,
            errorMessage: $exception->getMessage(),
        ));
    }
}
