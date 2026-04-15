<?php

declare(strict_types=1);

/**
 * Example 8: Vendor Overrides Service Provider.
 *
 * A module that overrides views and translations from third-party packages
 * (e.g., Spatie Health, Laravel Telescope) by placing files in the
 * views/vendor/ and i18n/vendor/ directories.
 *
 * This is useful when you need to:
 *   - Customize Spatie Health dashboard views
 *   - Override Laravel Telescope layout
 *   - Translate third-party package strings to Arabic
 *   - Rebrand third-party package UI
 *
 * Expected directory structure for vendor overrides:
 *   packages/admin/src/
 *   ├── views/
 *   │   ├── dashboard/
 *   │   │   └── index.blade.php           (module's own views)
 *   │   └── vendor/
 *   │       ├── health/                    (overrides spatie/laravel-health views)
 *   │       │   └── list.blade.php
 *   │       └── telescope/                 (overrides laravel/telescope views)
 *   │           └── layout.blade.php
 *   └── i18n/
 *       ├── en/
 *       │   └── messages.php               (module's own translations)
 *       ├── ar/
 *       │   └── messages.php
 *       └── vendor/
 *           ├── health/                    (overrides spatie/laravel-health translations)
 *           │   ├── en/
 *           │   │   └── notifications.php
 *           │   └── ar/
 *           │       └── notifications.php
 *           └── backup/                    (overrides spatie/laravel-backup translations)
 *               └── ar/
 *                   └── notifications.php
 *
 * How vendor overrides work:
 *   1. Files in views/vendor/{package}/ are registered as view overrides
 *      for the {package} namespace. When you call view('health::list'),
 *      Laravel uses YOUR override instead of the package's original.
 *
 *   2. Files in i18n/vendor/{package}/ are registered as translation
 *      overrides for the {package} namespace. When you call
 *      __('health::notifications.check_failed'), Laravel uses YOUR
 *      translation instead of the package's original.
 *
 *   3. Translation overrides are re-registered AFTER all providers boot
 *      to ensure they take precedence over the original package translations.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Admin\Providers;

use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Admin module service provider — vendor overrides example.
 *
 * Automatically discovers and registers view/translation overrides
 * from the views/vendor/ and i18n/vendor/ directories. No additional
 * configuration needed — the base class handles it.
 */
#[Module(
    name: 'Admin',
    namespace: 'Pixielity\\Admin',
    viewNamespace: 'admin',
    translationNamespace: 'admin',
)]
class AdminServiceProvider extends ServiceProvider
{
    // Vendor overrides are handled automatically by the base class.
    //
    // The LoadsResources trait scans for:
    //   - {sourceDir}/views/vendor/{package}/     → view overrides
    //   - {sourceDir}/i18n/vendor/{package}/      → translation overrides
    //
    // No additional code needed. Just place your override files in the
    // correct directory structure and they'll be registered automatically.
    //
    // Access module views:     view('admin::dashboard.index')
    // Access module i18n:      __('admin::messages.welcome')
    // Override health views:   view('health::list')  ← uses YOUR override
    // Override health i18n:    __('health::notifications.check_failed')  ← uses YOUR override
}
