<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppCategoryInterface;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(AppCategoryInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->string(AppCategoryInterface::ATTR_SLUG)->unique();
            $table->json(AppCategoryInterface::ATTR_NAME);
            $table->json(AppCategoryInterface::ATTR_TITLE)->nullable();
            $table->json(AppCategoryInterface::ATTR_DESCRIPTION)->nullable();
            $table->string(AppCategoryInterface::ATTR_ICON)->nullable();
            $table->string(AppCategoryInterface::ATTR_COLOR)->default('blue');
            $table->string(AppCategoryInterface::ATTR_IMAGE)->nullable();
            $table->boolean(AppCategoryInterface::ATTR_FEATURED)->default(false);
            $table->unsignedInteger(AppCategoryInterface::ATTR_SORT_ORDER)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(AppCategoryInterface::TABLE);
    }
};
