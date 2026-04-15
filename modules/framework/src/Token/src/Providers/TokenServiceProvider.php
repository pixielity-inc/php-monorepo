<?php

declare(strict_types=1);

/**
 * Token Service Provider.
 *
 * Registers the Token package. No manual bindings needed — container
 * attributes (#[Bind] + #[Scoped]) on TokenManagerInterface handle
 * all wiring automatically.
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Providers;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Service provider for the Token package.
 */
#[Module(name: 'Token', priority: 3)]
#[LoadsResources]
class TokenServiceProvider extends ServiceProvider {}
