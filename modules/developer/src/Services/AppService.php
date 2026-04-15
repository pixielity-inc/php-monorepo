<?php

declare(strict_types=1);

/**
 * App Service.
 *
 * Manages the full lifecycle of developer applications in the marketplace.
 * Handles CRUD operations with OAuth credential generation, status
 * transitions (publish, suspend), and domain event dispatching.
 *
 * Delegates all data access to the AppRepository resolved via the
 * #[UseRepository] attribute. Extends the base Service class for
 * standard CRUD operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\AppServiceInterface
 * @see \Pixielity\Developer\Models\App
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\AppServiceInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Enums\AppStatus;
use Pixielity\Developer\Events\AppPublished;
use Pixielity\Developer\Events\AppSuspended;
use Pixielity\Developer\Models\App;

/**
 * Service for managing developer applications.
 *
 * Provides CRUD operations for marketplace apps with automatic
 * OAuth credential generation, status lifecycle management, and
 * domain event dispatching for cross-context integration.
 * All data access is delegated to the repository layer.
 */
#[Scoped]
#[UseRepository(AppRepositoryInterface::class)]
class AppService extends Service implements AppServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * Generates a UUID-based client_id and a 64-character random client_secret.
     * Sets the initial status to DRAFT so the app is not visible until published.
     */
    public function create(array $data): App
    {
        $data[AppInterface::ATTR_CLIENT_ID] = (string) Str::uuid();
        $data[AppInterface::ATTR_CLIENT_SECRET] = Str::random(64);
        $data[AppInterface::ATTR_STATUS] = AppStatus::DRAFT->value;

        /** @var App $app */
        $app = $this->repository->create($data);

        return $app;
    }

    /**
     * {@inheritDoc}
     */
    public function update(int|string $id, array $data): App
    {
        /** @var App $app */
        $app = $this->repository->update($id, $data);

        return $app;
    }

    /**
     * {@inheritDoc}
     *
     * Uses the repository's delete method which respects SoftDeletes if the
     * trait is applied on the App model.
     */
    public function delete(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * {@inheritDoc}
     *
     * @return App The application instance with loaded relationships.
     */
    public function findOrFail(int|string $id, array $columns = ['*']): App
    {
        /** @var App $app */
        $app = $this->repository->findOrFail($id, $columns);

        return $app;
    }

    /**
     * {@inheritDoc}
     */
    public function paginate(?int $perPage = null, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }

    /**
     * {@inheritDoc}
     *
     * Transitions the app from any status to PUBLISHED and dispatches
     * the AppPublished domain event for downstream listeners.
     */
    public function publish(int|string $id): App
    {
        /** @var App $app */
        $app = $this->repository->update($id, [
            AppInterface::ATTR_STATUS => AppStatus::PUBLISHED->value,
        ]);

        event(new AppPublished(
            appId: $app->getKey(),
            publishedBy: \Illuminate\Support\Facades\Auth::id(),
        ));

        return $app;
    }

    /**
     * {@inheritDoc}
     *
     * Transitions the app to SUSPENDED status and dispatches the
     * AppSuspended domain event. Existing installations remain active
     * but no new installations are permitted.
     */
    public function suspend(int|string $id): App
    {
        /** @var App $app */
        $app = $this->repository->update($id, [
            AppInterface::ATTR_STATUS => AppStatus::SUSPENDED->value,
        ]);

        event(new AppSuspended(
            appId: $app->getKey(),
            suspendedBy: \Illuminate\Support\Facades\Auth::id(),
            reason: 'Suspended via admin action',
        ));

        return $app;
    }
}
