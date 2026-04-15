<?php

declare(strict_types=1);

/**
 * Create Staged Rollouts Table Migration.
 *
 * Creates the staged_rollouts table for tracking percentage-based
 * progressive deployment of app versions to active installations.
 * Records target percentage, update counts, and rollout status.
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
use Pixielity\Developer\Contracts\Data\StagedRolloutInterface;

/**
 * Migration to create the staged_rollouts table.
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
        Schema::create(StagedRolloutInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(StagedRolloutInterface::ATTR_APP_VERSION_ID)
                ->constrained(AppVersionInterface::TABLE)
                ->cascadeOnDelete();
            $table->foreignId(StagedRolloutInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedTinyInteger(StagedRolloutInterface::ATTR_TARGET_PERCENTAGE);
            $table->unsignedInteger(StagedRolloutInterface::ATTR_UPDATED_COUNT)->default(0);
            $table->unsignedInteger(StagedRolloutInterface::ATTR_REMAINING_COUNT)->default(0);
            $table->string(StagedRolloutInterface::ATTR_STATUS)->default('in_progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(StagedRolloutInterface::TABLE);
    }
};
