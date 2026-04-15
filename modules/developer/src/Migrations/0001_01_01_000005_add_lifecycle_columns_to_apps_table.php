<?php

declare(strict_types=1);

/**
 * Add Lifecycle Columns to Apps Table Migration.
 *
 * Adds version tracking, warning level, and developer ownership columns
 * to the existing apps table to support the marketplace lifecycle workflow.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;

/**
 * Migration to add lifecycle columns to the apps table.
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
        Schema::table(AppInterface::TABLE, function (Blueprint $table): void {
            $table->unsignedBigInteger(AppInterface::ATTR_CURRENT_VERSION_ID)->nullable()->after(AppInterface::ATTR_METADATA);
            $table->unsignedBigInteger(AppInterface::ATTR_LATEST_PENDING_VERSION_ID)->nullable()->after(AppInterface::ATTR_CURRENT_VERSION_ID);
            $table->string(AppInterface::ATTR_WARNING_LEVEL)->default('none')->after(AppInterface::ATTR_LATEST_PENDING_VERSION_ID);
            $table->unsignedBigInteger(AppInterface::ATTR_DEVELOPER_ID)->nullable()->after(AppInterface::ATTR_WARNING_LEVEL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(AppInterface::TABLE, function (Blueprint $table): void {
            $table->dropColumn([
                AppInterface::ATTR_CURRENT_VERSION_ID,
                AppInterface::ATTR_LATEST_PENDING_VERSION_ID,
                AppInterface::ATTR_WARNING_LEVEL,
                AppInterface::ATTR_DEVELOPER_ID,
            ]);
        });
    }
};
