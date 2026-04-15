<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\AppWebhookInterface;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(AppWebhookInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(AppWebhookInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();
            $table->string(AppWebhookInterface::ATTR_EVENT);
            $table->string(AppWebhookInterface::ATTR_URL);
            $table->text(AppWebhookInterface::ATTR_SECRET)->nullable();
            $table->boolean(AppWebhookInterface::ATTR_IS_ACTIVE)->default(true);
            $table->timestamps();

            $table->index([AppWebhookInterface::ATTR_APP_ID, AppWebhookInterface::ATTR_EVENT]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(AppWebhookInterface::TABLE);
    }
};
