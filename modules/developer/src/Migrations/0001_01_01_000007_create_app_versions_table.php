<?php

declare(strict_types=1);

/**
 * Create App Versions Table Migration.
 *
 * Creates the app_versions table for storing semantically versioned
 * releases of marketplace apps with changelog, compatibility metadata,
 * and breaking change information.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;

/**
 * Migration to create the app_versions table.
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
        Schema::create(AppVersionInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(AppVersionInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->string(AppVersionInterface::ATTR_VERSION);
            $table->text(AppVersionInterface::ATTR_CHANGELOG)->nullable();
            $table->text(AppVersionInterface::ATTR_RELEASE_NOTES)->nullable();
            $table->json(AppVersionInterface::ATTR_COMPATIBILITY)->nullable();
            $table->boolean(AppVersionInterface::ATTR_IS_BREAKING_CHANGE)->default(false);
            $table->text(AppVersionInterface::ATTR_MIGRATION_GUIDE)->nullable();
            $table->string(AppVersionInterface::ATTR_STATUS)->default('draft');
            $table->timestamp(AppVersionInterface::ATTR_PUBLISHED_AT)->nullable();
            $table->timestamps();

            $table->unique([AppVersionInterface::ATTR_APP_ID, AppVersionInterface::ATTR_VERSION]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(AppVersionInterface::TABLE);
    }
};
