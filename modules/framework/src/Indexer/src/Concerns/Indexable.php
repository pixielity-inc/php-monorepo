<?php

declare(strict_types=1);

/**
 * Indexable Trait (Model Concern).
 *
 * Applied to Eloquent models that are opted into Elasticsearch indexing.
 * Provides document building via toIndexableArray(), index management
 * delegation via buildIndex() and removeIndex(), conditional exclusion
 * via excludeIndex(), and automatic observer chain registration on boot.
 *
 * The trait resolves embed configurations from Discovery at runtime
 * and delegates all index write operations to the container-resolved
 * IndexerInterface and RecordBuilderInterface implementations.
 *
 * @category Concerns
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Contracts\IndexerInterface
 * @see \Pixielity\Indexer\Contracts\RecordBuilderInterface
 * @see \Pixielity\Indexer\Attributes\Indexed
 */

namespace Pixielity\Indexer\Concerns;

use Pixielity\Crud\Attributes\Searchable;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\Indexer\Attributes\EmbedMany;
use Pixielity\Indexer\Attributes\EmbedOne;
use Pixielity\Indexer\Contracts\IndexerInterface;
use Pixielity\Indexer\Contracts\RecordBuilderInterface;

/**
 * Model concern for Elasticsearch indexing.
 *
 * Provides the core indexing API for models: document building,
 * index management delegation, and observer chain registration.
 * The host model must be an Eloquent model with a primary key.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Concerns\Indexable;
 *   use Pixielity\Indexer\Attributes\Indexed;
 *
 *   #[Indexed(label: 'Products')]
 *   class Product extends Model
 *   {
 *       use Indexable;
 *
 *       // Override to conditionally exclude records
 *       public function excludeIndex(): bool
 *       {
 *           return $this->status === 'draft';
 *       }
 *   }
 *   ```
 */
trait Indexable
{
    // =========================================================================
    // Boot — Observer Registration
    // =========================================================================

    /**
     * Boot the Indexable trait on the model.
     *
     * Resolves the ObserverRegistry from the container (if bound) and
     * registers the model class for observer chain registration. This
     * is where the search package hooks in — if the search package
     * isn't installed, the observer registration is silently skipped.
     *
     * @return void
     */
    public static function bootIndexable(): void
    {
        // Resolve the ObserverRegistry from the container and register
        // observers for this model class. The ObserverRegistry handles
        // both the base model observer and embedded model triggers.
        if (app()->bound(\Pixielity\Search\Observers\ObserverRegistry::class)) {
            app(\Pixielity\Search\Observers\ObserverRegistry::class)->register(static::class);
        }
    }

    // =========================================================================
    // Document Building
    // =========================================================================

    /**
     * Build the indexable document array for this model.
     *
     * Reads #[Searchable] fields from the model class via Discovery,
     * extracts the corresponding attribute values, then resolves
     * #[EmbedOne] and #[EmbedMany] relationships to build nested
     * objects within the document.
     *
     * @return array The flat document array with 'id' key.
     */
    public function toIndexableArray(): array
    {
        $forClass = Discovery::forClass(static::class);

        // Start with the model's primary key
        $document = ['id' => $this->getKey()];

        // 1. Read #[Searchable] fields and extract model attribute values
        $document = $this->extractSearchableFields($forClass, $document);

        // 2. Resolve #[EmbedOne] relationships
        $document = $this->resolveEmbedOneRelations($forClass, $document);

        // 3. Resolve #[EmbedMany] relationships
        $document = $this->resolveEmbedManyRelations($forClass, $document);

        return $document;
    }

    // =========================================================================
    // Index Management Delegation
    // =========================================================================

    /**
     * Build the full index record for this model via the RecordBuilder.
     *
     * Delegates to RecordBuilderInterface::build() which handles the
     * complete document construction pipeline including transforms
     * and validation.
     *
     * @return array The built ES document array.
     */
    public function buildIndex(): array
    {
        /** @var RecordBuilderInterface $builder */
        $builder = app(RecordBuilderInterface::class);

        return $builder->build(static::class, $this->getKey());
    }

    /**
     * Remove this model's document from the Elasticsearch index.
     *
     * Delegates to IndexerInterface::remove() for the actual
     * ES delete operation.
     *
     * @return bool Always returns true after successful removal.
     */
    public function removeIndex(): bool
    {
        /** @var IndexerInterface $indexer */
        $indexer = app(IndexerInterface::class);

        $indexer->remove($this);

        return true;
    }

    /**
     * Determine if this model should be excluded from indexing.
     *
     * Override this method in the model to conditionally exclude
     * specific records from the ES index (e.g., draft posts,
     * soft-deleted records, inactive products).
     *
     * @return bool True to exclude from indexing, false to include.
     */
    public function excludeIndex(): bool
    {
        return false;
    }

    // =========================================================================
    // Internal Helpers
    // =========================================================================

    /**
     * Extract searchable field values from the model.
     *
     * Reads the #[Searchable] attribute from the model class and
     * populates the document array with the corresponding attribute values.
     *
     * @param  object  $forClass  The Discovery ForClass object.
     * @param  array   $document  The document array being built.
     *
     * @return array The document array with searchable fields added.
     */
    private function extractSearchableFields(object $forClass, array $document): array
    {
        foreach ($forClass->classAttributes as $attr) {
            if ($attr instanceof Searchable) {
                foreach (array_keys($attr->fields) as $fieldName) {
                    $document[$fieldName] = $this->getAttribute($fieldName);
                }

                break;
            }
        }

        return $document;
    }

    /**
     * Resolve #[EmbedOne] relationships into nested objects.
     *
     * For each #[EmbedOne] declaration, loads the related model and
     * extracts the declared fields as a nested object in the document.
     * If the relation returns null, the embed field is set to null.
     *
     * @param  object  $forClass  The Discovery ForClass object.
     * @param  array   $document  The document array being built.
     *
     * @return array The document array with EmbedOne fields added.
     */
    private function resolveEmbedOneRelations(object $forClass, array $document): array
    {
        foreach ($forClass->classAttributes as $attr) {
            if (! ($attr instanceof EmbedOne)) {
                continue;
            }

            $related = $this->{$attr->field};

            if ($related === null) {
                $document[$attr->field] = null;

                continue;
            }

            $document[$attr->field] = $this->extractFieldsFromModel($related, $attr->fields);
        }

        return $document;
    }

    /**
     * Resolve #[EmbedMany] relationships into arrays of nested objects.
     *
     * For each #[EmbedMany] declaration, loads the related collection
     * (respecting limit and orderBy constraints) and extracts the
     * declared fields as an array of nested objects.
     *
     * @param  object  $forClass  The Discovery ForClass object.
     * @param  array   $document  The document array being built.
     *
     * @return array The document array with EmbedMany fields added.
     */
    private function resolveEmbedManyRelations(object $forClass, array $document): array
    {
        foreach ($forClass->classAttributes as $attr) {
            if (! ($attr instanceof EmbedMany)) {
                continue;
            }

            $query = $this->{$attr->field}();

            // Apply orderBy if specified (format: "field:direction")
            if ($attr->orderBy !== null) {
                $parts = explode(':', $attr->orderBy);
                $column = $parts[0];
                $direction = $parts[1] ?? 'asc';
                $query = $query->orderBy($column, $direction);
            }

            // Apply limit if specified
            if ($attr->limit !== null) {
                $query = $query->limit($attr->limit);
            }

            $collection = $query->get();

            $document[$attr->field] = $collection
                ->map(fn (object $model): array => $this->extractFieldsFromModel($model, $attr->fields))
                ->all();
        }

        return $document;
    }

    /**
     * Extract specific fields from a related model instance.
     *
     * If the fields array is empty, returns all model attributes.
     * Otherwise, returns only the specified fields.
     *
     * @param  object  $model   The related model instance.
     * @param  array   $fields  Field names to extract (empty = all).
     *
     * @return array The extracted field values.
     */
    private function extractFieldsFromModel(object $model, array $fields): array
    {
        if (empty($fields)) {
            return $model->toArray();
        }

        $result = [];

        foreach ($fields as $field) {
            $result[$field] = $model->getAttribute($field);
        }

        return $result;
    }
}
