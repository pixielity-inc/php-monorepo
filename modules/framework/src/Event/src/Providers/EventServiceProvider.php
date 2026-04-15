<?php

declare(strict_types=1);

/**
 * Event Service Provider.
 *
 * Registers the Event package. No manual bindings needed — Laravel's
 * event system is the engine. This package adds attribute-based discovery
 * and the #[EmitsEvent] AOP interceptor.
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\Event\Providers;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Service provider for the Event package.
 */
#[Module(name: 'Event', priority: 2)]
#[LoadsResources]
class EventServiceProvider extends ServiceProvider {}
