<?php

declare(strict_types=1);

/**
 * Create Products Table Migration.
 *
 * Uses ATTR_* constants from ProductInterface for all column names —
 * no hardcoded strings. This ensures column names are consistent
 * between the migration, model, repository, and API resource.
 *
 * @category Migrations
 *
 * @since    1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Products\Contracts\Data\ProductInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ProductInterface::TABLE, function (Blueprint $table): void {
            $table->id(ProductInterface::ATTR_ID);
            $table->json(ProductInterface::ATTR_NAME);                                    // translatable (JSON)
            $table->string(ProductInterface::ATTR_SLUG)->unique();
            $table->json(ProductInterface::ATTR_DESCRIPTION)->nullable();                 // translatable (JSON)
            $table->unsignedInteger(ProductInterface::ATTR_PRICE)->default(0);            // price in cents
            $table->string(ProductInterface::ATTR_SKU, 100)->unique();
            $table->string(ProductInterface::ATTR_STATUS)->default('draft');              // draft, active, archived
            $table->foreignId(ProductInterface::ATTR_CATEGORY_ID)->constrained()->cascadeOnDelete();
            $table->unsignedInteger(ProductInterface::ATTR_STOCK)->default(0);
            $table->boolean(ProductInterface::ATTR_IS_FEATURED)->default(false);
            $table->timestamp(ProductInterface::ATTR_PUBLISHED_AT)->nullable();
            $table->softDeletes(ProductInterface::ATTR_DELETED_AT);
            $table->timestamps();

            // Indexes for common queries
            $table->index(ProductInterface::ATTR_STATUS);
            $table->index(ProductInterface::ATTR_IS_FEATURED);
            $table->index(ProductInterface::ATTR_CATEGORY_ID);
            $table->index(ProductInterface::ATTR_PRICE);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ProductInterface::TABLE);
    }
};
