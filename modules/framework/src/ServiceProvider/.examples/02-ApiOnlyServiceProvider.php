<?php

declare(strict_types=1);

/**
 * Example 2: API-Only Service Provider.
 *
 * A module that serves only API endpoints — no views, no translations,
 * no publishable assets. Uses #[LoadsResources] to disable unnecessary
 * resource types.
 *
 * What is loaded:
 *   ✅ Migrations, Config, Routes, Commands, Seeders, Middleware, Listeners
 *
 * What is skipped:
 *   ❌ Views, Translations, Publishable assets
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Api\Providers;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * API module service provider.
 *
 * Disables views, translations, and publishables since this module
 * only serves JSON API responses.
 */
#[Module(
    name: 'Api',
    namespace: 'Pixielity\\Api',
)]
#[LoadsResources(
    views: false,
    translations: false,
    publishables: false,
)]
class ApiServiceProvider extends ServiceProvider
{
    // Only API-relevant resources are loaded.
    // Views, translations, and publishable assets are skipped entirely.
}
