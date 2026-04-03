<div align="center">

<img src="https://gitlab.com/pixielity/laravel-laravel/framework/routing/-/raw/main/.gitlab/banner.svg" alt="Routing" width="100%">

</div>

A modern, attribute-based routing system for Laravel inspired by NestJS decorators. Define routes using PHP 8+ attributes directly on your controller methods, eliminating the need for traditional route files.

## Features

- ✅ **Attribute-Based Routing**: Define routes using PHP 8+ attributes
- ✅ **NestJS-Inspired**: Familiar decorator pattern for developers coming from NestJS
- ✅ **Auto-Discovery**: Controllers are automatically discovered and registered
- ✅ **Type-Safe**: Full IDE autocomplete and type checking
- ✅ **Clean Controllers**: No route files needed, routes live with their handlers
- ✅ **Middleware Support**: Apply middleware via attributes
- ✅ **Route Constraints**: Built-in where clauses (UUID, ULID, Alpha, etc.)
- ✅ **RESTful Resources**: Resource and API resource attributes
- ✅ **Domain Routing**: Support for multi-tenant domain routing
- ✅ **OpenAPI Ready**: Attributes include metadata for API documentation

## Installation

The package is already installed as part of the monorepo. If installing separately:

```bash
composer require pixielity/laravel-routing
```

## Quick Start

### Basic Controller

```php
<?php

namespace App\Controllers;

use Pixielity\Routing\Attributes\Routing\Controller;
use Pixielity\Routing\Attributes\Routing\Get;
use Pixielity\Routing\Attributes\Routing\Post;
use Pixielity\Routing\Attributes\Routing\Put;
use Pixielity\Routing\Attributes\Routing\Delete;

#[AsController]
#[Prefix('api/v1/users')]
#[Middleware(['api', 'auth:sanctum'])]
class UserController extends BaseController
{
    #[Get('/', name: 'users.index')]
    public function index()
    {
        return User::paginate(15);
    }

    #[Get('/{id}', name: 'users.show')]
    #[WhereUuid('id')]
    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    #[Post('/', name: 'users.store')]
    public function store(StoreUserRequest $request)
    {
        return User::create($request->validated());
    }

    #[Put('/{id}', name: 'users.update')]
    public function update(string $id, UpdateUserRequest $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return $user;
    }

    #[Delete('/{id}', name: 'users.destroy')]
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return response()->noContent();
    }
}
```

## Available Attributes

### Routing Attributes

#### HTTP Method Attributes

| Attribute    | Purpose                 | Example                              |
| ------------ | ----------------------- | ------------------------------------ |
| `#[Get]`     | Handle GET requests     | `#[Get('/users')]`                   |
| `#[Post]`    | Handle POST requests    | `#[Post('/users')]`                  |
| `#[Put]`     | Handle PUT requests     | `#[Put('/users/{id}')]`              |
| `#[Patch]`   | Handle PATCH requests   | `#[Patch('/users/{id}')]`            |
| `#[Delete]`  | Handle DELETE requests  | `#[Delete('/users/{id}')]`           |
| `#[Options]` | Handle OPTIONS requests | `#[Options('/users')]`               |
| `#[Any]`     | Handle all HTTP methods | `#[Any('/webhook')]`                 |
| `#[Route]`   | Handle specific methods | `#[Route(['GET', 'POST'], '/form')]` |

#### Controller Attributes

| Attribute             | Purpose                  | Example                                 |
| --------------------- | ------------------------ | --------------------------------------- |
| `#[AsController]`     | Mark class as controller | `#[AsController]`                       |
| `#[Prefix]`           | Add route prefix         | `#[Prefix('api/v1')]`                   |
| `#[Middleware]`       | Apply middleware         | `#[Middleware(['auth'])]`               |
| `#[Group]`            | Group routes             | `#[Group('admin')]`                     |
| `#[Domain]`           | Domain routing           | `#[Domain('api.example.com')]`          |
| `#[DomainFromConfig]` | Domain from config       | `#[DomainFromConfig('app.api_domain')]` |

#### Resource Attributes

| Attribute        | Purpose                       | Example                   |
| ---------------- | ----------------------------- | ------------------------- |
| `#[Resource]`    | RESTful resource              | `#[Resource('posts')]`    |
| `#[ApiResource]` | API resource (no create/edit) | `#[ApiResource('posts')]` |

#### Route Constraints

| Attribute              | Purpose                 | Example                                       |
| ---------------------- | ----------------------- | --------------------------------------------- |
| `#[Where]`             | Custom regex constraint | `#[Where('id', '[0-9]+')]`                    |
| `#[WhereUuid]`         | UUID constraint         | `#[WhereUuid('id')]`                          |
| `#[WhereUlid]`         | ULID constraint         | `#[WhereUlid('id')]`                          |
| `#[WhereAlpha]`        | Alphabetic only         | `#[WhereAlpha('slug')]`                       |
| `#[WhereAlphaNumeric]` | Alphanumeric only       | `#[WhereAlphaNumeric('code')]`                |
| `#[WhereNumber]`       | Numeric only            | `#[WhereNumber('id')]`                        |
| `#[WhereIn]`           | Enum constraint         | `#[WhereIn('status', ['active', 'pending'])]` |

#### Advanced Attributes

| Attribute          | Purpose                  | Example                      |
| ------------------ | ------------------------ | ---------------------------- |
| `#[Defaults]`      | Default parameter values | `#[Defaults(['page' => 1])]` |
| `#[ScopeBindings]` | Scope route bindings     | `#[ScopeBindings]`           |
| `#[WithTrashed]`   | Include soft-deleted     | `#[WithTrashed]`             |
| `#[Fallback]`      | Fallback route           | `#[Fallback]`                |

### Middleware Attributes

| Attribute       | Purpose          | Example                               |
| --------------- | ---------------- | ------------------------------------- |
| `#[Middleware]` | Apply middleware | `#[Middleware(['auth', 'verified'])]` |

## Usage Examples

### RESTful API Controller

```php
#[AsController]
#[Prefix('api/v1/posts')]
#[Middleware(['api', 'auth:sanctum'])]
class PostController extends BaseController
{
    #[Get(
        uri: '/',
        name: 'posts.index',
        summary: 'List all posts',
        description: 'Returns a paginated list of posts',
        tags: ['Posts'],
        responseCode: 200
    )]
    public function index()
    {
        return Post::with('author')->paginate(15);
    }

    #[Get(
        uri: '/{id}',
        name: 'posts.show',
        summary: 'Get a specific post',
        tags: ['Posts']
    )]
    #[WhereUuid('id')]
    public function show(string $id)
    {
        return Post::with('author', 'comments')->findOrFail($id);
    }

    #[Post(
        uri: '/',
        name: 'posts.store',
        summary: 'Create a new post',
        tags: ['Posts'],
        requestSchema: StorePostRequest::class,
        responseCode: 201
    )]
    public function store(StorePostRequest $request)
    {
        $post = Post::create([
            ...$request->validated(),
            'author_id' => auth()->id(),
        ]);

        return response()->json($post, 201);
    }

    #[Put('/{id}', name: 'posts.update')]
    #[WhereUuid('id')]
    public function update(string $id, UpdatePostRequest $request)
    {
        $post = Post::findOrFail($id);
        $post->update($request->validated());
        return $post;
    }

    #[Delete('/{id}', name: 'posts.destroy')]
    #[WhereUuid('id')]
    public function destroy(string $id)
    {
        Post::findOrFail($id)->delete();
        return response()->noContent();
    }
}
```

### Route Constraints

```php
#[AsController]
#[Prefix('api/v1')]
class ProductController extends BaseController
{
    // UUID constraint
    #[Get('/products/{id}')]
    #[WhereUuid('id')]
    public function show(string $id) { }

    // ULID constraint
    #[Get('/orders/{id}')]
    #[WhereUlid('id')]
    public function showOrder(string $id) { }

    // Numeric constraint
    #[Get('/categories/{id}')]
    #[WhereNumber('id')]
    public function showCategory(int $id) { }

    // Enum constraint
    #[Get('/products/status/{status}')]
    #[WhereIn('status', ['active', 'pending', 'archived'])]
    public function byStatus(string $status) { }

    // Custom regex
    #[Get('/products/sku/{sku}')]
    #[Where('sku', '[A-Z]{3}-[0-9]{4}')]
    public function bySku(string $sku) { }
}
```

### Domain Routing

```php
// Static domain
#[AsController]
#[Domain('api.example.com')]
#[Prefix('v1')]
class ApiController extends BaseController
{
    #[Get('/status')]
    public function status() { }
}

// Domain from config
#[AsController]
#[DomainFromConfig('app.api_domain')]
#[Prefix('v1')]
class ConfigApiController extends BaseController
{
    #[Get('/health')]
    public function health() { }
}

// Multi-tenant domain
#[AsController]
#[Domain('{tenant}.example.com')]
class TenantController extends BaseController
{
    #[Get('/dashboard')]
    public function dashboard(string $tenant) { }
}
```

### Resource Routes

```php
// Full RESTful resource (7 routes)
#[AsController]
#[Resource('posts')]
class PostController extends BaseController
{
    public function index() { }
    public function create() { }
    public function store() { }
    public function show($id) { }
    public function edit($id) { }
    public function update($id) { }
    public function destroy($id) { }
}

// API resource (5 routes, no create/edit)
#[AsController]
#[ApiResource('posts')]
class PostApiController extends BaseController
{
    public function index() { }
    public function store() { }
    public function show($id) { }
    public function update($id) { }
    public function destroy($id) { }
}
```

### Middleware Stacking

```php
#[AsController]
#[Prefix('admin')]
#[Middleware(['web', 'auth'])]  // Applied to all routes
class AdminController extends BaseController
{
    #[Get('/dashboard')]
    public function dashboard() { }

    #[Get('/users')]
    #[Middleware(['can:view-users'])]  // Additional middleware
    public function users() { }

    #[Post('/settings')]
    #[Middleware(['can:edit-settings', 'throttle:10,1'])]
    public function updateSettings() { }
}
```

### Route Groups

```php
#[AsController]
#[Group('api')]
#[Prefix('v1')]
class ApiController extends BaseController
{
    #[Get('/users')]
    public function users() { }

    #[Get('/posts')]
    public function posts() { }
}
```

### Default Parameters

```php
#[AsController]
#[Prefix('api/v1/search')]
class SearchController extends BaseController
{
    #[Get('/')]
    #[Defaults(['page' => 1, 'per_page' => 15, 'sort' => 'created_at'])]
    public function search(int $page, int $per_page, string $sort)
    {
        // $page defaults to 1 if not provided
        // $per_page defaults to 15 if not provided
        // $sort defaults to 'created_at' if not provided
    }
}
```

### Soft Deletes

```php
#[AsController]
#[Prefix('api/v1/posts')]
class PostController extends BaseController
{
    #[Get('/{id}')]
    #[WithTrashed]  // Include soft-deleted posts
    public function show(string $id)
    {
        return Post::withTrashed()->findOrFail($id);
    }
}
```

### Fallback Routes

```php
#[AsController]
class FallbackController extends BaseController
{
    #[Get('/')]
    #[Fallback]
    public function notFound()
    {
        return response()->json([
            'message' => 'Route not found'
        ], 404);
    }
}
```

## OpenAPI Integration

Attributes include metadata for automatic OpenAPI documentation generation:

```php
#[Get(
    uri: '/users/{id}',
    name: 'users.show',
    summary: 'Get user by ID',
    description: 'Returns a single user by their unique identifier',
    tags: ['Users'],
    parameters: [
        ['name' => 'id', 'in' => 'path', 'type' => 'string', 'format' => 'uuid', 'required' => true]
    ],
    responseCode: 200,
    responseSchema: UserResource::class
)]
public function show(string $id) { }
```

## Architecture

### BaseController

The package provides a `BaseController` class that extends Laravel's base controller:

```php
use Pixielity\Routing\BaseController;

class UserController extends BaseController
{
    // Your controller methods
}
```

### Auto-Discovery

Controllers with the `#[AsController]` attribute are automatically discovered and registered. No need to manually register routes in route files.

### Service Provider

The `RoutingServiceProvider` handles:

- Controller discovery
- Route registration
- Attribute processing
- Middleware application

## Comparison with Traditional Routing

### Traditional Route File

```php
// routes/api.php
Route::prefix('api/v1')->middleware(['api', 'auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show')->whereUuid('id');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update')->whereUuid('id');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy')->whereUuid('id');
});
```

### Attribute-Based Routing

```php
#[AsController]
#[Prefix('api/v1')]
#[Middleware(['api', 'auth:sanctum'])]
class UserController extends BaseController
{
    #[Get('/', name: 'users.index')]
    public function index() { }

    #[Get('/{id}', name: 'users.show')]
    #[WhereUuid('id')]
    public function show(string $id) { }

    #[Post('/', name: 'users.store')]
    public function store(StoreUserRequest $request) { }

    #[Put('/{id}', name: 'users.update')]
    #[WhereUuid('id')]
    public function update(string $id, UpdateUserRequest $request) { }

    #[Delete('/{id}', name: 'users.destroy')]
    #[WhereUuid('id')]
    public function destroy(string $id) { }
}
```

## Benefits

### 1. **Colocation**

Routes live with their handlers, making it easier to understand what a controller does.

### 2. **Type Safety**

Full IDE autocomplete and type checking for route parameters.

### 3. **Less Boilerplate**

No need to maintain separate route files.

### 4. **Better Refactoring**

Moving or renaming controllers automatically updates routes.

### 5. **Self-Documenting**

Routes are documented directly in the controller.

### 6. **OpenAPI Ready**

Metadata for automatic API documentation generation.

## Testing

```php
use Pixielity\Routing\Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_can_list_users()
    {
        $response = $this->get('/api/v1/users');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email']
                ]
            ]);
    }
}
```

## Configuration

The package uses Laravel's default routing configuration. No additional configuration needed.

## Migration Guide

### From Traditional Routes

1. Add `#[AsController]` attribute to your controller class
2. Add HTTP method attributes to your methods
3. Move middleware, prefix, and other route options to class-level attributes
4. Remove routes from `routes/api.php` or `routes/web.php`

### Example Migration

**Before:**

```php
// routes/api.php
Route::middleware(['api', 'auth'])->prefix('api/v1')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
});

// PostController.php
class PostController extends Controller
{
    public function index() { }
}
```

**After:**

```php
// PostController.php
#[AsController]
#[Prefix('api/v1')]
#[Middleware(['api', 'auth'])]
class PostController extends BaseController
{
    #[Get('/posts')]
    public function index() { }
}
```

## Best Practices

1. **Use Named Routes**: Always provide route names for easier URL generation
2. **Apply Constraints**: Use `WhereUuid`, `WhereNumber`, etc. for type safety
3. **Group Middleware**: Apply common middleware at the class level
4. **Document Routes**: Use summary and description for OpenAPI generation
5. **Keep Controllers Focused**: One resource per controller

## Credits

Inspired by:

- [NestJS Decorators](https://docs.nestjs.com/controllers)
- [Spatie Laravel Route Attributes](https://github.com/spatie/laravel-route-attributes)

## License

MIT
