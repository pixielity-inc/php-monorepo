<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Policy Ability Enum.
 *
 * Defines standard policy method names used across all resource policies.
 * These abilities map to controller actions and are used with the #[Authorize] attribute.
 *
 * ## Standard CRUD Abilities:
 * - viewAny: List/index all resources
 * - view: View a specific resource
 * - create: Create a new resource
 * - update: Update an existing resource
 * - delete: Delete a resource
 * - restore: Restore a soft-deleted resource
 * - forceDelete: Permanently delete a resource
 *
 * ## Custom Abilities:
 * - manage: General management permission
 * - attach: Attach related resources
 * - detach: Detach related resources
 *
 * ## Usage with #[Authorize] Attribute:
 *
 * ```php
 * use Pixielity\Foundation\Attributes\Auth\Authorize;
 * use Pixielity\Foundation\Enums\PolicyAbility;
 *
 * #[Authorize(PolicyAbility::VIEW(), Incident::class)]
 * public function show(Incident $incident): JsonResponse
 * {
 *     // Calls IncidentPolicy::view($user, $incident)
 * }
 *
 * #[Authorize(PolicyAbility::UPDATE(), Facility::class)]
 * public function update(Facility $facility): JsonResponse
 * {
 *     // Calls FacilityPolicy::update($user, $facility)
 * }
 * ```
 *
 * ## Controller Method Mapping:
 *
 * | Controller Method | Policy Ability | Constant Value |
 * |-------------------|----------------|----------------|
 * | index()           | viewAny()      | VIEW_ANY       |
 * | show()            | view()         | VIEW           |
 * | create()          | create()       | CREATE         |
 * | store()           | create()       | CREATE         |
 * | edit()            | update()       | UPDATE         |
 * | update()          | update()       | UPDATE         |
 * | destroy()         | delete()       | DELETE         |
 * | restore()         | restore()      | RESTORE        |
 *
 * ## Benefits:
 *
 * - Type-safe: IDE autocomplete and type checking
 * - Consistent: Same ability names across all policies
 * - Discoverable: Easy to see all available abilities
 * - Refactorable: Change ability names in one place
 *
 * @version 1.0.0
 *
 * @method static VIEW_ANY() Returns the VIEW_ANY enum instance
 * @method static VIEW() Returns the VIEW enum instance
 * @method static CREATE() Returns the CREATE enum instance
 * @method static UPDATE() Returns the UPDATE enum instance
 * @method static DELETE() Returns the DELETE enum instance
 * @method static RESTORE() Returns the RESTORE enum instance
 * @method static FORCE_DELETE() Returns the FORCE_DELETE enum instance
 * @method static MANAGE() Returns the MANAGE enum instance
 * @method static ATTACH() Returns the ATTACH enum instance
 * @method static DETACH() Returns the DETACH enum instance
 */
enum PolicyAbility: string
{
    use Enum;

    /**
     * View any resources (list/index).
     *
     * Used for: index(), list() methods
     * Policy method: viewAny(User $user): bool
     */
    #[Label('View Any')]
    #[Description('View any resources (list/index). Used for index(), list() methods.')]
    case VIEW_ANY = 'viewAny';

    /**
     * View a specific resource.
     *
     * Used for: show(), view() methods
     * Policy method: view(User $user, Model $model): bool
     */
    #[Label('View')]
    #[Description('View a specific resource. Used for show(), view() methods.')]
    case VIEW = 'view';

    /**
     * Create a new resource.
     *
     * Used for: create(), store() methods
     * Policy method: create(User $user): bool
     */
    #[Label('Create')]
    #[Description('Create a new resource. Used for create(), store() methods.')]
    case CREATE = 'create';

    /**
     * Update an existing resource.
     *
     * Used for: edit(), update() methods
     * Policy method: update(User $user, Model $model): bool
     */
    #[Label('Update')]
    #[Description('Update an existing resource. Used for edit(), update() methods.')]
    case UPDATE = 'update';

    /**
     * Delete a resource (soft delete).
     *
     * Used for: destroy(), delete() methods
     * Policy method: delete(User $user, Model $model): bool
     */
    #[Label('Delete')]
    #[Description('Delete a resource (soft delete). Used for destroy(), delete() methods.')]
    case DELETE = 'delete';

    /**
     * Restore a soft-deleted resource.
     *
     * Used for: restore() methods
     * Policy method: restore(User $user, Model $model): bool
     */
    #[Label('Restore')]
    #[Description('Restore a soft-deleted resource. Used for restore() methods.')]
    case RESTORE = 'restore';

    /**
     * Permanently delete a resource.
     *
     * Used for: forceDelete() methods
     * Policy method: forceDelete(User $user, Model $model): bool
     */
    #[Label('Force Delete')]
    #[Description('Permanently delete a resource. Used for forceDelete() methods.')]
    case FORCE_DELETE = 'forceDelete';

    /**
     * Manage a resource (general management permission).
     *
     * Used for: Various management operations
     * Policy method: manage(User $user, Model $model): bool
     *
     * Common in: Settings, System operations
     */
    #[Label('Manage')]
    #[Description('Manage a resource (general management permission). Used for various management operations.')]
    case MANAGE = 'manage';

    /**
     * Attach related resources.
     *
     * Used for: attach(), link() methods
     * Policy method: attach(User $user, Model $model): bool
     *
     * Common in: Many-to-many relationships
     */
    #[Label('Attach')]
    #[Description('Attach related resources. Used for attach(), link() methods in many-to-many relationships.')]
    case ATTACH = 'attach';

    /**
     * Detach related resources.
     *
     * Used for: detach(), unlink() methods
     * Policy method: detach(User $user, Model $model): bool
     *
     * Common in: Many-to-many relationships
     */
    #[Label('Detach')]
    #[Description('Detach related resources. Used for detach(), unlink() methods in many-to-many relationships.')]
    case DETACH = 'detach';
}
