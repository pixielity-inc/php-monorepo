<?php

declare(strict_types=1);

/**
 * Support Thread Controller.
 *
 * Manages private support conversations between developers and tenants.
 * Provides endpoints for opening threads, adding messages, updating
 * status, and listing threads for an app.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\SupportThreadServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\SupportThreadServiceInterface;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for support threads.
 *
 * Endpoints:
 *   GET  /api/marketplace/apps/{appId}/support     — List support threads
 *   POST /api/marketplace/apps/{appId}/support     — Open a support thread
 *   POST /api/marketplace/support/{id}/messages    — Add a message
 *   PUT  /api/marketplace/support/{id}/status      — Update thread status
 */
#[AsController]
class SupportThreadController extends Controller
{
    /**
     * Create a new SupportThreadController instance.
     *
     * @param  SupportThreadServiceInterface  $supportThreadService  The support thread service.
     */
    public function __construct(
        private readonly SupportThreadServiceInterface $supportThreadService,
    ) {}

    /**
     * List all support threads for an app.
     *
     * Returns all support threads for the specified app, ordered by
     * most recent activity. Includes thread metadata but not full
     * message history.
     *
     * @param  int|string  $appId  The app ID.
     * @return mixed The collection of support thread records.
     */
    public function index(int|string $appId): mixed
    {
        $threads = $this->supportThreadService->getThreadsForApp($appId);

        return $this->ok($threads);
    }

    /**
     * Open a new support thread.
     *
     * Creates a support thread for the specified app with an initial
     * message. The thread is created in OPEN status. Only tenants
     * with the app installed may open support threads.
     *
     * @param  Request     $request  The HTTP request containing subject and message.
     * @param  int|string  $appId    The app ID.
     * @return mixed The created support thread record or an error response.
     */
    public function store(Request $request, int|string $appId): mixed
    {
        try {
            $tenantId = $request->input('tenant_id', $request->user()?->getAttribute('tenant_id'));
            $subject = $request->input('subject');
            $initialMessage = $request->input('message');

            $thread = $this->supportThreadService->open($appId, $tenantId, $subject, $initialMessage);

            return $this->created($thread);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Add a message to a support thread.
     *
     * Creates a new message in the specified thread. Only the thread's
     * tenant and the app's developer may add messages. Returns a 403
     * if the user is not a participant.
     *
     * @param  Request     $request  The HTTP request containing the message body.
     * @param  int|string  $id       The support thread ID.
     * @return mixed The created support message record or an error response.
     */
    public function addMessage(Request $request, int|string $id): mixed
    {
        try {
            $authorId = $request->user()?->getKey();
            $authorType = $request->input('author_type', 'tenant');
            $body = $request->input('body');

            $message = $this->supportThreadService->addMessage($id, $authorId, $authorType, $body);

            return $this->created($message);
        } catch (\RuntimeException $e) {
            return $this->forbidden($e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Update the status of a support thread.
     *
     * Transitions the thread to the specified status (open, resolved,
     * or closed). Status transitions may be restricted based on the
     * current status and the user's role.
     *
     * @param  Request     $request  The HTTP request containing the new status.
     * @param  int|string  $id       The support thread ID.
     * @return mixed The updated support thread record or an error response.
     */
    public function updateStatus(Request $request, int|string $id): mixed
    {
        try {
            $status = $request->input('status');

            $thread = $this->supportThreadService->updateStatus($id, $status);

            return $this->ok($thread);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }
}
