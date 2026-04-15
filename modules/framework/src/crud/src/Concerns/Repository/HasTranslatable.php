<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Repository;

use Pixielity\Crud\Criteria\TranslatableCriteria;

/**
 * HasTranslatable Trait.
 *
 * Provides translatable column qualification for repositories that
 * work with multi-locale JSON columns (Spatie HasTranslations pattern).
 *
 * This is the REPOSITORY-layer trait for translatable fields. It handles
 * locale-aware query logic (ORDER BY, WHERE with JSON arrow notation).
 *
 * The MODEL-layer translation behavior (getAttribute/setAttribute override)
 * is handled by Spatie's HasTranslations trait — the developer adds that
 * directly on the model class:
 *
 * ```php
 * // Model: uses Spatie's trait for read/write translation behavior
 * class Product extends Model {
 *     use \Spatie\Translatable\HasTranslations;
 *     public array $translatable = ['name', 'description'];
 * }
 *
 * // Repository: uses this trait (via base Repository) for query-layer locale qualification
 * #[Translatable(['name', 'description'])]
 * class ProductRepository extends Repository { }
 * ```
 *
 * When the repository has a #[Translatable] attribute, this trait:
 *   1. Stores the translatable field names from the attribute
 *   2. Provides qualifyTranslatableColumn() for locale-aware column names
 *   3. Creates and pushes a TranslatableCriteria for ORDER BY rewriting
 *
 * The #[Translatable] attribute is resolved at boot time by the
 * RepositoryConfigRegistry — zero runtime reflection.
 *
 * ## How It Works:
 * ```
 * #[Translatable(['name', 'description'])]
 * class ProductRepository extends Repository { }
 *
 * // When ordering by 'name', the trait qualifies it as 'name->en'
 * // (or 'name->ar' depending on the current app locale)
 * $repository->orderBy('name', 'asc');
 * // SQL: ORDER BY name->en ASC
 * ```
 *
 * @since 2.0.0
 */
trait HasTranslatable
{
    /**
     * Translatable fields from #[Translatable] attribute.
     *
     * Populated at boot time from the RepositoryConfigRegistry.
     *
     * @var array<int, string>
     */
    protected array $translatableFields = [];

    /**
     * Default locale for translatable fields (null = app locale).
     *
     * Populated at boot time from the #[Translatable] attribute's
     * defaultLocale parameter.
     */
    protected ?string $translatableDefaultLocale = null;

    /**
     * Cached TranslatableCriteria instance.
     *
     * Created lazily on first call to getTranslatableCriteria().
     */
    private ?TranslatableCriteria $translatableCriteria = null;

    /**
     * Qualify a column name for translatable fields.
     *
     * If the column is in the translatable fields list, appends the locale
     * suffix using JSON arrow notation (e.g., 'name' → 'name->en').
     * Non-translatable columns are returned unchanged.
     *
     * @param  string  $column  The column name to qualify.
     * @return string The qualified column name.
     */
    protected function qualifyTranslatableColumn(string $column): string
    {
        return $this->getTranslatableCriteria()->qualifyColumn($column);
    }

    /**
     * Check if a column is a translatable field.
     *
     * @param  string  $column  The column name to check.
     * @return bool True if the column is translatable.
     */
    protected function isTranslatableColumn(string $column): bool
    {
        if ($this->translatableFields === []) {
            return false;
        }

        return $this->getTranslatableCriteria()->isTranslatable($column);
    }

    /**
     * Get or create the TranslatableCriteria instance.
     *
     * Lazily creates the criteria from the stored translatable fields
     * and default locale. The criteria is cached for the lifetime of
     * the repository instance.
     *
     * @return TranslatableCriteria The translatable criteria instance.
     */
    protected function getTranslatableCriteria(): TranslatableCriteria
    {
        if ($this->translatableCriteria === null) {
            $this->translatableCriteria = new TranslatableCriteria(
                translatableFields: $this->translatableFields,
                locale: $this->translatableDefaultLocale,
            );
        }

        return $this->translatableCriteria;
    }

    /**
     * Apply the translatable criteria to the current query.
     *
     * Pushes the TranslatableCriteria onto the criteria stack so that
     * ORDER BY clauses on translatable fields are rewritten with
     * locale-qualified JSON arrow notation.
     *
     * Called automatically by prepareQuery() when translatable fields
     * are configured.
     */
    protected function applyTranslatableCriteria(): void
    {
        if ($this->translatableFields === []) {
            return;
        }

        $this->pushCriteria($this->getTranslatableCriteria());
    }
}
