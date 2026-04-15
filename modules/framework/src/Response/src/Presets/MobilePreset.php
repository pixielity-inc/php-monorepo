<?php

declare(strict_types=1);

namespace Pixielity\Response\Presets;

use Illuminate\Container\Attributes\Singleton;
use Override;
use Pixielity\Contracts\Framework\Response\Preset as PresetContract;
use Pixielity\Response\Attributes\AsPreset;
use Pixielity\Response\Renderers\JsonRenderer;

/**
 * Mobile client preset configuration.
 *
 * Provides configuration optimized for mobile app responses with
 * compact JSON output, minimal headers for bandwidth optimization,
 * and client-side caching support.
 *
 * Features:
 *   - Compact JSON output (no pretty printing)
 *   - Minimal headers to reduce bandwidth
 *   - 5-minute client cache (Cache-Control: private, max-age=300)
 *   - Debug disabled to minimize payload size
 *
 * Usage:
 *   ```php
 *   return Response::mobile()->success()->data($feed);
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
#[AsPreset(name: 'mobile')]
class MobilePreset implements PresetContract
{
    /**
     * Create a new MobilePreset instance.
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
        return 'mobile';
    }

    /**
     * Get the default renderer class.
     *
     * Mobile uses compact JSON renderer.
     *
     * @return class-string<\Pixielity\Contracts\Framework\Response\Renderer> Renderer class.
     */
    #[Override]
    public function getDefaultRenderer(): string
    {
        return JsonRenderer::class;
    }

    /**
     * Get default headers for mobile responses.
     *
     * Minimal headers optimized for mobile bandwidth.
     *
     * @return array<string, string> Default headers.
     */
    #[Override]
    public function getDefaultHeaders(): array
    {
        return [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=300',
            'Vary' => 'Accept-Encoding, Authorization',
        ];
    }

    /**
     * Get default meta for mobile responses.
     *
     * Includes mobile-specific metadata.
     *
     * @return array<string, mixed> Default metadata.
     */
    #[Override]
    public function getDefaultMeta(): array
    {
        return [
            'api_version' => $this->getApiVersion() ?? 'v1',
            'platform' => 'mobile',
        ];
    }

    /**
     * Get the API version.
     *
     * @return string|null API version.
     */
    #[Override]
    public function getApiVersion(): ?string
    {
        return config('api.mobile_version', config('api.version', 'v1'));
    }

    /**
     * Check if debug mode is enabled.
     *
     * Debug is disabled for mobile to reduce payload size.
     *
     * @return bool Always false for mobile.
     */
    #[Override]
    public function isDebug(): bool
    {
        return false;
    }

    /**
     * Get JSON encoding flags.
     *
     * Mobile responses use compact JSON to minimize bandwidth.
     *
     * @return int JSON flags bitmask.
     */
    #[Override]
    public function getJsonFlags(): int
    {
        return JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    }
}
