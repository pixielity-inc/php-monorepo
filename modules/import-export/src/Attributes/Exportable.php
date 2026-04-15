<?php

declare(strict_types=1);

/**
 * Exportable Attribute.
 *
 * Declares a model class as exportable by the import-export engine.
 * Place this attribute on any Eloquent model to register it for
 * export operations. The EntityRegistry discovers all classes
 * annotated with this attribute at boot time via the Discovery facade.
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\EntityRegistryInterface
 * @see \Pixielity\ImportExport\Contracts\ExportManagerInterface
 */

namespace Pixielity\ImportExport\Attributes;

use Attribute;

/**
 * Exportable Attribute for Model Classes.
 *
 * Configures export behavior for a model: which fields to export,
 * supported formats, human-readable label, chunk size for batched
 * processing, and optional field formatters for value transformation.
 *
 * When `fields` is empty, the ExportManager falls back to the model's
 * `$fillable` array or `toArray()` output.
 *
 * When `formats` is empty, all ExportFormat cases are allowed.
 *
 * Usage:
 *   ```php
 *   use Pixielity\ImportExport\Attributes\Exportable;
 *   use Pixielity\User\Contracts\Data\UserInterface;
 *
 *   #[Exportable(
 *       fields: [
 *           UserInterface::ATTR_NAME  => 'Full Name',
 *           UserInterface::ATTR_EMAIL => 'Email Address',
 *       ],
 *       formats: [ExportFormat::CSV, ExportFormat::XLSX],
 *       label: 'Users',
 *       chunkSize: 500,
 *       formatters: [
 *           UserInterface::ATTR_NAME => UpperCaseFormatter::class,
 *       ],
 *   )]
 *   class User extends Model { }
 *   ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Exportable
{
       // =========================================================================
       // ATTR_* Constants
       // =========================================================================

       /**
        * Attribute parameter name for fields.
        *
        * @var string
        */
       public const ATTR_FIELDS = 'fields';

       /**
        * Attribute parameter name for formats.
        *
        * @var string
        */
       public const ATTR_FORMATS = 'formats';

       /**
        * Attribute parameter name for label.
        *
        * @var string
        */
       public const ATTR_LABEL = 'label';

       /**
        * Attribute parameter name for chunk size.
        *
        * @var string
        */
       public const ATTR_CHUNK_SIZE = 'chunkSize';

       /**
        * Attribute parameter name for formatters.
        *
        * @var string
        */
       public const ATTR_FORMATTERS = 'formatters';

       // =========================================================================
       // Constructor
       // =========================================================================

       /**
        * Create a new Exportable attribute instance.
        *
        * @param  array<string, string>  $fields      Map of model attribute names (ATTR_* constants) to column headers.
        *                                              Empty array = export all fillable attributes.
        * @param  array<int, string>     $formats     Allowed ExportFormat values. Empty array = all formats.
        * @param  string                 $label       Human-readable entity name for UI display.
        * @param  int                    $chunkSize   Number of rows per batch during export (default 1000).
        * @param  array<string, string>  $formatters  Map of field names to formatter class references for value transformation.
        */
       public function __construct(
              /** 
               * @var array<string, string> Map of ATTR_* constants to column headers. 
               */
              public array $fields = [],
              /** 
               * @var array<int, string> Allowed ExportFormat values; empty = all. 
               */
              public array $formats = [],
              /** 
               * @var string Human-readable entity name. 
               */
              public string $label = '',
              /** 
               * @var int Rows per export batch. 
               */
              public int $chunkSize = 1000,
              /** 
               * @var array<string, string> Field name → formatter class map. 
               */
              public array $formatters = [],
       ) {
       }
}
