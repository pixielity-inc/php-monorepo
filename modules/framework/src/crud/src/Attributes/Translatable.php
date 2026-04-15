<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * Translatable Attribute for Model or Repository Classes.
 *
 * Declares which fields are translatable (stored as JSON with locale keys).
 * When placed on a repository, the RepositoryConfigRegistry stores this info
 * so the repository can:
 * - Auto-apply locale-aware ordering (e.g., `orderBy("name->en")`)
 * - Auto-apply locale-aware filtering
 * - Expose translatable field metadata for API responses
 *
 * The actual translation behavior (getAttribute/setAttribute override) is
 * handled by Spatie's HasTranslations trait on the MODEL — this attribute
 * just declares which fields are translatable for the repository layer.
 *
 * ```php
 * // On the repository (declares translatable fields for query logic)
 * #[AsRepository]
 * #[UseModel(ProductInterface::class)]
 * #[Translatable(['name', 'description', 'slug'])]
 * class ProductRepository extends Repository {}
 *
 * // On the model (actual translation behavior via Spatie)
 * #[Table('products')]
 * class Product extends Model {
 *     use HasTranslations;
 *     public array $translatable = ['name', 'description', 'slug'];
 * }
 * ```
 *
 * @since 2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Translatable
{
    /** 
 * @var array<int, string> Translatable field names. 
 */
    public array $fields;

    /** 
 * @var string|null Default locale (null = app locale). 
 */
    public ?string $defaultLocale;

    /**
     * @param  array<int, string>  $fields  Translatable field names.
     * @param  string|null  $defaultLocale  Default locale override (null = use app locale).
     */
    public function __construct(
        array $fields,
        ?string $defaultLocale = null,
    ) {
        $this->fields = $fields;
        $this->defaultLocale = $defaultLocale;
    }
}
