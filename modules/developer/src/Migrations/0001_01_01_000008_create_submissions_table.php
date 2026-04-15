<?php

declare(strict_types=1);

/**
 * Create Submissions Table Migration.
 *
 * Creates the submissions table for tracking developer submissions of
 * apps and app versions for marketplace review, including checklist
 * snapshots captured at submission time.
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
use Pixielity\Developer\Contracts\Data\SubmissionInterface;

/**
 * Migration to create the submissions table.
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
        Schema::create(SubmissionInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(SubmissionInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->foreignId(SubmissionInterface::ATTR_APP_VERSION_ID)
                ->nullable()
                ->constrained(AppVersionInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(SubmissionInterface::ATTR_SUBMITTED_BY);
            $table->json(SubmissionInterface::ATTR_CHECKLIST_SNAPSHOT)->nullable();
            $table->string(SubmissionInterface::ATTR_STATUS)->default('pending_review');
            $table->timestamp(SubmissionInterface::ATTR_SUBMITTED_AT);
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
        Schema::dropIfExists(SubmissionInterface::TABLE);
    }
};
