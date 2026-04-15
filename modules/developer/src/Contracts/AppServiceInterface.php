<?php

declare(strict_types=1);

/**
 * App Service Interface.
 *
 * Defines the contract for managing developer applications in the marketplace.
 * Covers the full CRUD lifecycle including creation with OAuth credential
 * generation, publishing, suspension, and paginated listing. All mutations
 * dispatch domain events for cross-context listeners.
 *
 * Bound to {@see \Pixielity\Developer\Services\AppService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\AppService
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\App;

/**
 * Contract for the App CRUD service.
 *
 * Provides methods for creating, updating, deleting, publishing,
 * and suspending marketplace applications. Implementations must
 * generate OAuth credentials on creation and dispatch appropriate
 * domain events on state transitions.
 */
#[Bind('Pixielity\\Developer\\Services\\AppService')]
interface AppServiceInterface
{
    /**
     * Create a new developer application.
     *
     * Generates a unique client_id and client_secret for OAuth2 authentication.
     * The application is created in DRAFT status by default and must be
     * explicitly published before it becomes visible in the marketplace.
     *
     * @param  array<string, mixed>  $data  The application data including name, slug, developer info, etc.
     * @return App The newly created application instance with generated OAuth credentials.
     */
    public function create(array $data): App;

    /**
     * Update an existing developer application.
     *
     * Updates the specified fields on the application record. Only provided
     * fields are modified; omitted fields retain their current values.
     *
     * @param  int|string  $id    The application ID.
     * @param  array<string, mixed>  $data  The fields to update.
     * @return App The updated application instance.
     */
    public function update(int|string $id, array $data): App;

    /**
     * Delete a developer application.
     *
     * Removes the application from the system. If the model uses soft deletes,
     * the record is soft-deleted; otherwise it is permanently removed.
     *
     * @param  int|string  $id  The application ID.
     * @return bool True if the application was successfully deleted.
     */
    public function delete(int|string $id): bool;

    /**
     * Find a developer application or throw a ModelNotFoundException.
     *
     * Eagerly loads the plans and categories relationships for complete
     * application data retrieval.
     *
     * @param  int|string      $id       The application ID.
     * @param  array<string>   $columns  Columns to select.
     * @return App The application instance with loaded relationships.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the application is not found.
     */
    public function findOrFail(int|string $id, array $columns = ['*']): App;

    /**
     * Paginate developer applications.
     *
     * Returns a paginated collection of applications with plans and
     * categories eagerly loaded. Suitable for admin dashboard listings.
     *
     * @param  int|null        $perPage  The number of applications per page.
     * @param  array<string>   $columns  Columns to select.
     * @return LengthAwarePaginator The paginated application collection.
     */
    public function paginate(?int $perPage = null, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Publish a developer application to the marketplace.
     *
     * Transitions the application status from DRAFT to PUBLISHED, making
     * it visible and installable by tenants. Dispatches an AppPublished event.
     *
     * @param  int|string  $id  The application ID.
     * @return App The published application instance.
     */
    public function publish(int|string $id): App;

    /**
     * Suspend a developer application.
     *
     * Transitions the application status to SUSPENDED, hiding it from
     * the marketplace and preventing new installations. Existing installations
     * remain active. Dispatches an AppSuspended event.
     *
     * @param  int|string  $id  The application ID.
     * @return App The suspended application instance.
     */
    public function suspend(int|string $id): App;
}
