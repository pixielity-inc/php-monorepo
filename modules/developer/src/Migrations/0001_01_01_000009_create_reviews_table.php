<?php

declare(strict_types=1);

/**
 * Create Reviews Table Migration.
 *
 * Creates the reviews table for storing administrative evaluations of
 * submissions. Records reviewer decisions, notes, rejection reasons,
 * and elapsed time for SLA tracking.
 *
 * Note: This is the admin review table, not the tenant app review table.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\ReviewInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;

/**
 * Migration to create the reviews table.
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
        Schema::create(ReviewInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(ReviewInterface::ATTR_SUBMISSION_ID)
                ->constrained(SubmissionInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(ReviewInterface::ATTR_REVIEWER_ID);
            $table->string(ReviewInterface::ATTR_DECISION);
            $table->text(ReviewInterface::ATTR_NOTES)->nullable();
            $table->json(ReviewInterface::ATTR_REJECTION_REASONS)->nullable();
            $table->unsignedInteger(ReviewInterface::ATTR_ELAPSED_SECONDS)->nullable();
            $table->timestamp(ReviewInterface::ATTR_REVIEWED_AT);
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
        Schema::dropIfExists(ReviewInterface::TABLE);
    }
};
