<?php

declare(strict_types=1);

/**
 * App Installation API Resource.
 *
 * Transforms AppInstallation model instances into JSON API responses.
 * Includes the installation status, granted scopes, timestamps, and
 * the conditionally loaded App relationship. Sensitive fields like
 * the access_token are excluded from the output.
 *
 * @category Resources
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Models\AppInstallation
 * @see \Pixielity\Developer\Contracts\Data\AppInstallationInterface
 */

namespace Pixielity\Developer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;

/**
 * JSON API resource for the AppInstallation model.
 *
 * Transforms installation records for API responses, including
 * status, granted scopes, timestamps, and the associated app
 * when the relationship is loaded.
 */
class AppInstallationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Maps AppInstallation model attributes to a structured JSON response
     * using interface constants for attribute keys. Conditionally includes
     * the App relationship via AppResource when loaded on the model.
     *
     * @param  Request  $request  The incoming HTTP request.
     * @return array<string, mixed> The transformed installation data.
     */
    public function toArray(Request $request): array
    {
        return [
            AppInstallationInterface::ATTR_ID => $this->getKey(),
            AppInstallationInterface::ATTR_APP_ID => $this->getAttribute(AppInstallationInterface::ATTR_APP_ID),
            AppInstallationInterface::ATTR_TENANT_ID => $this->getAttribute(AppInstallationInterface::ATTR_TENANT_ID),
            AppInstallationInterface::ATTR_INSTALLED_BY => $this->getAttribute(AppInstallationInterface::ATTR_INSTALLED_BY),
            AppInstallationInterface::ATTR_GRANTED_SCOPES => $this->getAttribute(AppInstallationInterface::ATTR_GRANTED_SCOPES),
            AppInstallationInterface::ATTR_STATUS => $this->getAttribute(AppInstallationInterface::ATTR_STATUS)?->value,
            AppInstallationInterface::ATTR_INSTALLED_AT => $this->getAttribute(AppInstallationInterface::ATTR_INSTALLED_AT)?->toISOString(),
            AppInstallationInterface::ATTR_UNINSTALLED_AT => $this->getAttribute(AppInstallationInterface::ATTR_UNINSTALLED_AT)?->toISOString(),

            // Timestamps
            'created_at' => $this->getAttribute('created_at')?->toISOString(),
            'updated_at' => $this->getAttribute('updated_at')?->toISOString(),

            // Conditional relationships
            AppInstallationInterface::REL_APP => AppResource::make($this->whenLoaded(AppInstallationInterface::REL_APP)),
        ];
    }
}
