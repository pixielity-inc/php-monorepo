<?php

declare(strict_types=1);

/**
 * AppPlan Repository Interface.
 *
 * Defines the contract for the AppPlanRepository with query operations.
 * Bound via #[Bind] attribute for automatic container registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Support\Collection;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Developer\Repositories\AppPlanRepository;

/**
 * Contract for the AppPlanRepository.
 */
#[Bind(AppPlanRepository::class)]
#[Singleton]
interface AppPlanRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all plans for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all active plans for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findActivePlans(int|string $appId): Collection;
}
