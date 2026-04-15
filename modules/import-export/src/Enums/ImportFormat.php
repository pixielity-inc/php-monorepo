<?php

declare(strict_types=1);

/**
 * Import Format Enum.
 *
 * Defines the supported file formats for entity import operations.
 * Each case maps to a Laravel Excel reader type constant. JSON
 * imports are handled outside Laravel Excel (returns null for
 * laravelExcelType).
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @see \Maatwebsite\Excel\Excel
 * @see \Pixielity\ImportExport\Contracts\ImportManagerInterface
 */

namespace Pixielity\ImportExport\Enums;

use Maatwebsite\Excel\Excel;
use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Import Format Enum.
 *
 * Backed string enum representing the file formats accepted for
 * data import: CSV, XLSX, and JSON. Provides a helper method for
 * Laravel Excel reader type mapping.
 *
 * Usage:
 *   $format = ImportFormat::CSV;
 *   $type   = $format->laravelExcelType(); // 'Csv'
 *
 * @method static CSV()  Returns the CSV enum instance
 * @method static XLSX() Returns the XLSX enum instance
 * @method static JSON() Returns the JSON enum instance
 */
enum ImportFormat: string
{
    use Enum;

    // =========================================================================
    // Cases
    // =========================================================================

    /**
     * Comma-separated values format.
     */
    #[Label('CSV')]
    #[Description('Comma-separated values file for universal spreadsheet compatibility.')]
    case CSV = 'csv';

    /**
     * Excel spreadsheet format (Office Open XML).
     */
    #[Label('XLSX')]
    #[Description('Excel spreadsheet format with full formatting and multi-sheet support.')]
    case XLSX = 'xlsx';

    /**
     * JSON array format.
     */
    #[Label('JSON')]
    #[Description('JSON array of objects for programmatic consumption and API integration.')]
    case JSON = 'json';

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Get the Laravel Excel reader type constant for this format.
     *
     * Returns the appropriate `Maatwebsite\Excel\Excel::*` constant
     * for CSV and XLSX formats. Returns null for JSON since JSON
     * imports are handled directly by the ImportManager without
     * Laravel Excel.
     *
     * @return string|null The Laravel Excel reader type constant, or null for JSON.
     */
    public function laravelExcelType(): ?string
    {
        return match ($this) {
            self::CSV  => Excel::CSV,
            self::XLSX => Excel::XLSX,
            self::JSON => null,
        };
    }
}
