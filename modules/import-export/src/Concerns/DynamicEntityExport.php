<?php

declare(strict_types=1);

/**
 * Dynamic Entity Export.
 *
 * A runtime-configured Laravel Excel export class that implements
 * multiple Concerns interfaces. Constructed by the ExportManager
 * from `#[Exportable]` attribute metadata — no per-entity export
 * classes are needed.
 *
 * @category Concerns
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Attributes\Exportable
 * @see \Pixielity\ImportExport\Services\ExportManager
 */

namespace Pixielity\ImportExport\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Pixielity\ImportExport\Data\CsvSettings;

/**
 * Dynamic Entity Export Class.
 *
 * Implements Laravel Excel Concerns for exporting entity data:
 * - `FromQuery` — provides the Eloquent query builder
 * - `WithHeadings` — returns human-readable column headers
 * - `WithMapping` — maps each row through the field map and formatters
 * - `ShouldAutoSize` — auto-sizes columns to fit content
 * - `WithCustomChunkSize` — configures chunk size for large exports
 * - `WithCustomCsvSettings` — applies CSV delimiter and enclosure settings
 *
 * Usage:
 *   $export = new DynamicEntityExport(
 *       queryBuilder: User::query(),
 *       fieldMap: ['name' => 'Full Name', 'email' => 'Email Address'],
 *       formatters: ['name' => UpperCaseFormatter::class],
 *       chunkSize: 1000,
 *       csvSettings: CsvSettings::fromConfig(),
 *   );
 *   Excel::download($export, 'users.xlsx');
 */
class DynamicEntityExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithCustomChunkSize,
    WithCustomCsvSettings
{
    /**
     * Create a new DynamicEntityExport instance.
     *
     * @param  Builder              $queryBuilder  The Eloquent query builder for the entity data.
     * @param  array<string, string> $fieldMap     Map of model attribute names to column headers.
     * @param  array<string, string> $formatters   Map of field names to formatter class references.
     * @param  int                   $chunkSize    Number of rows per export batch.
     * @param  CsvSettings           $csvSettings  CSV formatting configuration.
     */
    public function __construct(
        /**
         * @var Builder The Eloquent query builder for the entity data.
         */
        private readonly Builder $queryBuilder,

        /**
         * @var array<string, string> Map of model attribute names to column headers.
         */
        private readonly array $fieldMap,

        /**
         * @var array<string, string> Map of field names to formatter class references.
         */
        private readonly array $formatters,

        /**
         * @var int Number of rows per export batch.
         */
        private readonly int $chunkSize,

        /**
         * @var CsvSettings CSV formatting configuration.
         */
        private readonly CsvSettings $csvSettings,
    ) {
    }

    // =========================================================================
    // FromQuery
    // =========================================================================

    /**
     * Return the query builder for the export data source.
     *
     * @return Builder The Eloquent query builder instance.
     */
    public function query(): Builder
    {
        return $this->queryBuilder;
    }

    // =========================================================================
    // WithHeadings
    // =========================================================================

    /**
     * Return the column headings for the export file.
     *
     * Extracts the human-readable column headers from the field map
     * values, preserving the order defined in the `#[Exportable]`
     * attribute.
     *
     * @return array<int, string> The column header strings.
     */
    public function headings(): array
    {
        return array_values($this->fieldMap);
    }

    // =========================================================================
    // WithMapping
    // =========================================================================

    /**
     * Map a single row to an array of export values.
     *
     * Iterates the field map keys (model attribute names), retrieves
     * each value from the row, applies the configured formatter if
     * one exists for that field, and returns the ordered values
     * matching the headings order.
     *
     * @param  mixed  $row  The Eloquent model instance for the current row.
     *
     * @return array<int, mixed> The ordered export values for this row.
     */
    public function map($row): array
    {
        $values = [];

        foreach (array_keys($this->fieldMap) as $attribute) {
            $value = $row->{$attribute};

            if (isset($this->formatters[$attribute])) {
                $value = resolve($this->formatters[$attribute])->format($value);
            }

            $values[] = $value;
        }

        return $values;
    }

    // =========================================================================
    // WithCustomChunkSize
    // =========================================================================

    /**
     * Return the chunk size for batched export processing.
     *
     * @return int The number of rows per chunk.
     */
    public function chunkSize(): int
    {
        return $this->chunkSize;
    }

    // =========================================================================
    // WithCustomCsvSettings
    // =========================================================================

    /**
     * Return the custom CSV settings for this export.
     *
     * Provides the field delimiter and enclosure character from the
     * injected CsvSettings value object.
     *
     * @return array{delimiter: string, enclosure: string} The CSV configuration array.
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => $this->csvSettings->fieldSeparator,
            'enclosure' => $this->csvSettings->enclosure,
        ];
    }
}
