<?php

declare(strict_types=1);

/**
 * Localization Service Provider.
 *
 * Registers the Localization module with translations, locale detection,
 * and timezone handling. Middleware auto-discovered via #[AsMiddleware].
 *
 * @category Providers
 *
 * @since    2.0.0
 */

namespace Pixielity\Localization\Providers;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Localization module service provider.
 */
#[Module(name: 'Localization', priority: 3)]
#[LoadsResources(config: true, translations: true, middleware: true, publishables: true)]
class LocalizationServiceProvider extends ServiceProvider {}
