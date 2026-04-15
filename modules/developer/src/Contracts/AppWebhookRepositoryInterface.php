<?php

declare(strict_types=1);

/**
 * AppWebhook Repository Interface.
 *
 * Defines the contract for the AppWebhookRepository with query operations.
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
use Pixielity\Developer\Repositories\AppWebhookRepository;

/**
 * Contract for the AppWebhookRepository.
 */
#[Bind(AppWebhookRepository::class)]
#[Singleton]
interface AppWebhookRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all webhooks for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all active webhooks for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findActiveByApp(int|string $appId): Collection;
}
