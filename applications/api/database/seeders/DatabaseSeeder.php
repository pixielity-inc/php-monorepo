<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Pixielity\Database\Seeder;

/**
 * Database Seeder.
 *
 * Main application seeder that automatically discovers and runs all module seeders.
 * Module seeders are registered via their ServiceProviders and executed in priority order.
 *
 * ## How It Works:
 * 1. Each module's ServiceProvider registers its DatabaseSeeder in config
 * 2. This seeder reads all registered seeders from config('app.module_seeders')
 * 3. Sorts them by priority (lower numbers run first)
 * 4. Executes each seeder in order
 *
 * ## Usage:
 *
 * ### Seed All Modules:
 * ```bash
 * php artisan db:seed
 * ```
 *
 * ### Seed Specific Module:
 * ```bash
 * php artisan db:seed --class=Modules\\User\\Database\\Seeders\\UserDatabaseSeeder
 * ```
 *
 * ### Control Seeding Volume:
 * ```bash
 * # Fast (10 records per seeder)
 * SEEDER_COUNT=10 php artisan db:seed
 *
 * # Default (50 records per seeder)
 * php artisan db:seed
 *
 * # Comprehensive (100 records per seeder)
 * SEEDER_COUNT=100 php artisan db:seed
 * ```
 *
 * ## Module Seeder Priority:
 * Module seeders can define priority to control execution order:
 *
 * ```php
 * class UserDatabaseSeeder extends Seeder
 * {
 *     public static int $priority = 10; // Runs early
 *
 *     public function run(): void
 *     {
 *         // Seed users
 *     }
 * }
 * ```
 *
 * ## Recommended Priority Values:
 * - 10: Core/foundational data (Users, Roles, Permissions)
 * - 20: Reference data (Countries, Cities, Settings)
 * - 30: Business entities (Facilities, Licenses)
 * - 40: Dependent entities (Checklists, Rules)
 * - 50: Transactional data (Visits, Incidents)
 * - 100: Default (no specific order required)
 *
 * @see Seeder Base seeder with auto-discovery
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Automatically calls all registered module seeders in priority order.
     * Override this method if you need custom seeding logic.
     */
    public function run(): void
    {
        // Call parent to run all module seeders
        parent::run();

        // Add any application-specific seeding here if needed
        // Example:
        // $this->call([
        //     CustomSeeder::class,
        // ]);
    }
}
