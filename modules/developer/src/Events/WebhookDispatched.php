<?php

declare(strict_types=1);

/**
 * Webhook Dispatched Event.
 *
 * Dispatched after a webhook payload has been delivered (or attempted)
 * to a third-party app endpoint. Contains the delivery outcome including
 * the HTTP status code and success flag. Downstream listeners can use
 * this event for delivery tracking, analytics, or alerting on failures.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Jobs\WebhookDispatchJob
 */

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched after a webhook delivery attempt completes.
 */
#[AsEvent(description: 'Fired after a webhook payload delivery attempt completes')]
final readonly class WebhookDispatched
{
    /**
     * Create a new WebhookDispatched event instance.
     *
     * @param  int|string  $appId       The ID of the application that owns the webhook.
     * @param  string      $event       The event name that was delivered (e.g. 'order.created').
     * @param  string      $url         The endpoint URL the payload was delivered to.
     * @param  int         $statusCode  The HTTP response status code from the endpoint.
     * @param  bool        $success     Whether the delivery was successful (2xx response).
     */
    public function __construct(
        /** 
 * @var int|string The ID of the application that owns the webhook. 
 */
        public int|string $appId,
        /** 
 * @var string The event name that was delivered. 
 */
        public string $event,
        /** 
 * @var string The endpoint URL the payload was delivered to. 
 */
        public string $url,
        /** 
 * @var int The HTTP response status code from the endpoint. 
 */
        public int $statusCode,
        /** 
 * @var bool Whether the delivery was successful. 
 */
        public bool $success,
    ) {}
}
