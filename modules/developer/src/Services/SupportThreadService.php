<?php

declare(strict_types=1);

/**
 * Support Thread Service.
 *
 * Manages private support conversations between developers and tenants.
 * Handles thread creation with initial messages, message exchange with
 * participation validation, status transitions, and thread listing.
 *
 * Delegates all data access to the repository layer. Injects both
 * SupportThreadRepository and SupportMessageRepository via constructor
 * since this service operates across multiple models without a single
 * primary.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\SupportThreadServiceInterface
 * @see \Pixielity\Developer\Models\SupportThread
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\Developer\Contracts\Data\SupportMessageInterface;
use Pixielity\Developer\Contracts\Data\SupportThreadInterface;
use Pixielity\Developer\Contracts\SupportMessageRepositoryInterface;
use Pixielity\Developer\Contracts\SupportThreadRepositoryInterface;
use Pixielity\Developer\Contracts\SupportThreadServiceInterface;
use Pixielity\Developer\Enums\AuthorType;
use Pixielity\Developer\Enums\SupportThreadStatus;
use Pixielity\Developer\Events\SupportMessageReceived;
use Pixielity\Developer\Models\SupportMessage;
use Pixielity\Developer\Models\SupportThread;

/**
 * Service for managing private support threads between developers and tenants.
 *
 * Creates threads with initial messages, validates participant eligibility,
 * manages thread status transitions, and dispatches SupportMessageReceived
 * events for downstream processing. All data access is delegated to the
 * repository layer.
 */
#[Scoped]
class SupportThreadService implements SupportThreadServiceInterface
{
    /**
     * Create a new SupportThreadService instance.
     *
     * @param  SupportThreadRepositoryInterface   $supportThreadRepository   The support thread repository.
     * @param  SupportMessageRepositoryInterface  $supportMessageRepository  The support message repository.
     */
    public function __construct(
        private readonly SupportThreadRepositoryInterface $supportThreadRepository,
        private readonly SupportMessageRepositoryInterface $supportMessageRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Creates a SupportThread record in OPEN status with the specified
     * app, tenant, and subject. Also creates the initial SupportMessage
     * from the tenant. Returns the created thread.
     */
    public function open(
        int|string $appId,
        int|string $tenantId,
        string $subject,
        string $initialMessage,
    ): SupportThread {
        /** @var SupportThread $thread */
        $thread = $this->supportThreadRepository->create([
            SupportThreadInterface::ATTR_APP_ID => $appId,
            SupportThreadInterface::ATTR_TENANT_ID => $tenantId,
            SupportThreadInterface::ATTR_SUBJECT => $subject,
            SupportThreadInterface::ATTR_STATUS => SupportThreadStatus::OPEN->value,
        ]);

        $this->supportMessageRepository->create([
            SupportMessageInterface::ATTR_SUPPORT_THREAD_ID => $thread->getKey(),
            SupportMessageInterface::ATTR_AUTHOR_ID => $tenantId,
            SupportMessageInterface::ATTR_AUTHOR_TYPE => AuthorType::TENANT->value,
            SupportMessageInterface::ATTR_BODY => $initialMessage,
        ]);

        return $thread;
    }

    /**
     * {@inheritDoc}
     *
     * Validates the author type is either TENANT or DEVELOPER (restricting
     * thread participation), creates a SupportMessage record, and dispatches
     * a SupportMessageReceived event.
     *
     * @throws \InvalidArgumentException If the author type is not tenant or developer.
     */
    public function addMessage(
        int|string $threadId,
        int|string $authorId,
        string $authorType,
        string $body,
    ): SupportMessage {
        if (! in_array($authorType, [AuthorType::TENANT->value, AuthorType::DEVELOPER->value], true)) {
            throw new \InvalidArgumentException(
                "Only tenants and developers may participate in support threads. Got [{$authorType}]."
            );
        }

        $this->supportThreadRepository->findOrFail($threadId);

        /** @var SupportMessage $message */
        $message = $this->supportMessageRepository->create([
            SupportMessageInterface::ATTR_SUPPORT_THREAD_ID => $threadId,
            SupportMessageInterface::ATTR_AUTHOR_ID => $authorId,
            SupportMessageInterface::ATTR_AUTHOR_TYPE => $authorType,
            SupportMessageInterface::ATTR_BODY => $body,
        ]);

        event(new SupportMessageReceived(
            threadId: $threadId,
            authorId: $authorId,
        ));

        return $message;
    }

    /**
     * {@inheritDoc}
     *
     * Transitions the thread to the specified status. Validates the
     * status string corresponds to a valid SupportThreadStatus enum
     * value before applying the update.
     *
     * @throws \ValueError If the status string is not a valid SupportThreadStatus value.
     */
    public function updateStatus(int|string $threadId, string $status): SupportThread
    {
        $threadStatus = SupportThreadStatus::from($status);

        /** @var SupportThread $thread */
        $thread = $this->supportThreadRepository->update($threadId, [
            SupportThreadInterface::ATTR_STATUS => $threadStatus->value,
        ]);

        return $thread;
    }

    /**
     * {@inheritDoc}
     *
     * Returns all support threads for the specified app, ordered by
     * most recent update. Includes thread metadata but not the full
     * message history.
     */
    public function getThreadsForApp(int|string $appId): Collection
    {
        return $this->supportThreadRepository->findByApp($appId);
    }
}
