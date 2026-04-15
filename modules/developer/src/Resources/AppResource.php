<?php

declare(strict_types=1);

/**
 * App API Resource.
 *
 * Transforms App model instances into JSON API responses for the
 * marketplace. Includes core app fields, developer information,
 * marketplace metadata, and conditionally loaded relationships
 * (plans, categories). Sensitive fields like client_secret and
 * webhook_secret are excluded from the output.
 *
 * @category Resources
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Models\App
 * @see \Pixielity\Developer\Contracts\Data\AppInterface
 */

namespace Pixielity\Developer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Pixielity\Developer\Contracts\Data\AppInterface;

/**
 * JSON API resource for the App model.
 *
 * Transforms marketplace application data for API responses,
 * including translatable fields, developer info, marketplace
 * stats, and conditionally loaded relationships.
 */
class AppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Maps App model attributes to a structured JSON response using
     * interface constants for attribute keys. Conditionally includes
     * plans and categories relationships when they are loaded on the model.
     *
     * @param  Request  $request  The incoming HTTP request.
     * @return array<string, mixed> The transformed app data.
     */
    public function toArray(Request $request): array
    {
        return [
            AppInterface::ATTR_ID => $this->getKey(),
            AppInterface::ATTR_NAME => $this->getAttribute(AppInterface::ATTR_NAME),
            AppInterface::ATTR_SLUG => $this->getAttribute(AppInterface::ATTR_SLUG),
            AppInterface::ATTR_SHORT_DESCRIPTION => $this->getAttribute(AppInterface::ATTR_SHORT_DESCRIPTION),
            AppInterface::ATTR_DESCRIPTION => $this->getAttribute(AppInterface::ATTR_DESCRIPTION),
            AppInterface::ATTR_LOGO => $this->getAttribute(AppInterface::ATTR_LOGO),
            AppInterface::ATTR_ICON => $this->getAttribute(AppInterface::ATTR_ICON),
            AppInterface::ATTR_COLOR => $this->getAttribute(AppInterface::ATTR_COLOR),

            // Developer information
            AppInterface::ATTR_DEVELOPER_NAME => $this->getAttribute(AppInterface::ATTR_DEVELOPER_NAME),
            AppInterface::ATTR_DEVELOPER_EMAIL => $this->getAttribute(AppInterface::ATTR_DEVELOPER_EMAIL),
            AppInterface::ATTR_DEVELOPER_URL => $this->getAttribute(AppInterface::ATTR_DEVELOPER_URL),
            AppInterface::ATTR_PRIVACY_POLICY_URL => $this->getAttribute(AppInterface::ATTR_PRIVACY_POLICY_URL),

            // OAuth (public fields only)
            AppInterface::ATTR_CLIENT_ID => $this->getAttribute(AppInterface::ATTR_CLIENT_ID),
            AppInterface::ATTR_REDIRECT_URI => $this->getAttribute(AppInterface::ATTR_REDIRECT_URI),

            // Marketplace metadata
            AppInterface::ATTR_STATUS => $this->getAttribute(AppInterface::ATTR_STATUS)?->value,
            AppInterface::ATTR_PLAN_TYPE => $this->getAttribute(AppInterface::ATTR_PLAN_TYPE),
            AppInterface::ATTR_ONE_CLICK_INSTALLATION => $this->getAttribute(AppInterface::ATTR_ONE_CLICK_INSTALLATION),
            AppInterface::ATTR_RATING => $this->getAttribute(AppInterface::ATTR_RATING),
            AppInterface::ATTR_REVIEWS_COUNT => $this->getAttribute(AppInterface::ATTR_REVIEWS_COUNT),
            AppInterface::ATTR_INSTALL_COUNT => $this->getAttribute(AppInterface::ATTR_INSTALL_COUNT),

            // Timestamps
            'created_at' => $this->getAttribute('created_at')?->toISOString(),
            'updated_at' => $this->getAttribute('updated_at')?->toISOString(),

            // Conditional relationships
            AppInterface::REL_PLANS => $this->whenLoaded(AppInterface::REL_PLANS),
            AppInterface::REL_CATEGORIES => $this->whenLoaded(AppInterface::REL_CATEGORIES),
        ];
    }
}
