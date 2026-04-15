<?php

declare(strict_types=1);

/**
 * Notification Service Provider.
 *
 * No manual bindings — container attributes handle it.
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\Notification\Providers;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Service provider for the Notification package.
 */
#[Module(name: 'Notification', priority: 5)]
#[LoadsResources]
class NotificationServiceProvider extends ServiceProvider {}
