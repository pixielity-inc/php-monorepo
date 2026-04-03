<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Interacts With Auth Trait.
 *
 * Provides convenient methods for accessing authenticated user information.
 *
 * ## Usage:
 * ```php
 * class UserController extends BaseController
 * {
 *     use InteractsWithAuth;
 *
 *     public function profile()
 *     {
 *         $user = $this->user();
 *
 *         if (!$user) {
 *             return $this->unauthorized('Not authenticated');
 *         }
 *
 *         return $this->ok($user);
 *     }
 *
 *     public function myPosts()
 *     {
 *         $userId = $this->userId();
 *
 *         $posts = Post::where('user_id', $userId)->get();
 *
 *         return $this->ok($posts);
 *     }
 * }
 * ```
 *
 * @method Authenticatable|null user(?string $guard = null) Get the authenticated user
 * @method int|string|null userId(?string $guard = null) Get the authenticated user's ID
 * @method bool isAuthenticated(?string $guard = null) Check if user is authenticated
 * @method bool isGuest(?string $guard = null) Check if user is a guest (not authenticated)
 * @method Authenticatable userOrFail(?string $guard = null) Get the authenticated user or fail
 * @method bool hasRole(string $role, ?string $guard = null) Check if authenticated user has a specific role
 * @method bool hasAnyRole(array $roles, ?string $guard = null) Check if authenticated user has any of the specified roles
 * @method bool hasAllRoles(array $roles, ?string $guard = null) Check if authenticated user has all of the specified roles
 * @method bool hasPermission(string $permission, ?string $guard = null) Check if authenticated user has a specific permission
 * @method bool hasAnyPermission(array $permissions, ?string $guard = null) Check if authenticated user has any of the specified permissions
 * @method bool hasAllPermissions(array $permissions, ?string $guard = null) Check if authenticated user has all of the specified permissions
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithAuth
{
    /**
     * Get the authenticated user.
     *
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function user(?string $guard = null): ?Authenticatable
    {
        return auth($guard)->user();
    }

    /**
     * Get the authenticated user's ID.
     *
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function userId(?string $guard = null): int|string|null
    {
        return auth($guard)->id();
    }

    /**
     * Check if user is authenticated.
     *
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function isAuthenticated(?string $guard = null): bool
    {
        return auth($guard)->check();
    }

    /**
     * Check if user is a guest (not authenticated).
     *
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function isGuest(?string $guard = null): bool
    {
        return auth($guard)->guest();
    }

    /**
     * Get the authenticated user or fail.
     *
     * Throws AuthenticationException if not authenticated.
     *
     * @param  string|null  $guard  Guard name (optional)
     *
     * @throws AuthenticationException
     */
    protected function userOrFail(?string $guard = null): Authenticatable
    {
        $authGuard = auth($guard);

        // Check if authenticate method exists (Laravel 8+)
        if (method_exists($authGuard, 'authenticate')) {
            /* @var mixed $authGuard */
            return $authGuard->authenticate();
        }

        // Fallback: manually check and throw exception
        $user = $authGuard->user();

        throw_unless($user, AuthenticationException::class, 'Unauthenticated.', [$guard]);

        return $user;
    }

    /**
     * Check if authenticated user has a specific role.
     *
     * Requires user model to have hasRole() method.
     *
     * @param  string  $role  Role name
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function hasRole(string $role, ?string $guard = null): bool
    {
        $user = $this->user($guard);

        if ($user !== null && method_exists($user, 'hasRole')) {
            /* @var mixed $user */
            return $user->hasRole($role);
        }

        return false;
    }

    /**
     * Check if authenticated user has any of the specified roles.
     *
     * Requires user model to have hasAnyRole() method.
     *
     * @param  array<string>  $roles  Role names
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function hasAnyRole(array $roles, ?string $guard = null): bool
    {
        $user = $this->user($guard);

        if ($user !== null && method_exists($user, 'hasAnyRole')) {
            /* @var mixed $user */
            return $user->hasAnyRole($roles);
        }

        return false;
    }

    /**
     * Check if authenticated user has all of the specified roles.
     *
     * Requires user model to have hasAllRoles() method.
     *
     * @param  array<string>  $roles  Role names
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function hasAllRoles(array $roles, ?string $guard = null): bool
    {
        $user = $this->user($guard);

        if ($user !== null && method_exists($user, 'hasAllRoles')) {
            /* @var mixed $user */
            return $user->hasAllRoles($roles);
        }

        return false;
    }

    /**
     * Check if authenticated user has a specific permission.
     *
     * Requires user model to have hasPermission() method.
     *
     * @param  string  $permission  Permission name
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function hasPermission(string $permission, ?string $guard = null): bool
    {
        $user = $this->user($guard);

        if ($user !== null && method_exists($user, 'hasPermission')) {
            /* @var mixed $user */
            return $user->hasPermission($permission);
        }

        return false;
    }

    /**
     * Check if authenticated user has any of the specified permissions.
     *
     * Requires user model to have hasAnyPermission() method.
     *
     * @param  array<string>  $permissions  Permission names
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function hasAnyPermission(array $permissions, ?string $guard = null): bool
    {
        $user = $this->user($guard);

        if ($user !== null && method_exists($user, 'hasAnyPermission')) {
            /* @var mixed $user */
            return $user->hasAnyPermission($permissions);
        }

        return false;
    }

    /**
     * Check if authenticated user has all of the specified permissions.
     *
     * Requires user model to have hasAllPermissions() method.
     *
     * @param  array<string>  $permissions  Permission names
     * @param  string|null  $guard  Guard name (optional)
     */
    protected function hasAllPermissions(array $permissions, ?string $guard = null): bool
    {
        $user = $this->user($guard);

        if ($user !== null && method_exists($user, 'hasAllPermissions')) {
            /* @var mixed $user */
            return $user->hasAllPermissions($permissions);
        }

        return false;
    }
}
