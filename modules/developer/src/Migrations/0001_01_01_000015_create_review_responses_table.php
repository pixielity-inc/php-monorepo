<?php

declare(strict_types=1);

/**
 * Create Review Responses Table Migration.
 *
 * Creates the review_responses table for storing developer replies to
 * tenant app reviews. Enforces one response per review via a unique
 * index on app_review_id.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Contracts\Data\ReviewResponseInterface;

/**
 * Migration to create the review_responses table.
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
        Schema::create(ReviewResponseInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(ReviewResponseInterface::ATTR_APP_REVIEW_ID)
                ->constrained(AppReviewInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(ReviewResponseInterface::ATTR_DEVELOPER_ID);
            $table->text(ReviewResponseInterface::ATTR_BODY);
            $table->timestamps();

            $table->unique(ReviewResponseInterface::ATTR_APP_REVIEW_ID);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(ReviewResponseInterface::TABLE);
    }
};
