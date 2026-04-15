<?php

declare(strict_types=1);

namespace Pixielity\Response\Attributes;

use Attribute;

/**
 * Marks a class as a discoverable response Preset.
 *
 * When the Response service provider boots, it discovers all classes
 * annotated with #[AsPreset] and makes them available for resolution
 * by the ResponseFactory (api(), admin(), mobile() methods).
 *
 * The name parameter provides a human-readable identifier used for
 * config-based preset selection (e.g., config('response.default_preset')).
 *
 * Usage:
 *   #[AsPreset(name: 'api')]
 *   class ApiPreset implements Preset { ... }
 *
 * @see \Pixielity\Contracts\Framework\Response\Preset The contract presets implement.
 * @see \Pixielity\Response\Factories\ResponseFactory Where presets are used.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class AsPreset
{
    /**
     * Create a new AsPreset attribute instance.
     *
     * @param  string|null $name Human-readable preset name (e.g., 'api', 'admin', 'mobile').
     *                           If null, the class short name is used.
     */
    public function __construct(
        public ?string $name = null,
    ) {}
}
