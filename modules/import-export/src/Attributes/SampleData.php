<?php

declare(strict_types=1);

/**
 * SampleData Attribute.
 *
 * Declares a model class as capable of generating sample/seed data.
 * Place this attribute on any Eloquent model to register it for
 * sample data generation. The EntityRegistry discovers all classes
 * annotated with this attribute at boot time via the Discovery facade.
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\EntityRegistryInterface
 * @see \Pixielity\ImportExport\Contracts\SampleDataGeneratorInterface
 */

namespace Pixielity\ImportExport\Attributes;

use Attribute;

/**
 * SampleData Attribute for Model Classes.
 *
 * Configures sample data generation for a model: the factory or
 * generator class to use, the default number of records to create,
 * and a human-readable label for UI display.
 *
 * For tenant-scoped entities, the SampleDataGenerator automatically
 * fills the tenant_id column with the current tenant context.
 *
 * Usage:
 *   ```php
 *   use Pixielity\ImportExport\Attributes\SampleData;
 *
 *   #[SampleData(
 *       factory: UserFactory::class,
 *       count: 25,
 *       label: 'Users',
 *   )]
 *   class User extends Model { }
 *   ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class SampleData
{
       // =========================================================================
       // ATTR_* Constants
       // =========================================================================

       /**
        * Attribute parameter name for factory.
        *
        * @var string
        */
       public const ATTR_FACTORY = 'factory';

       /**
        * Attribute parameter name for count.
        *
        * @var string
        */
       public const ATTR_COUNT = 'count';

       /**
        * Attribute parameter name for label.
        *
        * @var string
        */
       public const ATTR_LABEL = 'label';

       // =========================================================================
       // Constructor
       // =========================================================================

       /**
        * Create a new SampleData attribute instance.
        *
        * @param  string  $factory  Class reference to a Laravel model factory or custom generator class.
        * @param  int     $count    Default number of records to generate (default 10).
        * @param  string  $label    Human-readable entity name for UI display.
        */
       public function __construct(
              /** 
               * @var string Factory or generator class-string. 
               */
              public string $factory = '',
              /** 
               * @var int Default number of records to generate. 
               */
              public int $count = 10,
              /** 
               * @var string Human-readable entity name. 
               */
              public string $label = '',
       ) {
       }
}
