<?php

declare(strict_types=1);

/**
 * Create App Ratings Table Migration.
 *
 * Creates the app_ratings table for storing star ratings (1–5) submitted
 * by tenants for installed apps. Enforces one rating per tenant per app
 * via a unique index on [app_id, tenant_id].
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\AppRatingInterface;

/**
 * Migration to create the app_ratings table.
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
        Schema::create(AppRatingInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(AppRatingInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(AppRatingInterface::ATTR_TENANT_ID);
            $table->unsignedTinyInteger(AppRatingInterface::ATTR_RATING);
            $table->timestamps();

            $table->unique([AppRatingInterface::ATTR_APP_ID, AppRatingInterface::ATTR_TENANT_ID]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(AppRatingInterface::TABLE);
    }
};
