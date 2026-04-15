<?php

declare(strict_types=1);

/**
 * Import Manager Interface.
 *
 * Defines the contract for the ImportManager service, which handles
 * all import operations: dispatching async import jobs and running
 * synchronous dry-run validations. Bound via #[Bind] attribute for
 * automatic container registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Services\ImportManager
 * @see \Pixielity\ImportExport\Data\ImportRequestData
 * @see \Pixielity\ImportExport\Data\ImportResultData
 */

namespace Pixielity\ImportExport\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Pixielity\ImportExport\Data\ImportRequestData;
use Pixielity\ImportExport\Data\ImportResultData;
use Pixielity\ImportExport\Services\ImportManager;

/**
 * Contract for the ImportManager service.
 *
 * Provides methods to trigger async imports and run synchronous
 * dry-run validations. The `import()` method always dispatches a
 * queued job and returns a job ID. The `dryRun()` method runs
 * synchronously, validates all rows, and returns results without
 * persisting any data.
 *
 * Usage:
 *   ```php
 *   // Trigger an async import
 *   $jobId = $importManager->import($request, auth()->id());
 *
 *   // Run a dry-run validation (sync)
 *   $result = $importManager->dryRun($request);
 *   echo "Would create: {$result->created}";
 *   echo "Errors: " . count($result->errors);
 *   ```
 */
#[Bind(ImportManager::class)]
#[Scoped]
interface ImportManagerInterface
{
    /**
     * Dispatch an async import job for the given entity.
     *
     * Stores the uploaded file, queues the import job, and returns
     * a job ID. The frontend receives completion notification via
     * broadcasting.
     *
     * @param  ImportRequestData  $request  The import request parameters including the uploaded file.
     * @param  int|string         $userId   The authenticated user who initiated the import.
     *
     * @return string The dispatched job ID.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or file format is unsupported.
     */
    public function import(ImportRequestData $request, int|string $userId): string;

    /**
     * Run a synchronous dry-run import (validation only, no persistence).
     *
     * Wraps the import in a database transaction that is rolled back
     * after processing. Returns the full ImportResultData with counts
     * and validation errors.
     *
     * @param  ImportRequestData  $request  The import request parameters including the uploaded file.
     *
     * @return ImportResultData The validation result with row counts and errors.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or file format is unsupported.
     */
    public function dryRun(ImportRequestData $request): ImportResultData;
}
