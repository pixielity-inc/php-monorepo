<?php

declare(strict_types=1);

namespace Pixielity\Response\Presets;

use Illuminate\Container\Attributes\Singleton;
use Override;
use Pixielity\Contracts\Framework\Response\Preset as PresetContract;
use Pixielity\Response\Attributes\AsPreset;
use Pixielity\Response\Renderers\JsonRenderer;

/**
 * Admin/Dashboard preset configuration.
 *
 * Provides configuration for admin panel responses with relaxed
 * security headers, debug information enabled by default in
 * non-production environments, and always-pretty-printed JSON.
 *
 * Features:
 *   - JSON renderer as default
 *   - Relaxed security headers (SAMEORIGIN frame options)
 *   - Debug enabled in non-production environments
 *   - Always pretty-printed JSON for readability
 *   - No-cache headers for fresh data
 *
 * Usage:
 *   ```php
 *   return Response::admin()->success()->data($dashboardData);
 *   ```
 *
 * @category Presets
 *
 * @since    1.0.0
 *
 * @see PresetContract The interface this implements.
 * @see AsPreset The discovery attribute.
 */
#[Singleton]
#[AsPreset(name: 'admin')]
class AdminPreset implements PresetContract
{
    /**
     * Create a new AdminPreset instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the preset name.
     *
     * @return string Preset identifier.
     */
    #[Override]
    public function getName(): string
    {
        return 'admin';
    }

    /**
     * Get the default renderer class.
     *
     * Uses JSON renderer by default for API-driven admin panels.
     *
     * @return class-string<\Pixielity\Contracts\Framework\Response\Renderer> Renderer class.
     */
    #[Override]
    public function getDefaultRenderer(): string
    {
        return JsonRenderer::class;
    }

    /**
     * Get default headers for admin responses.
     *
     * Uses relaxed headers suitable for internal admin panels.
     *
     * @return array<string, string> Default headers.
     */
    #[Override]
    public function getDefaultHeaders(): array
    {
        return [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ];
    }

    /**
     * Get default meta for admin responses.
     *
     * Includes environment and admin version information.
     *
     * @return array<string, mixed> Default metadata.
     */
    #[Override]
    public function getDefaultMeta(): array
    {
        return [
            'environment' => app()->environment(),
            'admin_version' => config('admin.version', '1.0.0'),
        ];
    }

    /**
     * Get the API version.
     *
     * Admin panel doesn't typically need API versioning.
     *
     * @return string|null API version.
     */
    #[Override]
    public function getApiVersion(): ?string
    {
        return null;
    }

    /**
     * Check if debug mode is enabled.
     *
     * Admin panel enables debug by default in non-production environments.
     *
     * @return bool True if debug mode is enabled.
     */
    #[Override]
    public function isDebug(): bool
    {
        return ! app()->isProduction();
    }

    /**
     * Get JSON encoding flags.
     *
     * Admin responses always use pretty-printed JSON for readability.
     *
     * @return int JSON flags bitmask.
     */
    #[Override]
    public function getJsonFlags(): int
    {
        return JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_PRETTY_PRINT;
    }
}
