<?php

declare(strict_types=1);

/**
 * Webhook Service.
 *
 * Manages webhook dispatch and subscription registration for developer
 * applications. When an event occurs, this service finds all active
 * webhook subscriptions for the app and event, then dispatches a queued
 * WebhookDispatchJob for each endpoint. Each delivery includes an
 * HMAC-SHA256 signature for payload integrity verification.
 *
 * Delegates all data access to the AppWebhookRepository resolved via
 * the #[UseRepository] attribute. Extends the base Service class for
 * standard CRUD operations. Timeout and retry settings are injected
 * from the developer config via #[Config].
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\WebhookServiceInterface
 * @see \Pixielity\Developer\Jobs\WebhookDispatchJob
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Config;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Str;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\AppWebhookRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppWebhookInterface;
use Pixielity\Developer\Contracts\WebhookServiceInterface;
use Pixielity\Developer\Jobs\WebhookDispatchJob;
use Pixielity\Developer\Models\AppWebhook;

/**
 * Service for dispatching webhooks and managing subscriptions.
 *
 * Finds active webhook subscriptions for a given app and event,
 * dispatches queued delivery jobs with HMAC signatures, and
 * provides subscription management for webhook registration.
 * All data access is delegated to the repository layer.
 */
#[Scoped]
#[UseRepository(AppWebhookRepositoryInterface::class)]
class WebhookService extends Service implements WebhookServiceInterface
{
    /**
     * The HTTP timeout in seconds for webhook delivery requests.
     *
     * @var int
     */
    private readonly int $timeout;

    /**
     * The number of times to retry a failed webhook delivery.
     *
     * @var int
     */
    private readonly int $retryTimes;

    /**
     * The delay in seconds between webhook delivery retry attempts.
     *
     * @var int
     */
    private readonly int $retryDelay;

    /**
     * The queue name for webhook dispatch jobs, or null for synchronous dispatch.
     *
     * @var string|null
     */
    private readonly ?string $queue;

    /**
     * Create a new WebhookService instance.
     *
     * Injects webhook configuration values from the developer config
     * file via the #[Config] attribute for automatic resolution.
     * Calls parent::__construct() to resolve the repository via
     * the #[UseRepository] attribute.
     *
     * @param  int          $timeout     The HTTP timeout in seconds.
     * @param  int          $retryTimes  The number of retry attempts.
     * @param  int          $retryDelay  The delay between retries in seconds.
     * @param  string|null  $queue       The queue name, or null for sync dispatch.
     */
    public function __construct(
        #[Config('developer.webhook.timeout', 10)]
        int $timeout = 10,
        #[Config('developer.webhook.retry_times', 3)]
        int $retryTimes = 3,
        #[Config('developer.webhook.retry_delay', 5)]
        int $retryDelay = 5,
        #[Config('developer.webhook.queue')]
        ?string $queue = null,
    ) {
        $this->timeout = $timeout;
        $this->retryTimes = $retryTimes;
        $this->retryDelay = $retryDelay;
        $this->queue = $queue;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     *
     * Queries all active webhook subscriptions for the given app and event,
     * then dispatches a WebhookDispatchJob for each matching endpoint.
     * Jobs are placed on the configured queue with the service's retry
     * and timeout settings.
     */
    public function dispatch(int|string $appId, string $event, array $payload): void
    {
        $webhooks = $this->repository->findWhere([
            AppWebhookInterface::ATTR_APP_ID => $appId,
            AppWebhookInterface::ATTR_EVENT => $event,
            AppWebhookInterface::ATTR_IS_ACTIVE => true,
        ]);

        foreach ($webhooks as $webhook) {
            $job = new WebhookDispatchJob(
                webhookId: $webhook->getKey(),
                url: $webhook->getAttribute(AppWebhookInterface::ATTR_URL),
                secret: $webhook->getAttribute(AppWebhookInterface::ATTR_SECRET),
                event: $event,
                payload: $payload,
                timeout: $this->timeout,
                retryTimes: $this->retryTimes,
            );

            if ($this->queue) {
                $job->onQueue($this->queue);
            }

            dispatch($job)->afterResponse();
        }
    }

    /**
     * {@inheritDoc}
     *
     * Generates a 64-character random secret for HMAC-SHA256 payload signing.
     * The webhook is created in an active state and will immediately begin
     * receiving deliveries for the subscribed event.
     */
    public function registerWebhook(int|string $appId, string $event, string $url): AppWebhook
    {
        /** @var AppWebhook $webhook */
        $webhook = $this->repository->create([
            AppWebhookInterface::ATTR_APP_ID => $appId,
            AppWebhookInterface::ATTR_EVENT => $event,
            AppWebhookInterface::ATTR_URL => $url,
            AppWebhookInterface::ATTR_SECRET => Str::random(64),
            AppWebhookInterface::ATTR_IS_ACTIVE => true,
        ]);

        return $webhook;
    }
}
