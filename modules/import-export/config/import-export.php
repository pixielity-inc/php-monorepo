<?php

declare(strict_types=1);

/**
 * Import/Export Package Configuration.
 *
 * Central configuration for the pixielity/laravel-import-export package.
 * Controls export/import behavior, CSV formatting, queue settings,
 * real-time broadcasting, sample data generation, and PDF output.
 *
 * All values can be overridden per-request via the API endpoints
 * where applicable (e.g., CSV separators, format, column selection).
 *
 * @category Configuration
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Providers\ImportExportServiceProvider
 */
return [

    // =========================================================================
    // Export Settings
    // =========================================================================

    'export' => [

        /**
         * Default export file format when none is specified in the request.
         *
         * Supported: 'csv', 'xlsx', 'json', 'pdf'
         *
         * @see \Pixielity\ImportExport\Enums\ExportFormat
         */
        'default_format' => 'xlsx',

        /**
         * Number of rows per chunk when processing large exports.
         *
         * Exports are always queued — this controls how many rows
         * are written per batch in the queued job.
         */
        'chunk_size' => 1000,

        /**
         * Filesystem disk for storing generated export files.
         *
         * Must be a valid disk name from config/filesystems.php.
         */
        'storage_disk' => 'local',

        /**
         * Directory path (relative to disk root) for export files.
         *
         * Files are stored as: {storage_path}/{jobId}.{extension}
         */
        'storage_path' => 'exports',
    ],

    // =========================================================================
    // Import Settings
    // =========================================================================

    'import' => [

        /**
         * Number of rows per chunk when processing large imports.
         *
         * Controls both WithChunkReading and WithBatchInserts sizes
         * in the DynamicEntityImport class.
         */
        'chunk_size' => 500,

        /**
         * Filesystem disk for storing uploaded import files.
         *
         * Must be a valid disk name from config/filesystems.php.
         */
        'storage_disk' => 'local',

        /**
         * Directory path (relative to disk root) for uploaded import files.
         */
        'storage_path' => 'imports',

        /**
         * Maximum allowed file size for import uploads, in kilobytes.
         *
         * Files exceeding this limit are rejected at the controller level.
         */
        'max_file_size' => 10240,

        /**
         * Maximum number of row-level errors before halting the import.
         *
         * 0 = unlimited (process all rows regardless of errors).
         * N = halt after N validation failures.
         */
        'allowed_errors' => 0,

        /**
         * Behavior when a row fails validation during import.
         *
         * 'skip'  — Skip the invalid row and continue processing.
         * 'stop'  — Halt the entire import on the first error.
         */
        'on_error' => 'skip',
    ],

    // =========================================================================
    // CSV Separator Settings
    // =========================================================================

    'csv' => [

        /**
         * Column delimiter character for CSV files.
         *
         * Common values: ',' (comma), '|' (pipe), ';' (semicolon), "\t" (tab).
         * Can be overridden per-request via the API.
         */
        'field_separator' => ',',

        /**
         * Separator for multiple values within a single CSV field.
         *
         * Used when a field contains multiple values (e.g., tags: "tag1|tag2|tag3").
         * The import transformer splits on this character; the export formatter joins with it.
         */
        'multi_value_separator' => '|',

        /**
         * Field enclosure character for CSV files.
         *
         * Wraps field values that contain the delimiter or newlines.
         * Common values: '"' (double quote), "'" (single quote).
         */
        'enclosure' => '"',
    ],

    // =========================================================================
    // Queue Settings
    // =========================================================================

    'queue' => [

        /**
         * Whether import/export jobs should be dispatched to the queue.
         *
         * When false, jobs run synchronously (not recommended for production).
         */
        'enabled' => true,

        /**
         * Queue connection name for import/export jobs.
         *
         * null = use the application's default queue connection.
         */
        'connection' => null,

        /**
         * Queue name for import/export jobs.
         *
         * Allows isolating import/export work from other queued jobs.
         */
        'queue_name' => 'import-export',
    ],

    // =========================================================================
    // Broadcasting Settings
    // =========================================================================

    'broadcasting' => [

        /**
         * Whether job lifecycle events should be broadcast via Laravel Broadcasting.
         *
         * When enabled, events like ExportCompleted and ImportCompleted are
         * broadcast on a private channel so the frontend can react in real-time.
         */
        'enabled' => true,

        /**
         * Prefix for the private broadcasting channel.
         *
         * The full channel name is: private-{channel_prefix}.{userId}.import-export
         * Default produces: private-user.{userId}.import-export
         */
        'channel_prefix' => 'user',
    ],

    // =========================================================================
    // Sample Data Settings
    // =========================================================================

    'sample_data' => [

        /**
         * Default number of records to generate when no count is specified.
         *
         * Used by the SampleDataGenerator when the API request omits the count parameter.
         */
        'default_count' => 10,
    ],

    // =========================================================================
    // PDF Export Settings
    // =========================================================================

    'pdf' => [

        /**
         * Paper size for PDF exports.
         *
         * Supported values depend on the PDF driver (dompdf/mpdf).
         * Common: 'a4', 'letter', 'legal', 'a3'.
         */
        'paper_size' => 'a4',

        /**
         * Page orientation for PDF exports.
         *
         * 'landscape' is recommended for data tables with many columns.
         * Supported: 'portrait', 'landscape'.
         */
        'orientation' => 'landscape',
    ],
];
