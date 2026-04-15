<?php

declare(strict_types=1);

/**
 * Create Comments Table Migration.
 *
 * Creates the comments table for storing public messages on app marketplace
 * pages. Supports threaded replies via self-referencing parent_id and
 * soft deletion for moderation purposes.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\CommentInterface;

/**
 * Migration to create the comments table.
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
        Schema::create(CommentInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(CommentInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->unsignedBigInteger(CommentInterface::ATTR_AUTHOR_ID);
            $table->string(CommentInterface::ATTR_AUTHOR_TYPE);
            $table->foreignId(CommentInterface::ATTR_PARENT_ID)
                ->nullable()
                ->constrained(CommentInterface::TABLE)
                ->cascadeOnDelete();
            $table->text(CommentInterface::ATTR_BODY);
            $table->softDeletes();
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
        Schema::dropIfExists(CommentInterface::TABLE);
    }
};
