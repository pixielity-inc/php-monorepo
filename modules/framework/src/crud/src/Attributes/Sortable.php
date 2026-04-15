<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * Sortable Attribute for Repository Classes.
 *
 * Declares which fields are sortable via request query parameters.
 * Replaces Purity's model-based Sortable trait.
 *
 * ```php
 * #[Sortable(['name', 'email', 'created_at', 'price'])]
 * class ProductRepository extends Repository {}
 * ```
 *
 * Supports relation sorting: `?sort=category.name:desc`
 *
 * If `'*'` is passed, all model columns are sortable.
 *
 * @since 2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Sortable
{
    /** 
 * @var array<string>|string Sortable field names or '*' for all. 
 */
    public array|string $fields;

    /**
     * @param  array<string>|string  $fields  Sortable fields or '*' for unrestricted.
     */
    public function __construct(array|string $fields = '*')
    {
        $this->fields = $fields;
    }
}
