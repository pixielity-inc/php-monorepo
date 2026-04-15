<?php

declare(strict_types=1);

namespace Pixielity\Response\Presets;

use Illuminate\Container\Attributes\Singleton;
use Override;
use Pixielity\Contracts\Framework\Response\Preset as PresetContract;
use Pixielity\Response\Attributes\AsPreset;
use Pixielity\Response\Renderers\JsonRenderer;

/**
 * API preset configuration.
 *
 * Provides configuration for JSON API responses with strict security
 * headers, API versioning support, and compact JSON output.
 *
 * Features:
 *   - JSON renderer as default
 *   - Strict security headers (X-Content-Type-Options, X-Frame-Options,
 *     X-XSS-Protection, Referrer-Policy)
 *   - API versioning from config
 *   - Compact JSON with debug-mode pretty printing
 *
 * Usage:
 *   ```php
 *   return Response::api()->success()->data($users);
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
#[AsPreset(name: 'api')]
class ApiPreset implements PresetContract
{
    /**
     * Create a new ApiPreset instance.
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
        return 'api';
    }

    /**
     * Get the default renderer class.
     *
     * @return class-string<\Pixielity\Contracts\Framework\Response\Renderer> Renderer class.
     */
    #[Override]
    public function getDefaultRenderer(): string
    {
        return JsonRenderer::class;
    }

    /**
     * Get default headers for API responses.
     *
     * Includes strict security headers for API endpoints.
     *
     * @return array<string, string> Default headers.
     */
    #[Override]
    public function getDefaultHeaders(): array
    {
        return [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Cache-Control' => 'private, must-revalidate',
        ];
    }

    /**
     * Get default meta for API responses.
     *
     * Includes API version information.
     *
     * @return array<string, mixed> Default metadata.
     */
    #[Override]
    public function getDefaultMeta(): array
    {
        return [
            'api_version' => $this->getApiVersion() ?? 'v1',
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
        return config('api.version', 'v1');
    }

    /**
     * Check if debug mode is enabled.
     *
     * @return bool True if debug mode is enabled.
     */
    #[Override]
    public function isDebug(): bool
    {
        return (bool) config('app.debug', false);
    }

    /**
     * Get JSON encoding flags.
     *
     * API responses use compact JSON with unescaped slashes.
     * Pretty printing is added in debug mode.
     *
     * @return int JSON flags bitmask.
     */
    #[Override]
    public function getJsonFlags(): int
    {
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

        if ($this->isDebug()) {
            $flags |= JSON_PRETTY_PRINT;
        }

        return $flags;
    }
}
