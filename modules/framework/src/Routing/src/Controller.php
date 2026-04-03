<?php

declare(strict_types=1);

namespace Pixielity\Routing;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Support\Traits\Macroable;
use Pixielity\Routing\Concerns\InteractsWithAuth;
use Pixielity\Routing\Concerns\InteractsWithBulkOperations;
use Pixielity\Routing\Concerns\InteractsWithPagination;
use Pixielity\Routing\Concerns\InteractsWithRequest;
use Pixielity\Routing\Concerns\InteractsWithResources;
use Pixielity\Routing\Concerns\InteractsWithResponse;
use Pixielity\Routing\Concerns\InteractsWithServices;

/**
 * Base HTTP Controller.
 *
 * Clean, modern base controller for all application controllers.
 * Provides essential functionality without state management issues.
 *
 * ## Features:
 * - ✅ Request helpers (query, input, header, user, etc.)
 * - ✅ Response helpers (ok, created, notFound, etc.)
 * - ✅ Auth helpers (user, hasRole, hasPermission, etc.)
 * - ✅ Pagination helpers (paginate, paginatedResponse, etc.)
 * - ✅ Resource transformation (resource, collection, etc.)
 * - ✅ Bulk operations (bulkCreated, bulkUpdated, bulkDeleted, etc.)
 * - ✅ Authorization (AuthorizesRequests)
 * - ✅ Validation (ValidatesRequests)
 * - ✅ Macroable (extend with custom methods)
 * - ✅ Octane-safe (no mutable state)
 *
 * ## Architecture:
 * - Uses Response facade (no state)
 * - Clean trait composition
 * - No circular dependencies
 * - Easy to test
 * - Production-ready
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\BaseController;
 * use Pixielity\Crud\Attributes\UseService;
 * use Pixielity\Users\Contracts\UserService;
 *
 * #[UseService(UserServiceInterface::class)]
 * class UserController extends BaseController
 * {
 *     public function index()
 *     {
 *         $page = $this->query('page', 1);
 *         $users = $this->service()->paginate(15);
 *
 *         return $this->ok($users, 'Users retrieved successfully');
 *     }
 *
 *     public function show($id)
 *     {
 *         $user = $this->service()->find($id);
 *
 *         if (!$user) {
 *             return $this->notFound('User not found');
 *         }
 *
 *         return $this->ok($user);
 *     }
 *
 *     public function store(Request $request)
 *     {
 *         $validated = $request->validate([
 *             'name' => 'required|string',
 *             'email' => 'required|email|unique:users',
 *         ]);
 *
 *         $user = $this->service()->create($validated);
 *
 *         return $this->created($user, 'User created successfully');
 *     }
 *
 *     public function destroy($id)
 *     {
 *         $this->service()->delete($id);
 *
 *         return $this->noContent();
 *     }
 * }
 * ```
 *
 * ## Advanced Usage:
 * ```php
 * // With HATEOAS links
 * use Pixielity\Response\Facades\Response;
 *
 * public function show($id)
 * {
 *     $user = User::find($id);
 *
 *     return Response::ok($user)
 *         ->addLink('self', route('users.show', $id))
 *         ->addLink('update', route('users.update', $id), 'PUT')
 *         ->addLink('delete', route('users.destroy', $id), 'DELETE')
 *         ->toJsonResponse();
 * }
 *
 * // With custom headers
 * public function show($id)
 * {
 *     $user = User::find($id);
 *
 *     return Response::ok($user)
 *         ->withHeader('X-Custom-Header', 'value')
 *         ->withETag($user->updated_at->timestamp)
 *         ->toJsonResponse();
 * }
 *
 * // With metrics (debug mode)
 * public function index()
 * {
 *     $users = User::all();
 *
 *     return Response::ok($users)
 *         ->withMetrics()
 *         ->toJsonResponse();
 * }
 * ```
 *
 * @category   Abstracts
 *
 * @since      2.0.0
 */
abstract class Controller extends LaravelController
{
    /**
     * Custom interaction traits for request/response/auth handling.
     *
     * These traits provide clean, fluent methods for common controller operations:
     * - InteractsWithAuth: User authentication and authorization helpers
     * - InteractsWithRequest: Request data access helpers
     * - InteractsWithResponse: HTTP response builders using Response facade
     * - InteractsWithPagination: Pagination helpers for API responses
     * - InteractsWithValidation: Validation shortcuts
     * - InteractsWithResources: API resource transformation
     * - InteractsWithBulkOperations: Bulk create/update/delete operations
     * - InteractsWithServices: Service resolution and access
     */
    use InteractsWithAuth;

    use InteractsWithBulkOperations;
    use InteractsWithPagination;
    use InteractsWithRequest;
    use InteractsWithResources;
    use InteractsWithResponse;
    use InteractsWithServices;

    /**
     * Laravel's built-in traits for authorization and validation.
     *
     * - Macroable: Enables method extension through macros
     * - ValidatesRequests: Request validation helpers
     */
    use Macroable;

    use ValidatesRequests;
}
