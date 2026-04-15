<?php

declare(strict_types=1);

/**
 * Create Review Assignments Table Migration.
 *
 * Creates the review_assignments table for binding admin reviewers to
 * specific submissions. Enforces one assignment per submission via
 * a unique index on submission_id.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\ReviewAssignmentInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;

/**
 * Migration to create the review_assignments table.
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
        Schema::create(ReviewAssignmentInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(ReviewAssignmentInterface::ATTR_SUBMISSION_ID)
                ->constrained(SubmissionInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(ReviewAssignmentInterface::ATTR_REVIEWER_ID);
            $table->timestamp(ReviewAssignmentInterface::ATTR_ASSIGNED_AT);
            $table->timestamps();

            $table->unique(ReviewAssignmentInterface::ATTR_SUBMISSION_ID);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(ReviewAssignmentInterface::TABLE);
    }
};
