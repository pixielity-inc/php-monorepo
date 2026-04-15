<?php

declare(strict_types=1);

/**
 * Import Result Interface.
 *
 * Defines the contract for import operation results. Any DTO or
 * object representing the outcome of an import operation must
 * implement this interface to provide standardized access to
 * row counts and validation errors.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Data\ImportResultData
 * @see \Pixielity\ImportExport\Contracts\ImportManagerInterface
 */

namespace Pixielity\ImportExport\Contracts\Data;

/**
 * Contract for import operation results.
 *
 * Provides read access to the outcome of an import: total rows
 * processed, rows created, rows updated, rows skipped, and any
 * validation errors encountered during processing.
 *
 * Usage:
 *   ```php
 *   $result = $importManager->dryRun($request);
 *   echo "Processed: {$result->totalRows()}";
 *   echo "Created: {$result->created()}";
 *   echo "Errors: " . count($result->errors());
 *   ```
 */
interface ImportResultInterface
{
    /**
     * Get the total number of rows processed.
     *
     * @return int The total row count.
     */
    public function totalRows(): int;

    /**
     * Get the number of rows that were created (new records).
     *
     * @return int The created row count.
     */
    public function created(): int;

    /**
     * Get the number of rows that were updated (existing records matched by uniqueBy).
     *
     * @return int The updated row count.
     */
    public function updated(): int;

    /**
     * Get the number of rows that were skipped (validation failures).
     *
     * @return int The skipped row count.
     */
    public function skipped(): int;

    /**
     * Get the array of validation errors encountered during import.
     *
     * Each entry contains row-level error details:
     *   [{row: int, field: string, message: string}, ...]
     *
     * @return array<int, array{row: int, field: string, message: string}> The validation errors.
     */
    public function errors(): array;
}
