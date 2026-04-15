<?php

declare(strict_types=1);

/**
 * Webhook Service Interface.
 *
 * Defines the contract for dispatching webhook payloads to third-party
 * app endpoints and managing webhook subscriptions. Webhook delivery
 * is performed asynchronously via queued jobs with HMAC signature
 * verification for payload integrity.
 *
 * Bound to {@see \Pixielity\Developer\Services\WebhookService} via
 * the #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\WebhookService
 * @see \Pixielity\Developer\Jobs\WebhookDispatchJob
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppWebhook;

/**
 * Contract for the Webhook dispatch service.
 *
 * Provides methods for dispatching event payloads to registered
 * webhook endpoints and managing webhook subscriptions for apps.
 */
#[Bind('Pixielity\\Developer\\Services\\WebhookService')]
interface WebhookServiceInterface
{
    /**
     * Dispatch a webhook event to all registered endpoints for an app.
     *
     * Finds all active webhook subscriptions matching the given app and
     * event, then dispatches a queued WebhookDispatchJob for each endpoint.
     * Each delivery includes an HMAC-SHA256 signature for verification.
     *
     * @param  int|string  $appId    The application ID whose webhooks to trigger.
     * @param  string      $event    The event name (e.g. 'order.created', 'app.installed').
     * @param  array<string, mixed>  $payload  The event payload to deliver.
     * @return void
     */
    public function dispatch(int|string $appId, string $event, array $payload): void;

    /**
     * Register a new webhook subscription for an app.
     *
     * Creates a webhook record with a generated HMAC secret for payload
     * signing. The webhook is created in an active state and will receive
     * deliveries for the specified event immediately.
     *
     * @param  int|string  $appId  The application ID to register the webhook for.
     * @param  string      $event  The event name to subscribe to.
     * @param  string      $url    The endpoint URL to deliver payloads to.
     * @return AppWebhook The created webhook subscription record.
     */
    public function registerWebhook(int|string $appId, string $event, string $url): AppWebhook;
}
