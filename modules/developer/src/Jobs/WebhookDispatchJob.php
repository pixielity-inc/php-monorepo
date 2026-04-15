<?php

declare(strict_types=1);

/**
 * Webhook Dispatch Job.
 *
 * Queued job that delivers a webhook payload to a third-party app endpoint
 * via HTTP POST. Each delivery includes an HMAC-SHA256 signature in the
 * X-Webhook-Signature header for payload integrity verification. The job
 * supports configurable retry logic and timeout settings.
 *
 * Dispatched by the WebhookService when an event occurs that matches
 * an active webhook subscription.
 *
 * @category Jobs
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\WebhookService
 * @see \Pixielity\Developer\Events\WebhookDispatched
 */

namespace Pixielity\Developer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Pixielity\Developer\Events\WebhookDispatched;

/**
 * Queued job for delivering webhook payloads to app endpoints.
 *
 * Makes an HTTP POST request to the webhook URL with the event payload
 * as JSON. Signs the payload with HMAC-SHA256 using the webhook secret
 * and includes the signature in the request headers. Logs the delivery
 * outcome and dispatches a WebhookDispatched event.
 *
 * Usage:
 *   ```php
 *   WebhookDispatchJob::dispatch(
 *       webhookId: 1,
 *       url: 'https://example.com/webhook',
 *       secret: 'webhook-secret-key',
 *       event: 'order.created',
 *       payload: ['order_id' => 42],
 *   );
 *   ```
 */
class WebhookDispatchJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $backoff;

    /**
     * Create a new WebhookDispatchJob instance.
     *
     * @param  int|string  $webhookId   The webhook subscription record ID.
     * @param  string      $url         The endpoint URL to deliver the payload to.
     * @param  string      $secret      The HMAC secret for signing the payload.
     * @param  string      $event       The event name being delivered.
     * @param  array<string, mixed>  $payload  The event payload data.
     * @param  int         $timeout     The HTTP request timeout in seconds.
     * @param  int         $retryTimes  The maximum number of delivery attempts.
     */
    public function __construct(
        /** 
 * @var int|string The webhook subscription record ID. 
 */
        public readonly int|string $webhookId,
        /** 
 * @var string The endpoint URL to deliver the payload to. 
 */
        public readonly string $url,
        /** 
 * @var string The HMAC secret for signing the payload. 
 */
        public readonly string $secret,
        /** 
 * @var string The event name being delivered. 
 */
        public readonly string $event,
        /** 
 * @var array<string, mixed> The event payload data. 
 */
        public readonly array $payload,
        /** 
 * @var int The HTTP request timeout in seconds. 
 */
        public readonly int $timeout = 10,
        /** 
 * @var int The maximum number of delivery attempts. 
 */
        int $retryTimes = 3,
    ) {
        $this->tries = $retryTimes;
        $this->backoff = (int) config('developer.webhook.retry_delay', 5);
    }

    /**
     * Execute the webhook delivery job.
     *
     * Encodes the payload as JSON, generates an HMAC-SHA256 signature,
     * and sends an HTTP POST request to the webhook URL. Logs the
     * delivery outcome and dispatches a WebhookDispatched event with
     * the response status code and success flag.
     *
     * @return void
     */
    public function handle(): void
    {
        $jsonPayload = json_encode($this->payload, JSON_THROW_ON_ERROR);
        $signature = hash_hmac('sha256', $jsonPayload, $this->secret);

        $verifySsl = (bool) config('developer.webhook.verify_ssl', true);

        $response = Http::timeout($this->timeout)
            ->withOptions(['verify' => $verifySsl])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'X-Webhook-Event' => $this->event,
                'X-Webhook-Signature' => $signature,
                'X-Webhook-Id' => (string) $this->webhookId,
                'User-Agent' => 'Pixielity-Webhook/1.0',
            ])
            ->post($this->url, $this->payload);

        $statusCode = $response->status();
        $success = $response->successful();

        if ($success) {
            Log::info('Webhook delivered successfully', [
                'webhook_id' => $this->webhookId,
                'event' => $this->event,
                'url' => $this->url,
                'status_code' => $statusCode,
            ]);
        } else {
            Log::warning('Webhook delivery failed', [
                'webhook_id' => $this->webhookId,
                'event' => $this->event,
                'url' => $this->url,
                'status_code' => $statusCode,
                'response_body' => $response->body(),
            ]);
        }

        event(new WebhookDispatched(
            appId: $this->payload['app_id'] ?? $this->webhookId,
            event: $this->event,
            url: $this->url,
            statusCode: $statusCode,
            success: $success,
        ));
    }

    /**
     * Handle a job failure.
     *
     * Logs the exception details when all retry attempts have been
     * exhausted. Includes the webhook ID, event, URL, and exception
     * message for debugging failed deliveries.
     *
     * @param  \Throwable  $exception  The exception that caused the failure.
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Webhook delivery permanently failed', [
            'webhook_id' => $this->webhookId,
            'event' => $this->event,
            'url' => $this->url,
            'exception' => $exception->getMessage(),
        ]);
    }
}
