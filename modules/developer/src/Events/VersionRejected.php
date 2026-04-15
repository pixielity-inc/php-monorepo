<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app version is rejected by a reviewer.
 *
 * This event signals that a version submission has failed review and the
 * developer must address the rejection reasons before resubmitting.
 * Downstream listeners can notify the developer with specific feedback.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\VersionReviewService::reject()
 */
#[AsEvent(description: 'Fired when an app version is rejected by a reviewer')]
final readonly class VersionRejected
{
    /**
     * Create a new VersionRejected event instance.
     *
     * @param  int|string          $appId      The ID of the application the version belongs to.
     * @param  int|string          $versionId  The ID of the rejected version record.
     * @param  string              $version    The semantic version string (e.g. "1.2.3").
     * @param  array<int, string>  $reasons    The list of rejection reasons provided by the reviewer.
     */
    public function __construct(
        public int|string $appId,
        public int|string $versionId,
        public string $version,
        public array $reasons,
    ) {}
}
