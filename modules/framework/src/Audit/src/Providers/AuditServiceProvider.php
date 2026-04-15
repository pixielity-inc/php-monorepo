<?php

declare(strict_types=1);

/**
 * Audit Service Provider.
 *
 * Registers the Audit package. Loads routes, config, and listeners.
 *
 * Container bindings handled by #[Bind] + #[Scoped] on AuditManagerInterface.
 * Auth event listeners auto-discovered via #[ListensTo] on AuthEventListener.
 * Controller auto-discovered via #[AsController] on AuditController.
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\Audit\Providers;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Service provider for the Audit package.
 */
#[Module(name: 'Audit', priority: 4)]
#[LoadsResources(routes: true, config: true, listeners: true)]
class AuditServiceProvider extends ServiceProvider {}
