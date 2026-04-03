<?php

declare(strict_types=1);

namespace Pixielity\Routing\Providers;

use Override;
use Pixielity\Support\ServiceProvider;

/**
 * Routing Service Provider.
 *
 * Registers the Routing module with the Laravel application, providing
 * enhanced routing capabilities through attributes and a powerful BaseController
 * with helper traits for common controller operations.
 *
 * ## Features:
 * - **Route Attributes**: Extended route attributes for declarative routing
 * - **BaseController**: Enhanced base controller with helper traits
 * - **Controller Concerns**: Reusable traits for auth, requests, responses, pagination, validation, resources, and bulk operations
 * - **Type-Safe**: Full IDE autocomplete support
 * - **Octane-Safe**: No state leakage between requests
 *
 * ## Registered Services:
 * - **Route Attributes**: Extended Spatie route attributes
 *   - HTTP method attributes: Get, Post, Put, Patch, Delete, Options, Any
 *   - Route configuration: Prefix, Domain, Middleware, Where constraints
 *   - Resource routing: Resource, ApiResource
 *   - Controller/Middleware markers: AsController, AsMiddleware
 *
 * - **BaseController**: Enhanced controller base class
 *   - Uses InteractsWithAuth for authentication helpers
 *   - Uses InteractsWithRequest for request data access
 *   - Uses InteractsWithResponse for response building
 *   - Uses InteractsWithPagination for pagination helpers
 *   - Uses InteractsWithValidation for validation shortcuts
 *   - Uses InteractsWithResources for resource transformation
 *   - Uses InteractsWithBulkOperations for bulk operations
 *
 * ## Usage:
 *
 * ### Route Attributes:
 * ```php
 * use Pixielity\Routing\Attributes\Get;
 * use Pixielity\Routing\Attributes\Post;
 * use Pixielity\Routing\Attributes\Middleware;
 * use Pixielity\Routing\Attributes\Prefix;
 *
 * #[Prefix('api/v1')]
 * #[Middleware('auth:api')]
 * class UserController extends BaseController
 * {
 *     #[Get('users', name: 'users.index')]
 *     public function index()
 *     {
 *         return $this->ok(User::paginate());
 *     }
 *
 *     #[Post('users', name: 'users.store')]
 *     public function store(Request $request)
 *     {
 *         $user = User::create($request->validated());
 *         return $this->created($user, 'User created successfully');
 *     }
 * }
 * ```
 *
 * ### BaseController with Concerns:
 * ```php
 * use Pixielity\Routing\BaseController;
 *
 * class ProductController extends BaseController
 * {
 *     // Auth helpers (InteractsWithAuth)
 *     public function myProducts()
 *     {
 *         $user = $this->user(); // Get authenticated user
 *         return $this->ok($user->products);
 *     }
 *
 *     // Request helpers (InteractsWithRequest)
 *     public function search()
 *     {
 *         $query = $this->input('q'); // Get input
 *         $filters = $this->only(['category', 'price_min', 'price_max']);
 *         return $this->ok(Product::search($query, $filters));
 *     }
 *
 *     // Response helpers (InteractsWithResponse)
 *     public function show($id)
 *     {
 *         $product = Product::find($id);
 *
 *         if (!$product) {
 *             return $this->notFound('Product not found');
 *         }
 *
 *         return $this->ok($product);
 *     }
 *
 *     // Pagination helpers (InteractsWithPagination)
 *     public function index()
 *     {
 *         $perPage = $this->getPerPage(); // Get per_page from request
 *         $products = Product::paginate($perPage);
 *         return $this->paginatedResponse($products);
 *     }
 *
 *     // Validation helpers (InteractsWithValidation)
 *     public function store()
 *     {
 *         $data = $this->validate([
 *             'name' => 'required|string|max:255',
 *             'price' => 'required|numeric|min:0',
 *         ]);
 *
 *         $product = Product::create($data);
 *         return $this->created($product);
 *     }
 *
 *     // Resource transformation (InteractsWithResources)
 *     public function show($id)
 *     {
 *         $product = Product::findOrFail($id);
 *         return $this->resource($product, ProductResource::class);
 *     }
 *
 *     // Bulk operations (InteractsWithBulkOperations)
 *     public function bulkCreate()
 *     {
 *         $products = Product::insert($this->input('products'));
 *         return $this->bulkCreated($products, 'Products created');
 *     }
 * }
 * ```
 *
 * ### Advanced Response Building:
 * ```php
 * class OrderController extends BaseController
 * {
 *     public function show($id)
 *     {
 *         $order = Order::with('items')->findOrFail($id);
 *
 *         // Use response() for advanced chaining
 *         return $this->response()
 *             ->ok($order)
 *             ->withMessage('Order retrieved')
 *             ->withMeta(['total_items' => $order->items->count()])
 *             ->addLink('self', route('orders.show', $id))
 *             ->addLink('cancel', route('orders.cancel', $id), 'POST')
 *             ->when($order->canBeModified(), fn($r) =>
 *                 $r->addLink('update', route('orders.update', $id), 'PUT')
 *             )
 *             ->toJsonResponse();
 *     }
 * }
 * ```
 *
 * ## Available Route Attributes:
 *
 * ### HTTP Methods:
 * - `Get`, `Post`, `Put`, `Patch`, `Delete`, `Options`, `Any`
 *
 * ### Route Configuration:
 * - `Prefix`: Add URI prefix to routes
 * - `Domain`: Specify domain for routes
 * - `DomainFromConfig`: Load domain from config
 * - `Middleware`: Apply middleware to routes
 * - `Where`: Add route parameter constraints
 * - `WhereAlpha`, `WhereAlphaNumeric`, `WhereNumber`, `WhereUuid`, `WhereUlid`, `WhereIn`
 * - `Defaults`: Set default parameter values
 * - `ScopeBindings`: Enable route model binding scoping
 * - `WithTrashed`: Include soft-deleted models in route model binding
 *
 * ### Resource Routing:
 * - `Resource`: Full resource routes (index, create, store, show, edit, update, destroy)
 * - `ApiResource`: API resource routes (index, store, show, update, destroy)
 *
 * ### Markers:
 * - `AsController`: Mark class as controller
 * - `AsMiddleware`: Mark class as middleware
 * - `Group`: Group routes together
 * - `Fallback`: Define fallback route
 *
 * ## Controller Concerns:
 *
 * ### InteractsWithAuth:
 * - `user()`, `userOrFail()`, `userId()`, `guard()`, `attempt()`, `login()`, `logout()`
 * - `hasRole()`, `hasAnyRole()`, `hasAllRoles()`
 * - `hasPermission()`, `hasAnyPermission()`, `hasAllPermissions()`
 *
 * ### InteractsWithRequest:
 * - `input()`, `query()`, `post()`, `all()`, `only()`, `except()`
 * - `has()`, `filled()`, `missing()`, `whenHas()`, `whenFilled()`
 * - `file()`, `hasFile()`, `ip()`, `userAgent()`, `isJson()`, `wantsJson()`
 *
 * ### InteractsWithResponse:
 * - `ok()`, `created()`, `accepted()`, `noContent()`
 * - `badRequest()`, `unauthorized()`, `forbidden()`, `notFound()`
 * - `unprocessable()`, `serverError()`, `response()`
 *
 * ### InteractsWithPagination:
 * - `getPage()`, `getPerPage()`, `validatePagination()`, `paginate()`, `paginatedResponse()`
 *
 * ### InteractsWithValidation:
 * - `validate()`, `validateWith()`, `validateData()`, `validationError()`
 *
 * ### InteractsWithResources:
 * - `resource()`, `collection()`, `resourceWithMeta()`
 *
 * ### InteractsWithBulkOperations:
 * - `bulkCreated()`, `bulkUpdated()`, `bulkDeleted()`, `bulkOperation()`, `bulkPartialSuccess()`
 *
 * @category   Providers
 *
 * @since      2.0.0
 * @see        BaseController
 * @see        InteractsWithAuth
 * @see        InteractsWithRequest
 * @see        InteractsWithResponse
 * @see        InteractsWithPagination
 * @see        InteractsWithValidation
 * @see        InteractsWithResources
 * @see        InteractsWithBulkOperations
 */
class RoutingServiceProvider extends ServiceProvider
{
    /**
     * The module name.
     *
     * Used for:
     * - Identifying the module in logs and error messages
     * - Namespacing config: `config('routing.config_name')`
     */
    protected string $moduleName = 'Routing';

    /**
     * The module namespace.
     *
     * Used for:
     * - Auto-discovering services
     * - Resolving class names for dependency injection
     */
    protected string $moduleNamespace = 'Pixielity\Routing';

    /**
     * Bootstrap any application services.
     *
     * This method is called after all service providers have been registered.
     * It's the place to perform any actions that depend on other services
     * being available.
     *
     * ## What happens here:
     * - Configuration files are published (if any)
     * - Route attributes are registered via Spatie's package
     * - BaseController and concerns are made available
     */
    // #[Override]
    public function boot(): void
    {
        // Call parent boot to automatically load configuration
        // parent::boot();
    }

    /**
     * Register any application services.
     *
     * This method is called during the registration phase, before boot().
     * Use this to bind services into the container.
     *
     * ## What happens here:
     * - BaseController is made available for extension
     * - Controller concerns (traits) are auto-discovered
     * - Route attributes are registered
     * - Custom RouteRegistrar is automatically bound via #[Bind] attribute
     *
     * @see RouteRegistrar The #[Bind] attribute handles the binding
     */
    public function register(): void
    {
        // Call parent register for base functionality
        parent::register();
    }
}
