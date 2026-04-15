<?php

declare(strict_types=1);

/**
 * Auth Event Listener.
 *
 * Subscribes to Laravel's built-in auth events and records them via the
 * AuditManager. Auto-discovered via #[Subscriber] + #[On] attributes.
 *
 * @category Listeners
 *
 * @since    1.0.0
 */

namespace Pixielity\Audit\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Pixielity\Audit\Contracts\AuditManagerInterface;
use Pixielity\Event\Attributes\On;
use Pixielity\Event\Attributes\Subscriber;

/**
 * Records auth events in the audit trail.
 */
#[Subscriber]
class AuthEventListener
{
    public function __construct(
        private readonly AuditManagerInterface $auditManager,
    ) {}

    /**
     * Handle user login event.
     */
    #[On(Login::class)]
    public function handleLogin(Login $event): void
    {
        $this->auditManager->log('auth.login', $event->user, [
            'guard' => $event->guard,
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'tags' => 'auth',
        ]);
    }

    /**
     * Handle user logout event.
     */
    #[On(Logout::class)]
    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            $this->auditManager->log('auth.logout', $event->user, [
                'guard' => $event->guard,
                'ip' => request()?->ip(),
                'tags' => 'auth',
            ]);
        }
    }

    /**
     * Handle failed login attempt.
     */
    #[On(Failed::class)]
    public function handleFailed(Failed $event): void
    {
        $this->auditManager->log('auth.failed', null, [
            'guard' => $event->guard,
            'credentials' => ['email' => $event->credentials['email'] ?? 'unknown'],
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'tags' => 'auth',
        ]);
    }

    /**
     * Handle account lockout.
     */
    #[On(Lockout::class)]
    public function handleLockout(Lockout $event): void
    {
        $this->auditManager->log('auth.lockout', null, [
            'ip' => $event->request->ip(),
            'email' => $event->request->input('email'),
            'tags' => 'auth',
        ]);
    }
}
