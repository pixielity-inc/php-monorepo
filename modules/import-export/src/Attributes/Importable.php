<?php

declare(strict_types=1);

/**
 * Importable Attribute.
 *
 * Declares a model class as importable by the import-export engine.
 * Place this attribute on any Eloquent model to register it for
 * import operations. The EntityRegistry discovers all classes
 * annotated with this attribute at boot time via the Discovery facade.
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\EntityRegistryInterface
 * @see \Pixielity\ImportExport\Contracts\ImportManagerInterface
 */

namespace Pixielity\ImportExport\Attributes;

use Attribute;

/**
 * Importable Attribute for Model Classes.
 *
 * Configures import behavior for a model: field mapping from column
 * headers to model attributes, validation rules per field, unique
 * fields for duplicate detection (upsert), human-readable label,
 * chunk size for batched processing, optional field transformers,
 * and accepted file formats.
 *
 * When `fields` is empty, the ImportManager uses column headers as-is.
 *
 * When `formats` is empty, all ImportFormat cases are allowed.
 *
 * Usage:
 *   ```php
 *   use Pixielity\ImportExport\Attributes\Importable;
 *   use Pixielity\User\Contracts\Data\UserInterface;
 *
 *   #[Importable(
 *       fields: [
 *           'Full Name'     => UserInterface::ATTR_NAME,
 *           'Email Address' => UserInterface::ATTR_EMAIL,
 *       ],
 *       rules: [
 *           UserInterface::ATTR_NAME  => 'required|string|max:255',
 *           UserInterface::ATTR_EMAIL => 'required|email|max:255',
 *       ],
 *       uniqueBy: [UserInterface::ATTR_EMAIL],
 *       label: 'Users',
 *       chunkSize: 250,
 *       transformers: [
 *           UserInterface::ATTR_EMAIL => LowercaseTransformer::class,
 *       ],
 *       formats: [ImportFormat::CSV, ImportFormat::XLSX],
 *   )]
 *   class User extends Model { }
 *   ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Importable
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
        * Attribute parameter name for rules.
        *
        * @var string
        */
       public const ATTR_RULES = 'rules';

       /**
        * Attribute parameter name for unique-by fields.
        *
        * @var string
        */
       public const ATTR_UNIQUE_BY = 'uniqueBy';

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
        * Attribute parameter name for transformers.
        *
        * @var string
        */
       public const ATTR_TRANSFORMERS = 'transformers';

       /**
        * Attribute parameter name for formats.
        *
        * @var string
        */
       public const ATTR_FORMATS = 'formats';

       // =========================================================================
       // Constructor
       // =========================================================================

       /**
        * Create a new Importable attribute instance.
        *
        * @param  array<string, string>  $fields        Map of column headers to model attribute names (ATTR_* constants).
        * @param  array<string, string>  $rules         Laravel validation rules keyed by model attribute name.
        * @param  array<int, string>     $uniqueBy      Fields used for duplicate detection (upsert).
        * @param  string                 $label         Human-readable entity name for UI display.
        * @param  int                    $chunkSize     Number of rows per batch during import (default 500).
        * @param  array<string, string>  $transformers  Map of field names to transformer class references for value transformation.
        * @param  array<int, string>     $formats       Allowed ImportFormat values. Empty array = all formats.
        */
       public function __construct(
              /** 
               * @var array<string, string> Map of column headers to ATTR_* constants. 
               */
              public array $fields = [],
              /** 
               * @var array<string, string> Validation rules keyed by attribute name. 
               */
              public array $rules = [],
              /** 
               * @var array<int, string> Fields for upsert duplicate detection. 
               */
              public array $uniqueBy = [],
              /** 
               * @var string Human-readable entity name. 
               */
              public string $label = '',
              /** 
               * @var int Rows per import batch. 
               */
              public int $chunkSize = 500,
              /** 
               * @var array<string, string> Field name → transformer class map. 
               */
              public array $transformers = [],
              /** 
               * @var array<int, string> Allowed ImportFormat values; empty = all. 
               */
              public array $formats = [],
       ) {
       }
}
