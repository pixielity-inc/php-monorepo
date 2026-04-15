<?php

declare(strict_types=1);

/**
 * CSV Settings Value Object.
 *
 * Immutable value object encapsulating CSV formatting configuration
 * for import and export operations. Supports both global defaults
 * from the config file and per-request overrides from the API.
 *
 * @category Data
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Concerns\DynamicEntityExport
 * @see \Pixielity\ImportExport\Concerns\DynamicEntityImport
 */

namespace Pixielity\ImportExport\Data;

use Illuminate\Container\Attributes\Config;

/**
 * CSV Settings Value Object.
 *
 * Final readonly DTO holding the three CSV formatting parameters:
 * field separator (column delimiter), multi-value separator (for
 * fields containing lists), and field enclosure character.
 *
 * Provides two static factory methods:
 * - `fromConfig()` reads global defaults from `config/import-export.php`
 * - `fromRequest()` merges per-request overrides with config defaults
 *
 * Usage:
 *   // From global config
 *   $settings = CsvSettings::fromConfig();
 *
 *   // From API request with optional overrides
 *   $settings = CsvSettings::fromRequest(';', null, "'");
 */
final readonly class CsvSettings
{
    /**
     * Create a new CsvSettings instance.
     *
     * @param  string  $fieldSeparator       Column delimiter character (e.g., ',', ';', '|', "\t").
     * @param  string  $multiValueSeparator  Separator for multiple values within a single field (e.g., '|').
     * @param  string  $enclosure            Field enclosure character (e.g., '"', "'").
     */
    public function __construct(
            /** 
             * @var string Column delimiter character. 
             */
        #[Config('import-export.csv.field_separator', ',')]
        public string $fieldSeparator = ',',
            /** 
             * @var string Separator for multiple values within a single field. 
             */
        #[Config('import-export.csv.multi_value_separator', '|')]
        public string $multiValueSeparator = '|',
            /** 
             * @var string Field enclosure character. 
             */
        #[Config('import-export.csv.enclosure', '"')]
        public string $enclosure = '"',
    ) {
    }

    // =========================================================================
    // Static Factories
    // =========================================================================

    /**
     * Create a CsvSettings instance from the global configuration.
     *
     * Resolves via the container to leverage #[Config] attributes
     * on the constructor parameters.
     *
     * @return self A new CsvSettings populated from config values.
     */
    public static function fromConfig(): self
    {
        return app(self::class);
    }

    /**
     * Create a CsvSettings instance from per-request overrides.
     *
     * Merges the provided request parameters with the global config
     * defaults. Any null parameter falls back to the config value.
     *
     * @param  string|null  $fieldSeparator       Per-request field separator override, or null for config default.
     * @param  string|null  $multiValueSeparator  Per-request multi-value separator override, or null for config default.
     * @param  string|null  $enclosure            Per-request enclosure override, or null for config default.
     *
     * @return self A new CsvSettings with overrides applied.
     */
    public static function fromRequest(
        ?string $fieldSeparator = null,
        ?string $multiValueSeparator = null,
        ?string $enclosure = null,
    ): self {
        $defaults = self::fromConfig();

        return new self(
            fieldSeparator: $fieldSeparator ?? $defaults->fieldSeparator,
            multiValueSeparator: $multiValueSeparator ?? $defaults->multiValueSeparator,
            enclosure: $enclosure ?? $defaults->enclosure,
        );
    }
}
