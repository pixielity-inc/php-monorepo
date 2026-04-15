<?php

declare(strict_types=1);

/**
 * App Repository.
 *
 * All query logic for the App model. Uses `$this->query()` for reads
 * and `$this->modelInstance->newQuery()` for writes.
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Repositories;

use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\Filterable;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\Sortable;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Attributes\WithRelations;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Enums\AppStatus;

/**
 * Repository for the App model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to AppInterface (resolved to App model)
 *   - #[WithRelations]    → eager loads categories on every query
 *   - #[OrderBy]          → default ordering by created_at desc
 *   - #[Filterable]       → request-based filtering on name, slug, status, developer_id
 *   - #[Sortable]         → request-based sorting on name, status, rating, install_count, created_at
 */
#[AsRepository]
#[UseModel(AppInterface::class)]
#[WithRelations(AppInterface::REL_CATEGORIES)]
#[OrderBy(column: 'created_at', direction: 'desc')]
#[Filterable([
    AppInterface::ATTR_NAME => ['$eq', '$contains', '$startsWith'],
    AppInterface::ATTR_SLUG => ['$eq', '$contains'],
    AppInterface::ATTR_STATUS => ['$eq', '$in', '$ne'],
    AppInterface::ATTR_DEVELOPER_ID => ['$eq'],
])]
#[Sortable([
    AppInterface::ATTR_NAME,
    AppInterface::ATTR_STATUS,
    AppInterface::ATTR_RATING,
    AppInterface::ATTR_INSTALL_COUNT,
    'created_at',
])]
class AppRepository extends Repository implements AppRepositoryInterface
{
    /**
     * Find an app by its slug.
     *
     * @param  string  $slug  The unique slug identifier.
     * @return AppInterface|null The matching app or null.
     */
    public function findBySlug(string $slug): ?AppInterface
    {
        return $this->query()
            ->where(AppInterface::ATTR_SLUG, $slug)
            ->first();
    }

    /**
     * Find all apps belonging to a developer.
     *
     * @param  int|string  $developerId  The developer identifier.
     * @return Collection<int, AppInterface>
     */
    public function findByDeveloper(int|string $developerId): Collection
    {
        return $this->query()
            ->where(AppInterface::ATTR_DEVELOPER_ID, $developerId)
            ->get();
    }

    /**
     * Find all apps with a given status.
     *
     * @param  AppStatus  $status  The app status to filter by.
     * @return Collection<int, AppInterface>
     */
    public function findByStatus(AppStatus $status): Collection
    {
        return $this->query()
            ->where(AppInterface::ATTR_STATUS, $status->value)
            ->get();
    }

    /**
     * Find all published apps.
     *
     * @return Collection<int, AppInterface>
     */
    public function findPublished(): Collection
    {
        return $this->query()
            ->where(AppInterface::ATTR_STATUS, AppStatus::APPROVED->value)
            ->get();
    }
}
