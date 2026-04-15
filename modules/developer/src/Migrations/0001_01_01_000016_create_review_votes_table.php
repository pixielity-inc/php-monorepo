<?php

declare(strict_types=1);

/**
 * Create Review Votes Table Migration.
 *
 * Creates the review_votes table for recording helpful or unhelpful
 * votes cast by tenants on app reviews. Enforces one vote per tenant
 * per review via a unique index on [app_review_id, tenant_id].
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Contracts\Data\ReviewVoteInterface;

/**
 * Migration to create the review_votes table.
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
        Schema::create(ReviewVoteInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(ReviewVoteInterface::ATTR_APP_REVIEW_ID)
                ->constrained(AppReviewInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(ReviewVoteInterface::ATTR_TENANT_ID);
            $table->string(ReviewVoteInterface::ATTR_VOTE_TYPE);
            $table->timestamps();

            $table->unique([ReviewVoteInterface::ATTR_APP_REVIEW_ID, ReviewVoteInterface::ATTR_TENANT_ID]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(ReviewVoteInterface::TABLE);
    }
};
