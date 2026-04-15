<?php

declare(strict_types=1);

/**
 * Dynamic Entity Import.
 *
 * A runtime-configured Laravel Excel import class that implements
 * multiple Concerns interfaces. Constructed by the ImportManager
 * from `#[Importable]` attribute metadata — no per-entity import
 * classes are needed.
 *
 * @category Concerns
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Attributes\Importable
 * @see \Pixielity\ImportExport\Services\ImportManager
 */

namespace Pixielity\ImportExport\Concerns;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Pixielity\ImportExport\Data\CsvSettings;

/**
 * Dynamic Entity Import Class.
 *
 * Implements Laravel Excel Concerns for importing entity data:
 * - `ToModel` — maps each spreadsheet row to an Eloquent model instance
 * - `WithValidation` — validates rows against configured rules
 * - `WithUpserts` — enables upsert behavior via unique field detection
 * - `WithBatchInserts` — batches inserts for performance
 * - `WithChunkReading` — reads the file in chunks to limit memory
 * - `WithHeadingRow` — uses the first row as column headers
 * - `SkipsOnFailure` — collects validation failures without aborting
 * - `WithCustomCsvSettings` — applies CSV delimiter and enclosure settings
 *
 * Uses the `SkipsFailures` trait to collect validation errors for
 * reporting in the ImportResultData DTO.
 *
 * Usage:
 *   $import = new DynamicEntityImport(
 *       modelClass: User::class,
 *       fieldMap: ['Full Name' => 'name', 'Email Address' => 'email'],
 *       rules: ['name' => 'required|string', 'email' => 'required|email'],
 *       uniqueBy: ['email'],
 *       transformers: ['email' => LowercaseTransformer::class],
 *       chunkSize: 500,
 *       tenantId: 'tenant-uuid',
 *       csvSettings: CsvSettings::fromConfig(),
 *   );
 *   Excel::import($import, 'users.csv');
 */
class DynamicEntityImport implements
    ToModel,
    WithValidation,
    WithUpserts,
    WithBatchInserts,
    WithChunkReading,
    WithHeadingRow,
    SkipsOnFailure,
    WithCustomCsvSettings
{
    use SkipsFailures;

    /**
     * Create a new DynamicEntityImport instance.
     *
     * @param  string                $modelClass    The fully-qualified model class name.
     * @param  array<string, string> $fieldMap      Map of column headers to model attribute names.
     * @param  array<string, string> $rules         Laravel validation rules keyed by attribute name.
     * @param  array<int, string>    $uniqueBy      Fields for upsert duplicate detection.
     * @param  array<string, string> $transformers  Map of attribute names to transformer class references.
     * @param  int                   $chunkSize     Number of rows per import batch.
     * @param  string|null           $tenantId      Tenant ID to auto-fill on imported records, or null.
     * @param  CsvSettings           $csvSettings   CSV formatting configuration.
     */
    public function __construct(
        /**
         * @var string The fully-qualified model class name.
         */
        private readonly string $modelClass,

        /**
         * @var array<string, string> Map of column headers to model attribute names.
         */
        private readonly array $fieldMap,

        /**
         * @var array<string, string> Laravel validation rules keyed by attribute name.
         */
        private readonly array $rules,

        /**
         * @var array<int, string> Fields for upsert duplicate detection.
         */
        private readonly array $uniqueBy,

        /**
         * @var array<string, string> Map of attribute names to transformer class references.
         */
        private readonly array $transformers,

        /**
         * @var int Number of rows per import batch.
         */
        private readonly int $chunkSize,

        /**
         * @var string|null Tenant ID to auto-fill on imported records.
         */
        private readonly ?string $tenantId,

        /**
         * @var CsvSettings CSV formatting configuration.
         */
        private readonly CsvSettings $csvSettings,
    ) {
    }

    // =========================================================================
    // ToModel
    // =========================================================================

    /**
     * Map a spreadsheet row to an Eloquent model instance.
     *
     * Iterates the field map to extract values from the row using
     * column headers as keys. Applies configured transformers to
     * each field value. If a tenant ID is set, adds it to the
     * model attributes. Returns a new model instance populated
     * with the mapped attributes.
     *
     * @param  array<string, mixed>  $row  The spreadsheet row keyed by column headers.
     *
     * @return Model The new model instance with mapped attributes.
     */
    public function model(array $row): Model
    {
        $attributes = [];

        foreach ($this->fieldMap as $columnHeader => $attributeName) {
            $value = $row[$columnHeader] ?? null;

            if (isset($this->transformers[$attributeName])) {
                $value = resolve($this->transformers[$attributeName])->transform($value);
            }

            $attributes[$attributeName] = $value;
        }

        if ($this->tenantId !== null) {
            $attributes['tenant_id'] = $this->tenantId;
        }

        return new ($this->modelClass)($attributes);
    }

    // =========================================================================
    // WithValidation
    // =========================================================================

    /**
     * Return the validation rules for each imported row.
     *
     * @return array<string, string> The Laravel validation rules keyed by attribute name.
     */
    public function rules(): array
    {
        return $this->rules;
    }

    // =========================================================================
    // WithUpserts
    // =========================================================================

    /**
     * Return the unique-by fields for upsert duplicate detection.
     *
     * @return array<int, string> The field names used to detect duplicates.
     */
    public function uniqueBy(): array
    {
        return $this->uniqueBy;
    }

    // =========================================================================
    // WithBatchInserts
    // =========================================================================

    /**
     * Return the batch size for bulk insert operations.
     *
     * @return int The number of rows per batch insert.
     */
    public function batchSize(): int
    {
        return $this->chunkSize;
    }

    // =========================================================================
    // WithChunkReading
    // =========================================================================

    /**
     * Return the chunk size for reading the import file.
     *
     * @return int The number of rows per read chunk.
     */
    public function chunkSize(): int
    {
        return $this->chunkSize;
    }

    // =========================================================================
    // WithCustomCsvSettings
    // =========================================================================

    /**
     * Return the custom CSV settings for this import.
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
