<?php

namespace Pixielity\Foundation\Abstracts;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Pixielity\Support\DataObject;

/**
 * Base Event Class.
 *
 * All module events should extend this base class.
 * Extends DataObject to provide magic getter/setter methods and dynamic data access.
 *
 * ## Features:
 * - Constructor property promotion (typed properties)
 * - Magic methods: get{Property}(), set{Property}(), has{Property}(), uns{Property}()
 * - Dot notation access: getData('user.name')
 * - Array access: toArray()
 * - Static factory: make()
 *
 * ## Usage Examples:
 *
 * ### Define Event with Constructor Property Promotion:
 * ```php
 * class SettingsSaving extends BaseEvent
 * {
 *     public function __construct(
 *         public string $group,
 *         public ?SettingsScope $scope,
 *         public array $properties
 *     ) {
 *         parent::__construct([
 *             'group' => $group,
 *             'scope' => $scope,
 *             'properties' => $properties,
 *         ]);
 *     }
 * }
 * ```
 *
 * ### Access Properties (Multiple Ways):
 * ```php
 * $event = new SettingsSaving('general', $scope, ['key' => 'value']);
 *
 * // 1. Direct property access (typed)
 * echo $event->group;  // 'general'
 *
 * // 2. Magic getter
 * echo $event->getGroup();  // 'general'
 *
 * // 3. DataObject getData()
 * echo $event->getData('group');  // 'general'
 *
 * // 4. Array access
 * $data = $event->toArray();  // ['group' => 'general', ...]
 * ```
 *
 * ### Factory Method:
 * ```php
 * // Clean instantiation
 * event(SettingsSaving::make('general', $scope, ['key' => 'value']));
 * ```
 *
 * ## Benefits:
 * - Type safety with constructor property promotion
 * - Flexibility with magic methods
 * - Backward compatibility with old code using getData()
 * - Consistent API across all events
 * - Easy serialization with toArray()
 */
abstract class BaseEvent extends DataObject
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
}
