<?php

declare(strict_types=1);

/**
 * App Repository Interface.
 *
 * Defines the contract for the AppRepository with query operations.
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
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Enums\AppStatus;
use Pixielity\Developer\Repositories\AppRepository;

/**
 * Contract for the AppRepository.
 */
#[Bind(AppRepository::class)]
#[Singleton]
interface AppRepositoryInterface extends RepositoryInterface
{
    /**
     * Find an app by its slug.
     *
     * @param  string  $slug  The unique slug identifier.
     * @return AppInterface|null The matching app or null.
     */
    public function findBySlug(string $slug): ?AppInterface;

    /**
     * Find all apps belonging to a developer.
     *
     * @param  int|string  $developerId  The developer identifier.
     * @return Collection<int, AppInterface>
     */
    public function findByDeveloper(int|string $developerId): Collection;

    /**
     * Find all apps with a given status.
     *
     * @param  AppStatus  $status  The app status to filter by.
     * @return Collection<int, AppInterface>
     */
    public function findByStatus(AppStatus $status): Collection;

    /**
     * Find all published apps.
     *
     * @return Collection<int, AppInterface>
     */
    public function findPublished(): Collection;
}
