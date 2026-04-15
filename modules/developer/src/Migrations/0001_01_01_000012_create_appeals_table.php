<?php

declare(strict_types=1);

/**
 * Create Appeals Table Migration.
 *
 * Creates the appeals table for storing developer contestations of
 * confirmed violations. Tracks justification, evidence, administrator
 * decisions, and resolution timestamps.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppealInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\ViolationReportInterface;

/**
 * Migration to create the appeals table.
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
        Schema::create(AppealInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(AppealInterface::ATTR_VIOLATION_REPORT_ID)
                ->constrained(ViolationReportInterface::TABLE)
                ->cascadeOnDelete();
            $table->foreignId(AppealInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(AppealInterface::ATTR_DEVELOPER_ID);
            $table->text(AppealInterface::ATTR_JUSTIFICATION);
            $table->json(AppealInterface::ATTR_EVIDENCE)->nullable();
            $table->string(AppealInterface::ATTR_STATUS)->default('pending');
            $table->unsignedBigInteger(AppealInterface::ATTR_ADMIN_ID)->nullable();
            $table->text(AppealInterface::ATTR_ADMIN_REASONING)->nullable();
            $table->timestamp(AppealInterface::ATTR_RESOLVED_AT)->nullable();
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
        Schema::dropIfExists(AppealInterface::TABLE);
    }
};
