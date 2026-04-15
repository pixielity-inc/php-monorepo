<?php

declare(strict_types=1);

/**
 * Create Violation Reports Table Migration.
 *
 * Creates the violation_reports table for recording policy violations
 * reported against marketplace apps by tenants, developers, or automated
 * system scans. Tracks confirmation status and admin decisions.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\ViolationReportInterface;

/**
 * Migration to create the violation_reports table.
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
        Schema::create(ViolationReportInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(ViolationReportInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(ViolationReportInterface::ATTR_REPORTER_ID)->nullable();
            $table->string(ViolationReportInterface::ATTR_REPORTER_TYPE);
            $table->string(ViolationReportInterface::ATTR_VIOLATION_TYPE);
            $table->string(ViolationReportInterface::ATTR_SEVERITY);
            $table->text(ViolationReportInterface::ATTR_DESCRIPTION);
            $table->boolean(ViolationReportInterface::ATTR_IS_CONFIRMED)->default(false);
            $table->unsignedBigInteger(ViolationReportInterface::ATTR_CONFIRMED_BY)->nullable();
            $table->timestamp(ViolationReportInterface::ATTR_CONFIRMED_AT)->nullable();
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
        Schema::dropIfExists(ViolationReportInterface::TABLE);
    }
};
