<?php

declare(strict_types=1);

/**
 * App Installation Service Interface.
 *
 * Defines the contract for managing app installations across tenants.
 * Covers the full installation lifecycle: consent data retrieval,
 * installation with scope granting, uninstallation, and querying
 * installation state. All mutations dispatch domain events.
 *
 * Bound to {@see \Pixielity\Developer\Services\AppInstallationService}
 * via the #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\AppInstallationService
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Crud\Contracts\ServiceInterface;
use Pixielity\Developer\Models\App;

/**
 * Contract for the App Installation service.
 *
 * Provides methods for installing and uninstalling apps for tenants,
 * retrieving consent data for the authorization screen, and querying
 * installation state across the platform.
 */
#[Bind('Pixielity\\Developer\\Services\\AppInstallationService')]
interface AppInstallationServiceInterface extends ServiceInterface
{
    /**
     * Install an app for a tenant.
     *
     * Creates an installation record binding the app to the tenant with
     * the granted OAuth scopes. Generates a unique access token for the
     * installation and dispatches an AppInstalled event.
     *
     * @param  int|string  $appId          The application ID to install.
     * @param  int|string  $tenantId       The tenant ID installing the app.
     * @param  int|string  $installedBy    The user ID performing the installation.
     * @param  array<int, string>  $grantedScopes  The OAuth scopes granted by the tenant.
     * @return Model The created AppInstallation record.
     */
    public function install(
        int|string $appId,
        int|string $tenantId,
        int|string $installedBy,
        array $grantedScopes = [],
    ): Model;

    /**
     * Uninstall an app from a tenant.
     *
     * Marks the installation as uninstalled, revokes the access token,
     * and dispatches an AppUninstalled event. Does not delete the record
     * to preserve audit history.
     *
     * @param  int|string  $appId     The application ID to uninstall.
     * @param  int|string  $tenantId  The tenant ID to uninstall from.
     * @return bool True if the app was successfully uninstalled, false if no active installation was found.
     */
    public function uninstall(int|string $appId, int|string $tenantId): bool;

    /**
     * Get the consent data for an app installation screen.
     *
     * Returns the app details and requested scopes needed to render
     * the OAuth consent/authorization screen before installation.
     *
     * @param  int|string  $appId  The application ID.
     * @return array{app: App, scopes: array<string, string>} The app instance and its requested scopes.
     */
    public function getConsentData(int|string $appId): array;

    /**
     * Get all active installations for a specific tenant.
     *
     * Returns a collection of AppInstallation records with the associated
     * App relationship eagerly loaded. Only active installations are included.
     *
     * @param  int|string  $tenantId  The tenant ID to query installations for.
     * @return Collection The collection of active AppInstallation records.
     */
    public function getInstallationsForTenant(int|string $tenantId): Collection;

    /**
     * Check if an app is currently installed for a specific tenant.
     *
     * Returns true only if an active (non-uninstalled) installation
     * exists for the given app and tenant combination.
     *
     * @param  int|string  $appId     The application ID.
     * @param  int|string  $tenantId  The tenant ID.
     * @return bool True if the app is actively installed for the tenant.
     */
    public function isInstalled(int|string $appId, int|string $tenantId): bool;
}
