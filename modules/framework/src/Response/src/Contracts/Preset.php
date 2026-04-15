<?php

declare(strict_types=1);

namespace Pixielity\Contracts\Framework\Response;

/**
 * Contract for response presets.
 *
 * A Preset provides pre-configured defaults for a specific client type
 * (API consumers, admin dashboards, mobile apps). The ResponseFactory
 * applies presets via api(), admin(), mobile() methods.
 *
 * Presets are auto-discovered via #[AsPreset] and registered as singletons
 * (they hold no mutable request state — only configuration).
 *
 * Built-in presets:
 *   - ApiPreset:    JSON, strict security headers, API versioning, compact output
 *   - AdminPreset:  JSON, relaxed headers, debug on in non-prod, pretty-printed
 *   - MobilePreset: JSON, minimal headers, 5min cache, compact output, no debug
 *
 * @see \Pixielity\Response\Attributes\AsPreset Discovery attribute.
 */
interface Preset
{
    /**
     * Get the unique preset identifier.
     *
     * @return string Preset name (e.g., 'api', 'admin', 'mobile').
     */
    public function getName(): string;

    /**
     * Get the default renderer class for this preset.
     *
     * @return class-string<Renderer> Fully-qualified renderer class name.
     */
    public function getDefaultRenderer(): string;

    /**
     * Get default HTTP headers added to every response using this preset.
     *
     * @return array<string, string> Header name-value pairs.
     */
    public function getDefaultHeaders(): array;

    /**
     * Get default metadata merged into the 'meta' section of every response.
     *
     * @return array<string, mixed> Metadata key-value pairs.
     */
    public function getDefaultMeta(): array;

    /**
     * Get the API version string for this preset.
     *
     * @return string|null API version (e.g., 'v1') or null if not applicable.
     */
    public function getApiVersion(): ?string;

    /**
     * Check if debug info should be included in responses.
     *
     * @return bool True if debug section should be included.
     */
    public function isDebug(): bool;

    /**
     * Get JSON encoding flags for this preset.
     *
     * @return int Bitmask of JSON_* constants.
     */
    public function getJsonFlags(): int;
}
