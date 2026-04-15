<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * Filterable Attribute for Repository Classes.
 *
 * Declares which fields are filterable via request query parameters and
 * which operators are allowed per field. Replaces Purity's model-based
 * Filterable trait — filtering logic lives in the repository, not the model.
 *
 * ```php
 * #[Filterable([
 *     'name' => ['$eq', '$contains', '$startsWith'],
 *     'email' => ['$eq', '$contains'],
 *     'status' => ['$eq', '$in', '$ne'],
 *     'created_at' => ['$gt', '$gte', '$lt', '$lte', '$between'],
 *     'price' => '*',  // all operators allowed
 * ])]
 * class ProductRepository extends Repository {}
 * ```
 *
 * If a field maps to `'*'`, all operators are allowed.
 * If the entire attribute is `#[Filterable('*')]`, all fields and operators are allowed.
 *
 * @since 2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Filterable
{
    /** 
 * @var array<string, array<string>|string> Field → operators map, or '*' for all. 
 */
    public array|string $fields;

    /**
     * @param  array<string, array<string>|string>|string  $fields  Field-operator map or '*' for unrestricted.
     */
    public function __construct(array|string $fields = '*')
    {
        $this->fields = $fields;
    }
}
