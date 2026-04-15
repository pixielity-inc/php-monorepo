<?php

declare(strict_types=1);

/**
 * Module Lifecycle Event Enum.
 *
 * Defines the lifecycle events fired during service provider registration
 * and boot phases. Each case maps to a Laravel event name that can be
 * listened to by other parts of the application for monitoring, logging,
 * or integration purposes.
 *
 * Events are fired with module context data (name, namespace, path) as
 * the event payload.
 *
 * @category Enums
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Lifecycle events for module service providers.
 *
 * Usage:
 *   Event::listen(ModuleLifecycleEvent::BOOTED->value, function (array $data) {
 *       // $data = ['module' => 'Tenancy', 'namespace' => '...', 'path' => '...']
 *   });
 */
enum ModuleLifecycleEvent: string
{
    use Enum;

    /**
     * Fired at the start of the register() phase.
     *
     * Listeners can use this to perform pre-registration setup or logging.
     */
    #[Label('Registering')]
    #[Description('Fired at the start of the register() phase before bindings are registered.')]
    case REGISTERING = 'module.registering';

    /**
     * Fired at the end of the register() phase.
     *
     * All container bindings for this module are now available.
     */
    #[Label('Registered')]
    #[Description('Fired at the end of the register() phase after all bindings are registered.')]
    case REGISTERED = 'module.registered';

    /**
     * Fired at the start of the boot() phase.
     *
     * Listeners can use this to perform pre-boot setup or logging.
     */
    #[Label('Booting')]
    #[Description('Fired at the start of the boot() phase before resources are loaded.')]
    case BOOTING = 'module.booting';

    /**
     * Fired at the end of the boot() phase.
     *
     * All resources, hooks, and discovery for this module are complete.
     */
    #[Label('Booted')]
    #[Description('Fired at the end of the boot() phase after all resources and hooks are registered.')]
    case BOOTED = 'module.booted';

    /**
     * Get all lifecycle events in execution order.
     *
     * @return array<self> The lifecycle events in order: REGISTERING, REGISTERED, BOOTING, BOOTED.
     */
    public static function inOrder(): array
    {
        return [
            self::REGISTERING,
            self::REGISTERED,
            self::BOOTING,
            self::BOOTED,
        ];
    }

    /**
     * Get the event name without the 'module.' prefix.
     *
     * @return string The short event name (e.g. 'booting', 'booted').
     */
    public function shortName(): string
    {
        return str_replace('module.', '', $this->value);
    }
}
