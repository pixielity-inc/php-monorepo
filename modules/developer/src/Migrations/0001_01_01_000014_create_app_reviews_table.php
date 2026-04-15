<?php

declare(strict_types=1);

/**
 * Create App Reviews Table Migration.
 *
 * Creates the app_reviews table for storing written text reviews
 * accompanying app ratings. Includes moderation status tracking and
 * helpfulness scoring with appropriate indexes.
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
use Pixielity\Developer\Contracts\Data\AppReviewInterface;

/**
 * Migration to create the app_reviews table.
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
        Schema::create(AppReviewInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(AppReviewInterface::ATTR_APP_RATING_ID)
                ->constrained(AppRatingInterface::TABLE)
                ->cascadeOnDelete();
            $table->foreignId(AppReviewInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(AppReviewInterface::ATTR_TENANT_ID);
            $table->string(AppReviewInterface::ATTR_TITLE);
            $table->text(AppReviewInterface::ATTR_BODY);
            $table->string(AppReviewInterface::ATTR_MODERATION_STATUS)->default('pending');
            $table->integer(AppReviewInterface::ATTR_HELPFULNESS_SCORE)->default(0);
            $table->timestamps();

            $table->index([AppReviewInterface::ATTR_APP_ID, AppReviewInterface::ATTR_MODERATION_STATUS]);
            $table->index(AppReviewInterface::ATTR_HELPFULNESS_SCORE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(AppReviewInterface::TABLE);
    }
};
