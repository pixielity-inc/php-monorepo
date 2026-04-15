<?php

declare(strict_types=1);

/**
 * Import Result Data DTO.
 *
 * Spatie Data DTO representing the outcome of an import operation.
 * Implements ImportResultInterface to provide standardized access
 * to row counts and validation errors. Used by both async import
 * jobs and synchronous dry-run operations.
 *
 * @category Data
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\Data\ImportResultInterface
 * @see \Pixielity\ImportExport\Contracts\ImportManagerInterface
 */

namespace Pixielity\ImportExport\Data;

use Pixielity\ImportExport\Contracts\Data\ImportResultInterface;
use Spatie\LaravelData\Data;

/**
 * Import Result Data DTO.
 *
 * Extends Spatie Data and implements ImportResultInterface. Contains
 * the full outcome of an import: total rows processed, rows created,
 * rows updated, rows skipped, and an array of row-level validation
 * errors.
 *
 * Usage:
 *   ```php
 *   $result = new ImportResultData(
 *       totalRows: 100,
 *       created: 85,
 *       updated: 10,
 *       skipped: 5,
 *       errors: [
 *           ['row' => 3, 'field' => 'email', 'message' => 'Invalid email format.'],
 *       ],
 *   );
 *   echo "Created: {$result->created()}";
 *   ```
 */
class ImportResultData extends Data implements ImportResultInterface
{
    /**
     * Create a new ImportResultData instance.
     *
     * @param  int    $totalRows  Total number of rows processed.
     * @param  int    $created    Number of new records created.
     * @param  int    $updated    Number of existing records updated.
     * @param  int    $skipped    Number of rows skipped due to validation failures.
     * @param  array  $errors     Array of row-level validation errors.
     */
    public function __construct(
        /** 
 * @var int Total rows processed. 
 */
        public int $totalRows = 0,
        /** 
 * @var int Rows created. 
 */
        public int $created = 0,
        /** 
 * @var int Rows updated. 
 */
        public int $updated = 0,
        /** 
 * @var int Rows skipped. 
 */
        public int $skipped = 0,
        /** 
 * @var array<int, array{row: int, field: string, message: string}> Validation errors. 
 */
        public array $errors = [],
    ) {
    }

    // =========================================================================
    // ImportResultInterface Implementation
    // =========================================================================

    /**
     * Get the total number of rows processed.
     *
     * @return int The total row count.
     */
    public function totalRows(): int
    {
        return $this->totalRows;
    }

    /**
     * Get the number of rows that were created (new records).
     *
     * @return int The created row count.
     */
    public function created(): int
    {
        return $this->created;
    }

    /**
     * Get the number of rows that were updated (existing records).
     *
     * @return int The updated row count.
     */
    public function updated(): int
    {
        return $this->updated;
    }

    /**
     * Get the number of rows that were skipped (validation failures).
     *
     * @return int The skipped row count.
     */
    public function skipped(): int
    {
        return $this->skipped;
    }

    /**
     * Get the array of validation errors encountered during import.
     *
     * @return array<int, array{row: int, field: string, message: string}> The validation errors.
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
