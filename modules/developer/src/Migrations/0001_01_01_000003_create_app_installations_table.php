<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(AppInstallationInterface::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignId(AppInstallationInterface::ATTR_APP_ID)
                ->constrained(AppInterface::TABLE)
                ->cascadeOnDelete();

            // Cross-context FKs
            $table->unsignedBigInteger(AppInstallationInterface::ATTR_TENANT_ID);
            $table->unsignedBigInteger(AppInstallationInterface::ATTR_INSTALLED_BY);

            $table->json(AppInstallationInterface::ATTR_GRANTED_SCOPES)->nullable();
            $table->string(AppInstallationInterface::ATTR_STATUS)->default('active');
            $table->text(AppInstallationInterface::ATTR_ACCESS_TOKEN)->nullable();
            $table->timestamp(AppInstallationInterface::ATTR_INSTALLED_AT);
            $table->timestamp(AppInstallationInterface::ATTR_UNINSTALLED_AT)->nullable();
            $table->timestamps();

            $table->unique([AppInstallationInterface::ATTR_APP_ID, AppInstallationInterface::ATTR_TENANT_ID]);
            $table->index(AppInstallationInterface::ATTR_TENANT_ID);
            $table->index(AppInstallationInterface::ATTR_STATUS);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(AppInstallationInterface::TABLE);
    }
};
