<?php

declare(strict_types=1);

/**
 * Export Format Enum.
 *
 * Defines the supported file formats for entity export operations.
 * Each case maps to a specific MIME type, file extension, and
 * Laravel Excel writer type constant. JSON exports are handled
 * outside Laravel Excel (returns null for laravelExcelType).
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @see \Maatwebsite\Excel\Excel
 * @see \Pixielity\ImportExport\Contracts\ExportManagerInterface
 */

namespace Pixielity\ImportExport\Enums;

use Maatwebsite\Excel\Excel;
use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Export Format Enum.
 *
 * Backed string enum representing the file formats available for
 * data export: CSV, XLSX, JSON, and PDF. Provides helper methods
 * for MIME type resolution, file extension lookup, and Laravel
 * Excel writer type mapping.
 *
 * Usage:
 *   $format = ExportFormat::XLSX;
 *   $mime   = $format->mimeType();      // 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
 *   $ext    = $format->extension();     // 'xlsx'
 *   $type   = $format->laravelExcelType(); // 'Xlsx'
 *
 * @method static CSV()  Returns the CSV enum instance
 * @method static XLSX() Returns the XLSX enum instance
 * @method static JSON() Returns the JSON enum instance
 * @method static PDF()  Returns the PDF enum instance
 */
enum ExportFormat: string
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

    /**
     * PDF document format.
     */
    #[Label('PDF')]
    #[Description('PDF document for print-ready reports and archival.')]
    case PDF = 'pdf';

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Get the MIME type for this export format.
     *
     * @return string The MIME type string.
     */
    public function mimeType(): string
    {
        return match ($this) {
            self::CSV => 'text/csv',
            self::XLSX => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            self::JSON => 'application/json',
            self::PDF => 'application/pdf',
        };
    }

    /**
     * Get the file extension for this export format.
     *
     * @return string The file extension without a leading dot.
     */
    public function extension(): string
    {
        return match ($this) {
            self::CSV => 'csv',
            self::XLSX => 'xlsx',
            self::JSON => 'json',
            self::PDF => 'pdf',
        };
    }

    /**
     * Get the Laravel Excel writer type constant for this format.
     *
     * Returns the appropriate `Maatwebsite\Excel\Excel::*` constant
     * for CSV, XLSX, and PDF formats. Returns null for JSON since
     * JSON exports are handled directly by the ExportManager without
     * Laravel Excel.
     *
     * @return string|null The Laravel Excel writer type constant, or null for JSON.
     */
    public function laravelExcelType(): ?string
    {
        return match ($this) {
            self::CSV => Excel::CSV,
            self::XLSX => Excel::XLSX,
            self::PDF => Excel::DOMPDF,
            self::JSON => null,
        };
    }
}
