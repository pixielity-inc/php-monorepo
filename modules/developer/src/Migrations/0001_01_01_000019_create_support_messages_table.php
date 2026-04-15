<?php

declare(strict_types=1);

/**
 * Create Support Messages Table Migration.
 *
 * Creates the support_messages table for storing individual messages
 * within support threads. Tracks author identity and type for each
 * message in the conversation.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\SupportMessageInterface;
use Pixielity\Developer\Contracts\Data\SupportThreadInterface;

/**
 * Migration to create the support_messages table.
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
        Schema::create(SupportMessageInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(SupportMessageInterface::ATTR_SUPPORT_THREAD_ID)
                ->constrained(SupportThreadInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(SupportMessageInterface::ATTR_AUTHOR_ID);
            $table->string(SupportMessageInterface::ATTR_AUTHOR_TYPE);
            $table->text(SupportMessageInterface::ATTR_BODY);
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
        Schema::dropIfExists(SupportMessageInterface::TABLE);
    }
};
