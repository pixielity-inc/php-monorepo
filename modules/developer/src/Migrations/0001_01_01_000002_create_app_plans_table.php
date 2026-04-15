<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\AppPlanInterface;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(AppPlanInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(AppPlanInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->json(AppPlanInterface::ATTR_NAME);
            $table->json(AppPlanInterface::ATTR_SUBTITLE)->nullable();
            $table->decimal(AppPlanInterface::ATTR_PRICE, 10, 2);
            $table->decimal(AppPlanInterface::ATTR_OLD_PRICE, 10, 2)->nullable();
            $table->string(AppPlanInterface::ATTR_RECURRING)->default('monthly');
            $table->decimal(AppPlanInterface::ATTR_INITIALIZATION_COST, 10, 2)->default(0);
            $table->boolean(AppPlanInterface::ATTR_RECOMMENDED)->default(false);
            $table->json(AppPlanInterface::ATTR_FEATURES)->nullable();
            $table->unsignedInteger(AppPlanInterface::ATTR_SORT_ORDER)->default(0);
            $table->boolean(AppPlanInterface::ATTR_IS_ACTIVE)->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(AppPlanInterface::TABLE);
    }
};
