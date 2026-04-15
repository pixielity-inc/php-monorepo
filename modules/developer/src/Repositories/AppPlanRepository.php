<?php

declare(strict_types=1);

/**
 * AppPlan Repository.
 *
 * All query logic for the AppPlan model. Uses `$this->query()` for reads
 * and `$this->modelInstance->newQuery()` for writes.
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Repositories;

use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\AppPlanRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppPlanInterface;

/**
 * Repository for the AppPlan model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to AppPlanInterface (resolved to AppPlan model)
 *   - #[OrderBy]          → default ordering by sort_order asc
 */
#[AsRepository]
#[UseModel(AppPlanInterface::class)]
#[OrderBy(column: AppPlanInterface::ATTR_SORT_ORDER, direction: 'asc')]
class AppPlanRepository extends Repository implements AppPlanRepositoryInterface
{
    /**
     * Find all plans for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppPlanInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all active plans for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findActivePlans(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppPlanInterface::ATTR_APP_ID, $appId)
            ->where(AppPlanInterface::ATTR_IS_ACTIVE, true)
            ->get();
    }
}
