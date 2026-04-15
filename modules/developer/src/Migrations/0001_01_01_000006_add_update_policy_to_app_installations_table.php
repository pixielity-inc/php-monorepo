<?php

declare(strict_types=1);

/**
 * Add Update Policy to App Installations Table Migration.
 *
 * Adds update_policy and installed_version_id columns to the existing
 * app_installations table to support version-aware update distribution.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;

/**
 * Migration to add update policy columns to the app_installations table.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(AppInstallationInterface::TABLE, function (Blueprint $table): void {
            $table->string(AppInstallationInterface::ATTR_UPDATE_POLICY)->default('auto')->after(AppInstallationInterface::ATTR_UNINSTALLED_AT);
            $table->unsignedBigInteger(AppInstallationInterface::ATTR_INSTALLED_VERSION_ID)->nullable()->after(AppInstallationInterface::ATTR_UPDATE_POLICY);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(AppInstallationInterface::TABLE, function (Blueprint $table): void {
            $table->dropColumn([
                AppInstallationInterface::ATTR_UPDATE_POLICY,
                AppInstallationInterface::ATTR_INSTALLED_VERSION_ID,
            ]);
        });
    }
};
