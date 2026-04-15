<?php

declare(strict_types=1);

namespace Pixielity\Crud\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Pixielity\Crud\Attributes\AsCriteria;
use Pixielity\Crud\Contracts\CriteriaInterface;
use Pixielity\Crud\Contracts\RepositoryInterface;

/**
 * Translatable Criteria.
 *
 * Applies locale-aware filtering and ordering to translatable JSON columns.
 * Works with Spatie's HasTranslations trait which stores translations as
 * JSON objects: `{"en": "Hello", "ar": "مرحبا"}`.
 *
 * When applied, this criteria:
 *   1. Rewrites ORDER BY clauses on translatable fields to use JSON arrow
 *      notation for the current locale (e.g., `name` → `name->en`)
 *   2. Rewrites WHERE clauses on translatable fields to use JSON arrow
 *      notation for the current locale
 *   3. Supports explicit locale override via constructor parameter
 *
 * ## How JSON Arrow Notation Works:
 * MySQL/PostgreSQL support querying JSON columns with `->` syntax:
 *   - `name->en` extracts the "en" key from the JSON column
 *   - `WHERE name->en LIKE '%laptop%'` searches the English translation
 *   - `ORDER BY name->en ASC` sorts by the English translation
 *
 * ## Usage:
 * ```php
 * // Applied automatically when repository has #[Translatable] attribute
 * // and filter()/sort() is called
 *
 * // Manual usage:
 * $repository->pushCriteria(new TranslatableCriteria(
 *     translatableFields: ['name', 'description'],
 *     locale: 'ar',
 * ));
 *
 * // Or with current app locale (default):
 * $repository->pushCriteria(new TranslatableCriteria(
 *     translatableFields: ['name', 'description'],
 * ));
 * ```
 *
 * ## Request Examples:
 * ```
 * GET /api/products?filters[name][$contains]=laptop&sort=name:asc
 * ```
 * With locale 'en', this becomes:
 * ```sql
 * WHERE name->en LIKE '%laptop%' ORDER BY name->en ASC
 * ```
 *
 * @category Criteria
 *
 * @since    2.0.0
 */
#[AsCriteria(
    name: 'translatable',
    description: 'Applies locale-aware filtering and ordering to translatable JSON columns.',
)]
class TranslatableCriteria implements CriteriaInterface
{
    /**
     * The translatable field names.
     *
     * @var array<int, string>
     */
    protected array $translatableFields;

    /**
     * The locale to use for JSON arrow notation.
     * Null means use the current application locale.
     */
    protected ?string $locale;

    /**
     * Create a new TranslatableCriteria instance.
     *
     * @param  array<int, string>  $translatableFields  The translatable field names.
     * @param  string|null  $locale  The locale to use (null = app locale).
     */
    public function __construct(
        array $translatableFields = [],
        ?string $locale = null,
    ) {
        $this->translatableFields = $translatableFields;
        $this->locale = $locale;
    }

    /**
     * Apply locale-aware column qualification to the query.
     *
     * Rewrites any existing ORDER BY and WHERE clauses on translatable
     * fields to use JSON arrow notation for the resolved locale.
     *
     * @param  Builder<Model>  $query  The query builder.
     * @param  RepositoryInterface  $repository  The repository instance.
     * @return Builder<Model> The modified query builder.
     */
    public function apply(Builder $query, RepositoryInterface $repository): Builder
    {
        if ($this->translatableFields === []) {
            return $query;
        }

        $locale = $this->resolveLocale();

        // Rewrite ORDER BY clauses for translatable fields
        $this->rewriteOrderBy($query, $locale);

        return $query;
    }

    /**
     * Resolve the locale to use for JSON arrow notation.
     *
     * Uses the explicitly provided locale, or falls back to the
     * current application locale.
     *
     * @return string The resolved locale (e.g., 'en', 'ar').
     */
    protected function resolveLocale(): string
    {
        return $this->locale ?? app()->getLocale();
    }

    /**
     * Qualify a column name with locale if it's a translatable field.
     *
     * Converts 'name' → 'name->en' for translatable fields.
     * Non-translatable fields are returned unchanged.
     *
     * @param  string  $column  The column name.
     * @param  string  $locale  The locale to append.
     * @return string The qualified column name.
     */
    public function qualifyColumn(string $column, ?string $locale = null): string
    {
        $locale ??= $this->resolveLocale();

        // Strip any existing locale suffix (e.g., 'name->en' → 'name')
        $baseColumn = str_contains($column, '->') ? explode('->', $column)[0] : $column;

        if (! \in_array($baseColumn, $this->translatableFields, true)) {
            return $column;
        }

        return "{$baseColumn}->{$locale}";
    }

    /**
     * Check if a column is a translatable field.
     *
     * @param  string  $column  The column name to check.
     * @return bool True if the column is translatable.
     */
    public function isTranslatable(string $column): bool
    {
        $baseColumn = str_contains($column, '->') ? explode('->', $column)[0] : $column;

        return \in_array($baseColumn, $this->translatableFields, true);
    }

    /**
     * Get the translatable field names.
     *
     * @return array<int, string> The translatable field names.
     */
    public function getTranslatableFields(): array
    {
        return $this->translatableFields;
    }

    /**
     * Rewrite ORDER BY clauses to use locale-qualified column names.
     *
     * Iterates over existing ORDER BY clauses and replaces translatable
     * column names with their JSON arrow notation equivalents.
     *
     * @param  Builder<Model>  $query  The query builder.
     * @param  string  $locale  The locale to use.
     */
    protected function rewriteOrderBy(Builder $query, string $locale): void
    {
        $orders = $query->getQuery()->orders ?? [];

        if ($orders === []) {
            return;
        }

        $rewritten = [];

        foreach ($orders as $order) {
            if (isset($order['column']) && $this->isTranslatable($order['column'])) {
                $order['column'] = $this->qualifyColumn($order['column'], $locale);
            }
            $rewritten[] = $order;
        }

        $query->getQuery()->orders = $rewritten;
    }
}
