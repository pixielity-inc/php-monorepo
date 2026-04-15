<?php

declare(strict_types=1);

/**
 * AppWebhook Repository.
 *
 * All query logic for the AppWebhook model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\AppWebhookRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppWebhookInterface;

/**
 * Repository for the AppWebhook model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to AppWebhookInterface (resolved to AppWebhook model)
 *   - #[OrderBy]          → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(AppWebhookInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class AppWebhookRepository extends Repository implements AppWebhookRepositoryInterface
{
    /**
     * Find all webhooks for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppWebhookInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all active webhooks for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findActiveByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppWebhookInterface::ATTR_APP_ID, $appId)
            ->where(AppWebhookInterface::ATTR_IS_ACTIVE, true)
            ->get();
    }
}
