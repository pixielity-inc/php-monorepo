<?php

declare(strict_types=1);

/**
 * AppCategory Repository.
 *
 * All query logic for the AppCategory model. Uses `$this->query()` for reads
 * and `$this->modelInstance->newQuery()` for writes.
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Repositories;

use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\AppCategoryRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppCategoryInterface;

/**
 * Repository for the AppCategory model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to AppCategoryInterface (resolved to AppCategory model)
 *   - #[OrderBy]          → default ordering by sort_order asc
 */
#[AsRepository]
#[UseModel(AppCategoryInterface::class)]
#[OrderBy(column: AppCategoryInterface::ATTR_SORT_ORDER, direction: 'asc')]
class AppCategoryRepository extends Repository implements AppCategoryRepositoryInterface
{
    /**
     * Find a category by its slug.
     *
     * @param  string  $slug  The unique slug identifier.
     * @return AppCategoryInterface|null The matching category or null.
     */
    public function findBySlug(string $slug): ?AppCategoryInterface
    {
        return $this->query()
            ->where(AppCategoryInterface::ATTR_SLUG, $slug)
            ->first();
    }
}
