<?php

declare(strict_types=1);

/**
 * Support Thread Service Interface.
 *
 * Defines the contract for managing private support conversations between
 * developers and tenants. Covers thread creation, message exchange, status
 * management, and thread listing.
 *
 * Bound to {@see \Pixielity\Developer\Services\SupportThreadService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\SupportThreadService
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Support\Collection;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\SupportMessage;
use Pixielity\Developer\Models\SupportThread;

/**
 * Contract for the Support Thread service.
 *
 * Provides methods for opening threads, adding messages, updating status,
 * and listing threads. Implementations must enforce participation
 * restrictions and dispatch SupportMessageReceived events.
 */
#[Bind('Pixielity\\Developer\\Services\\SupportThreadService')]
interface SupportThreadServiceInterface
{
    /**
     * Open a new support thread.
     *
     * Creates a support thread for the specified app and tenant with an
     * initial message. The thread is created in OPEN status. Only tenants
     * with the app installed may open support threads.
     *
     * @param  int|string  $appId           The ID of the application the thread is about.
     * @param  int|string  $tenantId        The ID of the tenant opening the thread.
     * @param  string      $subject         The subject line for the support thread.
     * @param  string      $initialMessage  The body of the initial message in the thread.
     * @return SupportThread The created support thread record.
     */
    public function open(int|string $appId, int|string $tenantId, string $subject, string $initialMessage): SupportThread;

    /**
     * Add a message to a support thread.
     *
     * Creates a new message in the specified thread. Only the thread's
     * tenant and the app's developer may add messages. Dispatches a
     * SupportMessageReceived event.
     *
     * @param  int|string  $threadId    The ID of the support thread to add a message to.
     * @param  int|string  $authorId    The ID of the user sending the message.
     * @param  string      $authorType  The type of author (tenant or developer).
     * @param  string      $body        The message body text.
     * @return SupportMessage The created support message record.
     */
    public function addMessage(int|string $threadId, int|string $authorId, string $authorType, string $body): SupportMessage;

    /**
     * Update the status of a support thread.
     *
     * Transitions the thread to the specified status (open, resolved,
     * or closed). Status transitions may be restricted based on the
     * current status and the user's role.
     *
     * @param  int|string  $threadId  The ID of the support thread to update.
     * @param  string      $status    The new status for the thread (open, resolved, closed).
     * @return SupportThread The updated support thread record.
     */
    public function updateStatus(int|string $threadId, string $status): SupportThread;

    /**
     * Get all support threads for an app.
     *
     * Returns all support threads for the specified app, ordered by
     * most recent activity. Includes thread metadata but not full
     * message history.
     *
     * @param  int|string  $appId  The ID of the application to retrieve threads for.
     * @return Collection The collection of SupportThread records for the app.
     */
    public function getThreadsForApp(int|string $appId): Collection;
}
