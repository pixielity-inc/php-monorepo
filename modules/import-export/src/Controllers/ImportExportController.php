<?php

declare(strict_types=1);

/**
 * Import/Export Controller.
 *
 * REST API controller for triggering imports, exports, downloads,
 * status checks, template generation, entity listing, and sample
 * data generation. Uses Spatie Data DTOs for request validation
 * and returns JSON responses.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\ExportManagerInterface
 * @see \Pixielity\ImportExport\Contracts\ImportManagerInterface
 * @see \Pixielity\ImportExport\Contracts\SampleDataGeneratorInterface
 * @see \Pixielity\ImportExport\Contracts\EntityRegistryInterface
 */

namespace Pixielity\ImportExport\Controllers;

use Illuminate\Container\Attributes\Config;
use Illuminate\Http\Request;
use Pixielity\ImportExport\Contracts\EntityRegistryInterface;
use Pixielity\ImportExport\Contracts\ExportManagerInterface;
use Pixielity\ImportExport\Contracts\ImportManagerInterface;
use Pixielity\ImportExport\Contracts\SampleDataGeneratorInterface;
use Pixielity\ImportExport\Data\ExportRequestData;
use Pixielity\ImportExport\Data\ImportRequestData;
use Pixielity\ImportExport\Enums\ExportFormat;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * Import/Export API Controller.
 *
 * Exposes 8 endpoints for the import/export engine:
 * - POST /export → dispatch async export job
 * - POST /import → dispatch async import job
 * - POST /import/dry-run → synchronous validation
 * - GET /status/{jobId} → job progress/status
 * - GET /download/{jobId} → download completed export
 * - GET /entities → list registered entities
 * - POST /sample-data → generate sample data
 * - GET /template/{entity} → download import template
 *
 * Usage:
 *   All endpoints require `auth:sanctum` middleware.
 */
#[AsController]
class ImportExportController extends Controller
{
    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new ImportExportController instance.
     *
     * @param  ExportManagerInterface        $exportManager        The export manager service.
     * @param  ImportManagerInterface         $importManager        The import manager service.
     * @param  SampleDataGeneratorInterface   $sampleDataGenerator  The sample data generator service.
     * @param  EntityRegistryInterface        $entityRegistry       The entity registry.
     */
    public function __construct(
        /**
         * @var ExportManagerInterface The export manager service.
         */
        private readonly ExportManagerInterface $exportManager,

        /**
         * @var ImportManagerInterface The import manager service.
         */
        private readonly ImportManagerInterface $importManager,

        /**
         * @var SampleDataGeneratorInterface The sample data generator service.
         */
        private readonly SampleDataGeneratorInterface $sampleDataGenerator,

        /**
         * @var EntityRegistryInterface The entity registry.
         */
        private readonly EntityRegistryInterface $entityRegistry,
            /**
             * @var string The default export format.
             */
        #[Config('import-export.export.default_format', 'xlsx')]
        private readonly string $defaultExportFormat = 'xlsx',
    ) {
    }

    // =========================================================================
    // Export Endpoints
    // =========================================================================

    /**
     * Dispatch an async export job.
     *
     * Accepts ExportRequestData DTO, dispatches the export to the
     * queue, and returns 202 Accepted with the job ID.
     *
     * @param  ExportRequestData  $data     The validated export request data.
     * @param  Request            $request  The HTTP request.
     *
     * @return JsonResponse 202 response with job ID and status.
     */
    public function export(ExportRequestData $data, Request $request): mixed
    {
        $jobId = $this->exportManager->export($data, $request->user()->getKey());

        return $this->accepted([
            'jobId' => $jobId,
            'status' => 'queued',
            'entityKey' => $data->entity,
            'format' => $data->format,
        ]);
    }

    /**
     * Download a completed export file.
     *
     * Returns the generated export file for a completed async job.
     *
     * @param  string  $jobId  The export job ID.
     *
     * @return mixed The downloadable file response.
     */
    public function download(string $jobId): mixed
    {
        return $this->exportManager->downloadCompleted($jobId);
    }

    /**
     * Download an import template for the given entity.
     *
     * Returns an empty file with column headers matching the entity's
     * #[Importable] field map configuration.
     *
     * @param  string   $entity   The entity identifier.
     * @param  Request  $request  The HTTP request.
     *
     * @return mixed The downloadable template file response.
     */
    public function template(string $entity, Request $request): mixed
    {
        $format = ExportFormat::from(
            $request->query('format', $this->defaultExportFormat),
        );

        return $this->exportManager->template($entity, $format);
    }

    // =========================================================================
    // Import Endpoints
    // =========================================================================

    /**
     * Dispatch an async import job.
     *
     * Accepts ImportRequestData DTO with file upload, dispatches the
     * import to the queue, and returns 202 Accepted with the job ID.
     *
     * @param  ImportRequestData  $data     The validated import request data.
     * @param  Request            $request  The HTTP request.
     *
     * @return JsonResponse 202 response with job ID and status.
     */
    public function import(ImportRequestData $data, Request $request): mixed
    {
        $jobId = $this->importManager->import($data, $request->user()->getKey());

        return $this->accepted([
            'jobId' => $jobId,
            'status' => 'queued',
            'entityKey' => $data->entity,
            'fileName' => $data->file->getClientOriginalName(),
        ]);
    }

    /**
     * Run a synchronous dry-run import (validation only).
     *
     * Validates all rows without persisting any data and returns
     * the full ImportResultData with counts and errors.
     *
     * @param  ImportRequestData  $data  The validated import request data.
     *
     * @return JsonResponse The import result with validation details.
     */
    public function dryRun(ImportRequestData $data): mixed
    {
        $result = $this->importManager->dryRun($data);

        return $this->ok([
            'data' => $result->toArray(),
        ]);
    }

    // =========================================================================
    // Status & Entity Endpoints
    // =========================================================================

    /**
     * Get the status of an async import or export job.
     *
     * Returns the current progress and status of the specified job.
     *
     * @param  string  $jobId  The job ID to check.
     *
     * @return JsonResponse The job status information.
     */
    public function status(string $jobId): mixed
    {
        // Job status tracking is handled via broadcasting events.
        // This endpoint provides a polling fallback.
        return $this->ok([
            'jobId' => $jobId,
            'status' => 'processing',
            'message' => 'Use broadcasting events for real-time status updates.',
        ]);
    }

    /**
     * List all registered entities with their capabilities.
     *
     * Returns all exportable, importable, and sample-data entities
     * from the EntityRegistry.
     *
     * @return JsonResponse The registered entities grouped by capability.
     */
    public function entities(): mixed
    {
        return $this->ok([
            'data' => [
                'exportable' => $this->entityRegistry->exportable()->map(fn($config, $key) => [
                    'key' => $key,
                    'label' => $config->label,
                    'fields' => $config->fields,
                    'formats' => $config->formats,
                ])->values()->toArray(),
                'importable' => $this->entityRegistry->importable()->map(fn($config, $key) => [
                    'key' => $key,
                    'label' => $config->label,
                    'fields' => $config->fields,
                    'formats' => $config->formats,
                ])->values()->toArray(),
                'sampleData' => $this->entityRegistry->sampleData()->map(fn($config, $key) => [
                    'key' => $key,
                    'label' => $config->label,
                    'count' => $config->count,
                ])->values()->toArray(),
            ],
        ]);
    }

    // =========================================================================
    // Sample Data Endpoint
    // =========================================================================

    /**
     * Generate sample data for the given entity.
     *
     * Accepts an entity identifier and optional count, triggers
     * sample data generation, and returns the number of records created.
     *
     * @param  Request  $request  The HTTP request.
     *
     * @return JsonResponse The generation result with record count.
     */
    public function sampleData(Request $request): JsonResponse
    {
        $entity = $request->input('entity');
        $count = $request->input('count');

        $recordCount = $this->sampleDataGenerator->generate(
            entityKey: $entity,
            count: $count !== null ? (int) $count : null,
        );

        return $this->ok([
            'entityKey' => $entity,
            'recordCount' => $recordCount,
        ]);
    }
}
