<?php

declare(strict_types=1);

/**
 * Note Service Interface.
 *
 * Defines the contract for managing admin-only internal notes on
 * marketplace applications. These notes are invisible to developers
 * and tenants, used for internal tracking and communication.
 *
 * Bound to {@see \Pixielity\Developer\Services\NoteService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\NoteService
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Support\Collection;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\InternalNote;

/**
 * Contract for the Note service.
 *
 * Provides methods for creating and listing internal notes.
 * Implementations must restrict access to admin users only.
 */
#[Bind('Pixielity\\Developer\\Services\\NoteService')]
interface NoteServiceInterface
{
    /**
     * Create an internal note on an app.
     *
     * Creates an admin-only note attached to the specified app. Internal
     * notes are not visible to developers or tenants and are used for
     * internal tracking, enforcement context, or admin communication.
     *
     * @param  int|string  $appId    The ID of the application to attach the note to.
     * @param  int|string  $adminId  The ID of the admin creating the note.
     * @param  string      $body     The note body text.
     * @return InternalNote The created internal note record.
     */
    public function create(int|string $appId, int|string $adminId, string $body): InternalNote;

    /**
     * Get all internal notes for an app.
     *
     * Returns all internal notes for the specified app, ordered by
     * creation date. Only accessible to admin users.
     *
     * @param  int|string  $appId  The ID of the application to retrieve notes for.
     * @return Collection The collection of InternalNote records for the app.
     */
    public function getNotesForApp(int|string $appId): Collection;
}
