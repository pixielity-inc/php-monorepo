<?php

declare(strict_types=1);

/**
 * Sample Data Generated Event.
 *
 * Dispatched when sample data generation completes for an entity.
 * Carries the entity key, optional tenant ID, and the number of
 * records created. This event is NOT broadcast — it is a standard
 * domain event for internal listeners only.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Services\SampleDataGenerator
 */

namespace Pixielity\ImportExport\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Sample Data Generated Event.
 *
 * Fired when sample data generation completes for an entity.
 * Does not implement ShouldBroadcast — this is an internal
 * domain event only.
 *
 * Usage:
 *   event(new SampleDataGenerated(
 *       entityKey: 'users',
 *       tenantId: 'tenant-uuid',
 *       recordCount: 10,
 *   ));
 */
#[AsEvent(description: 'Fired when sample data generation completes for an entity.')]
final readonly class SampleDataGenerated
{
    /**
     * Create a new SampleDataGenerated event instance.
     *
     * @param  string          $entityKey    The entity key for which sample data was generated.
     * @param  int|string|null $tenantId     The tenant ID if generated within a tenant context, or null.
     * @param  int             $recordCount  The number of sample records created.
     */
    public function __construct(
        /**
         * @var string The entity key for which sample data was generated.
         */
        public string $entityKey,

        /**
         * @var int|string|null The tenant ID if generated within a tenant context.
         */
        public int|string|null $tenantId,

        /**
         * @var int The number of sample records created.
         */
        public int $recordCount,
    ) {
    }
}
