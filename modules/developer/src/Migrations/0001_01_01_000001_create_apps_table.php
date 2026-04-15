<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(AppInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->json(AppInterface::ATTR_NAME);
            $table->string(AppInterface::ATTR_SLUG)->unique();
            $table->json(AppInterface::ATTR_SHORT_DESCRIPTION)->nullable();
            $table->json(AppInterface::ATTR_DESCRIPTION)->nullable();
            $table->string(AppInterface::ATTR_LOGO)->nullable();
            $table->string(AppInterface::ATTR_ICON)->nullable();
            $table->string(AppInterface::ATTR_COLOR)->default('blue');

            // Developer info
            $table->string(AppInterface::ATTR_DEVELOPER_NAME);
            $table->string(AppInterface::ATTR_DEVELOPER_EMAIL)->nullable();
            $table->string(AppInterface::ATTR_DEVELOPER_URL)->nullable();
            $table->string(AppInterface::ATTR_PRIVACY_POLICY_URL)->nullable();

            // OAuth
            $table->string(AppInterface::ATTR_CLIENT_ID)->unique();
            $table->text(AppInterface::ATTR_CLIENT_SECRET);
            $table->string(AppInterface::ATTR_REDIRECT_URI)->nullable();
            $table->string(AppInterface::ATTR_WEBHOOK_URL)->nullable();
            $table->text(AppInterface::ATTR_WEBHOOK_SECRET)->nullable();

            // Scopes
            $table->json(AppInterface::ATTR_REQUESTED_SCOPES)->nullable();

            // Marketplace
            $table->string(AppInterface::ATTR_STATUS)->default('draft');
            $table->string(AppInterface::ATTR_PLAN_TYPE)->default('free');
            $table->boolean(AppInterface::ATTR_ONE_CLICK_INSTALLATION)->default(false);
            $table->decimal(AppInterface::ATTR_RATING, 2, 1)->default(0);
            $table->unsignedInteger(AppInterface::ATTR_REVIEWS_COUNT)->default(0);
            $table->unsignedInteger(AppInterface::ATTR_INSTALL_COUNT)->default(0);
            $table->json(AppInterface::ATTR_METADATA)->nullable();
            $table->timestamps();

            $table->index(AppInterface::ATTR_STATUS);
            $table->index(AppInterface::ATTR_PLAN_TYPE);
        });

        // Pivot table for app-category many-to-many
        Schema::create('app_category_app', function (Blueprint $table): void {
            $table->foreignId('app_id')->constrained(AppInterface::TABLE)->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('app_categories')->cascadeOnDelete();
            $table->primary(['app_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_category_app');
        Schema::dropIfExists(AppInterface::TABLE);
    }
};
