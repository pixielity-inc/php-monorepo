<?php

declare(strict_types=1);

/**
 * Export Request Data DTO.
 *
 * Spatie Data DTO for export request validation and hydration.
 * Captures all parameters needed to initiate an export operation:
 * entity identifier, format, optional column selection, optional
 * filters, and optional CSV separator overrides.
 *
 * @category Data
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\ExportManagerInterface
 * @see \Pixielity\ImportExport\Controllers\ImportExportController
 */

namespace Pixielity\ImportExport\Data;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

/**
 * Export Request Data DTO.
 *
 * Extends Spatie Data for automatic request validation and casting.
 * The `entity` and `format` fields are required; all others are
 * optional with null defaults.
 *
 * Usage:
 *   ```php
 *   $request = ExportRequestData::from([
 *       'entity'  => 'users',
 *       'format'  => 'xlsx',
 *       'columns' => ['name', 'email'],
 *       'filters' => ['status' => 'active'],
 *   ]);
 *   $jobId = $exportManager->export($request, auth()->id());
 *   ```
 */
class ExportRequestData extends Data
{
    /**
     * Create a new ExportRequestData instance.
     *
     * @param  string       $entity               The entity identifier (e.g., 'users', 'products').
     * @param  string       $format               The desired export format (ExportFormat value).
     * @param  array|null   $columns              Subset of declared fields to export; null = all fields.
     * @param  array|null   $filters              CRUD filter operators for query scoping; null = no filters.
     * @param  string|null  $fieldSeparator       CSV field separator override; null = config default.
     * @param  string|null  $multiValueSeparator  Multi-value separator override; null = config default.
     * @param  string|null  $enclosure            Field enclosure override; null = config default.
     */
    public function __construct(
        /** 
 * @var string Entity identifier. 
 */
        #[Required, StringType]
        public string $entity,
        /** 
 * @var string Export format value. 
 */
        #[Required, StringType]
        public string $format,
        /** 
 * @var array|null Optional column subset. 
 */
        public ?array $columns = null,
        /** 
 * @var array|null Optional CRUD filter criteria. 
 */
        public ?array $filters = null,
        /** 
 * @var string|null CSV field separator override. 
 */
        public ?string $fieldSeparator = null,
        /** 
 * @var string|null Multi-value separator override. 
 */
        public ?string $multiValueSeparator = null,
        /** 
 * @var string|null Field enclosure override. 
 */
        public ?string $enclosure = null,
    ) {
    }
}
