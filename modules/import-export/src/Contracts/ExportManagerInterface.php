<?php

declare(strict_types=1);

/**
 * Export Manager Interface.
 *
 * Defines the contract for the ExportManager service, which handles
 * all export operations: dispatching async export jobs, generating
 * import templates, and serving completed export files. Bound via
 * #[Bind] attribute for automatic container registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Services\ExportManager
 * @see \Pixielity\ImportExport\Data\ExportRequestData
 * @see \Pixielity\ImportExport\Enums\ExportFormat
 */

namespace Pixielity\ImportExport\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Pixielity\ImportExport\Data\ExportRequestData;
use Pixielity\ImportExport\Enums\ExportFormat;
use Pixielity\ImportExport\Services\ExportManager;

/**
 * Contract for the ExportManager service.
 *
 * Provides methods to trigger async exports, generate import
 * templates with column headers, and download completed export
 * files. All export operations are async-first — `export()`
 * dispatches a queued job and returns a job ID.
 *
 * Usage:
 *   ```php
 *   // Trigger an async export
 *   $jobId = $exportManager->export($request, auth()->id());
 *
 *   // Generate an import template (sync)
 *   $file = $exportManager->template('users', ExportFormat::XLSX);
 *
 *   // Download a completed export
 *   $download = $exportManager->downloadCompleted($jobId);
 *   ```
 */
#[Bind(ExportManager::class)]
#[Scoped]
interface ExportManagerInterface
{
    /**
     * Dispatch an async export job for the given entity.
     *
     * Always queues the export and returns a job ID. The frontend
     * receives completion notification via broadcasting.
     *
     * @param  ExportRequestData  $request  The export request parameters.
     * @param  int|string         $userId   The authenticated user who initiated the export.
     *
     * @return string The dispatched job ID.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or format is unsupported.
     */
    public function export(ExportRequestData $request, int|string $userId): string;

    /**
     * Generate an import template file with column headers (sync).
     *
     * Returns an empty file (CSV or XLSX) with column headers matching
     * the entity's #[Importable] field map configuration.
     *
     * @param  string        $entityKey  The entity identifier (e.g., 'users').
     * @param  ExportFormat  $format     The desired template format.
     *
     * @return mixed The downloadable file response.
     *
     * @throws \InvalidArgumentException If the entity key is unknown.
     */
    public function template(string $entityKey, ExportFormat $format): mixed;

    /**
     * Download the completed export file for a finished job.
     *
     * @param  string  $jobId  The export job ID.
     *
     * @return mixed The downloadable file response.
     *
     * @throws \InvalidArgumentException If the job ID is unknown or not completed.
     */
    public function downloadCompleted(string $jobId): mixed;
}
