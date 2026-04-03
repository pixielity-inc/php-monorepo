<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\WithTrashed as SpatieWithTrashed;

/**
 * Include soft-deleted models in route model binding.
 *
 * Extends Spatie's WithTrashed attribute to include soft-deleted models
 * when resolving route model bindings.
 *
 * ## Purpose:
 * - Include soft-deleted models in route resolution
 * - Access trashed records via routes
 * - Useful for admin panels and restore functionality
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\WithTrashed;
 * use Pixielity\Routing\Attributes\Get;
 * use Pixielity\Routing\Attributes\Post;
 *
 * class UserController
 * {
 *     #[Get('/users/{user}')]
 *     #[WithTrashed()]  // Include soft-deleted users
 *     public function show(User $user) { }
 *
 *     #[Post('/users/{user}/restore')]
 *     #[WithTrashed()]  // Can restore soft-deleted users
 *     public function restore(User $user) {
 *         $user->restore();
 *     }
 * }
 * ```
 *
 * ## Requirements:
 * - Model must use `Illuminate\Database\Eloquent\SoftDeletes` trait
 * - Route parameter name must match model binding
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class WithTrashed extends SpatieWithTrashed {}
