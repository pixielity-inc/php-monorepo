<?php

declare(strict_types=1);

/**
 * ReadsQueueAttributes Trait.
 *
 * Reads queue configuration from PHP attributes and sets the corresponding
 * properties on the job class. Bridge between attribute-based config and
 * Laravel's property-based queue system.
 *
 * ## Usage:
 * ```php
 * #[OnQueue('emails')]
 * #[OnConnection('redis')]
 * #[WithTries(3)]
 * #[WithTimeout(120)]
 * #[WithBackoff(10, 30, 60)]
 * #[DeleteWhenMissingModels]
 * class SendWelcomeEmail implements ShouldQueue
 * {
 *     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 *     use ReadsQueueAttributes;
 *
 *     public function handle(): void { ... }
 * }
 * ```
 *
 * ## Supported Attributes:
 *   #[OnQueue('emails')]            → $this->queue
 *   #[OnConnection('redis')]        → $this->connection
 *   #[WithDelay(60)]                → $this->delay
 *   #[WithTries(3)]                 → $this->tries
 *   #[WithTimeout(120)]             → $this->timeout
 *   #[WithBackoff(10, 30)]          → $this->backoff
 *   #[UniqueFor(300)]               → $this->uniqueFor
 *   #[WithMaxExceptions(3)]         → $this->maxExceptions
 *   #[WithRetryUntil(3600)]         → $this->retryUntil (via method)
 *   #[DeleteWhenMissingModels]      → $this->deleteWhenMissingModels
 *   #[WithMiddleware(Class::class)] → middleware() method
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Concerns;

use Pixielity\Discovery\Facades\Discovery;
use Pixielity\Queue\Attributes\DeleteWhenMissingModels;
use Pixielity\Queue\Attributes\OnConnection;
use Pixielity\Queue\Attributes\OnQueue;
use Pixielity\Queue\Attributes\UniqueFor;
use Pixielity\Queue\Attributes\WithBackoff;
use Pixielity\Queue\Attributes\WithDelay;
use Pixielity\Queue\Attributes\WithMaxExceptions;
use Pixielity\Queue\Attributes\WithMiddleware;
use Pixielity\Queue\Attributes\WithRetryUntil;
use Pixielity\Queue\Attributes\WithTimeout;
use Pixielity\Queue\Attributes\WithTries;

/**
 * Reads queue attributes and sets Laravel queue properties.
 */
trait ReadsQueueAttributes
{
    /**
     * Queue middleware resolved from #[WithMiddleware] attribute.
     *
     * @var array<int, class-string>
     */
    private array $attributeMiddleware = [];

    /**
     * Retry-until seconds from #[WithRetryUntil] attribute.
     */
    private ?int $retryUntilSeconds = null;

    /**
     * Initialize the ReadsQueueAttributes trait.
     *
     * Called automatically by Laravel's trait initialization system.
     */
    public function initializeReadsQueueAttributes(): void
    {
        $classAttributes = Discovery::forClass(static::class)->classAttributes;

        foreach ($classAttributes as $attr) {
            match (true) {
                $attr instanceof OnQueue => $this->queue = $attr->queue,
                $attr instanceof OnConnection => $this->connection = $attr->connection,
                $attr instanceof WithDelay => $this->delay = $attr->seconds,
                $attr instanceof WithTries => $this->tries = $attr->tries,
                $attr instanceof WithTimeout => $this->timeout = $attr->seconds,
                $attr instanceof WithBackoff => $this->backoff = $attr->seconds,
                $attr instanceof UniqueFor => $this->uniqueFor = $attr->seconds,
                $attr instanceof WithMaxExceptions => $this->maxExceptions = $attr->maxExceptions,
                $attr instanceof WithRetryUntil => $this->retryUntilSeconds = $attr->seconds,
                $attr instanceof DeleteWhenMissingModels => $this->deleteWhenMissingModels = true,
                $attr instanceof WithMiddleware => $this->attributeMiddleware = $attr->middleware,
                default => null,
            };
        }
    }

    /**
     * Get the middleware the job should pass through.
     *
     * Merges attribute-declared middleware with any manually declared middleware.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        $middleware = [];

        foreach ($this->attributeMiddleware as $middlewareClass) {
            $middleware[] = app($middlewareClass);
        }

        return $middleware;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * Used by Laravel's queue worker when #[WithRetryUntil] is set.
     */
    public function retryUntil(): ?\DateTime
    {
        if ($this->retryUntilSeconds === null) {
            return null;
        }

        return now()->addSeconds($this->retryUntilSeconds)->toDateTime();
    }
}
