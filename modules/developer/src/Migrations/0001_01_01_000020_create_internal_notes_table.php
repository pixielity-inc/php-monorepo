<?php

declare(strict_types=1);

/**
 * Create Internal Notes Table Migration.
 *
 * Creates the internal_notes table for storing admin-only annotations
 * on apps. These notes are invisible to developers and tenants and
 * are used for documenting internal observations and decisions.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\InternalNoteInterface;

/**
 * Migration to create the internal_notes table.
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
        Schema::create(InternalNoteInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(InternalNoteInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(InternalNoteInterface::ATTR_ADMIN_ID);
            $table->text(InternalNoteInterface::ATTR_BODY);
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
        Schema::dropIfExists(InternalNoteInterface::TABLE);
    }
};
