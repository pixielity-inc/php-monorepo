<?php

declare(strict_types=1);

/**
 * Note Service.
 *
 * Manages admin-only internal notes on marketplace applications. These
 * notes are invisible to developers and tenants, used for internal
 * tracking, enforcement context, and admin communication.
 *
 * Delegates all data access to the InternalNoteRepository resolved via
 * the #[UseRepository] attribute. Extends the base Service class for
 * standard CRUD operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\NoteServiceInterface
 * @see \Pixielity\Developer\Models\InternalNote
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\Data\InternalNoteInterface;
use Pixielity\Developer\Contracts\InternalNoteRepositoryInterface;
use Pixielity\Developer\Contracts\NoteServiceInterface;
use Pixielity\Developer\Models\InternalNote;

/**
 * Service for managing admin-only internal notes on apps.
 *
 * Creates internal note records attached to apps and provides
 * note retrieval for admin users. Notes are never exposed through
 * public or developer-facing API endpoints. All data access is
 * delegated to the repository layer.
 */
#[Scoped]
#[UseRepository(InternalNoteRepositoryInterface::class)]
class NoteService extends Service implements NoteServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * Creates an InternalNote record attached to the specified app
     * with the admin's identifier and note body. The note is only
     * accessible to users with administrator privileges.
     */
    public function create(int|string $appId, int|string $adminId, string $body): InternalNote
    {
        /** @var InternalNote $note */
        $note = $this->repository->create([
            InternalNoteInterface::ATTR_APP_ID => $appId,
            InternalNoteInterface::ATTR_ADMIN_ID => $adminId,
            InternalNoteInterface::ATTR_BODY => $body,
        ]);

        return $note;
    }

    /**
     * {@inheritDoc}
     *
     * Returns all internal notes for the specified app, ordered by
     * creation date descending. Only accessible to admin users through
     * admin-restricted endpoints.
     */
    public function getNotesForApp(int|string $appId): Collection
    {
        /** @var InternalNoteRepositoryInterface $noteRepo */
        $noteRepo = $this->repository;

        return $noteRepo->findByApp($appId);
    }
}
