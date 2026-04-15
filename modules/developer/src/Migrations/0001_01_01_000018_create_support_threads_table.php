<?php

declare(strict_types=1);

/**
 * Create Support Threads Table Migration.
 *
 * Creates the support_threads table for storing private conversations
 * between tenants and app developers for resolving installation-specific
 * issues.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\SupportThreadInterface;

/**
 * Migration to create the support_threads table.
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
        Schema::create(SupportThreadInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(SupportThreadInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(SupportThreadInterface::ATTR_TENANT_ID);
            $table->string(SupportThreadInterface::ATTR_SUBJECT);
            $table->string(SupportThreadInterface::ATTR_STATUS)->default('open');
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
        Schema::dropIfExists(SupportThreadInterface::TABLE);
    }
};
