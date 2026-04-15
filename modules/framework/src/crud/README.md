<div align="center">

<img src="https://gitlab.com/pixielity/laravel-laravel/framework/crud/-/raw/main/.gitlab/banner.svg" alt="Crud" width="100%">

</div>

## Overview

The CRUD module provides a complete service layer architecture that sits between
controllers and repositories, encapsulating business logic and providing a
consistent API across all modules.

## Installation

The CRUD package is automatically registered via Laravel's package discovery.
The `CrudServiceProvider` will:

- ✅ Automatically discover all criteria with `#[AsCriteria]` attribute
- ✅ Register them in the `CriteriaRegistry`
- ✅ Make them available throughout your application

No manual configuration needed!

## Architecture

```
Controller → Service (Business Logic) → Repository (Data Access) → Model → Database
```

### Components

1. **ServiceInterface** - Base interface defining common CRUD operations
2. **Service** - Abstract base class implementing ServiceInterface
3. **RepositoryInterface** - Base interface for data access
4. **Repository** - Abstract base class implementing RepositoryInterface
5. **CrudServiceProvider** - Auto-discovers and registers criteria
6. **CriteriaRegistry** - Central registry for all criteria

## Attributes

The CRUD package provides powerful attributes for declarative configuration:

- `#[UseModel]` - Define repository model
- `#[UseRepository]` - Define service repository
- `#[UseService]` - Define controller service
- `#[UseData]` - Define DTO class
- `#[UseResource]` - Define API resource
- `#[AsCriteria]` - Mark criteria for discovery
- `#[UseCriteria]` - Auto-apply criteria
- `#[UseScope]` - Auto-apply scopes

See [ATTRIBUTES.md](ATTRIBUTES.md) for complete documentation.

## Base Service Features

The `Service` provides these common methods out of the box:

### Basic CRUD Operations

- `all()` - Get all records
- `find($id)` - Find by ID
- `findOrFail($id)` - Find by ID or throw exception
- `findBy($field, $value)` - Find by field
- `findWhere($conditions)` - Find with multiple conditions
- `create($data)` - Create new record
- `update($id, $data)` - Update record
- `delete($id)` - Delete record

### Additional Operations

- `paginate($perPage)` - Paginate results
- `count()` - Count all records
- `exists($id)` - Check if record exists

## Creating a New Service

### Step 1: Create Service Interface

Create an interface that extends `ServiceInterface`:

```php
<?php

namespace Pixielity\YourModule\Contracts;

use Pixielity\Support\Str;
use Pixielityud\Contracts\ServiceInterface;
use PixielityurModule\Models\YourModel;

/**
 * @extends ServiceInterface<YourModel>
 */
interface YourServiceInterface extends ServiceInterface
{
    // Add custom methods specific to your module
    public function customMethod(YourModel $model): void;
}
```

### Step 2: Create Service Implementation

Create a service class that extends `Service`:

```php
<?php

namespace PixielityurModule\Services;

use Pixielitypport\Str;
use Pixielityud\Services\Service;
use PixielityurModule\Contracts\YourRepositoryInterface;
use PixielityurModule\Contracts\YourServiceInterface;
use PixielityurModule\Models\YourModel;

/**
 * @extends Service<YourModel>
 */
class YourService extends Service implements YourServiceInterface
{
    public function __construct(
        protected YourRepositoryInterface $repository
    ) {
        parent::__construct($repository);
    }

    // Implement custom methods
    public function customMethod(YourModel $model): void
    {
        // Your business logic here
        $this->update($model->id, ['status' => 'active']);
    }
}
```

### Step 3: Register in Service Provider

Bind the service in your module's service provider:

```php
protected function registerBindings(): void
{
    $this->app->singleton(
        YourServiceInterface::class,
        YourService::class
    );
}
```

### Step 4: Use in Controller

Inject the service into your controller:

```php
<?php

namespace PixielityurModule\Controllers;

use Pixielitypport\Str;
use PixielityurModule\Contracts\YourServiceInterface;
use Illuminate\Http\Request;

class YourController extends Controller
{
    public function __construct(
        private YourServiceInterface $service
    ) {}

    public function index()
    {
        return $this->service->paginate(15);
    }

    public function store(Request $request)
    {
        $model = $this->service->create($request->validated());
        return response()->json($model, 201);
    }

    public function update(Request $request, int $id)
    {
        $model = $this->service->update($id, $request->validated());
        return response()->json($model);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
```

## Working with Multiple Repositories

Services can orchestrate operations across multiple repositories:

```php
class UserService extends Service implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $repository,
        protected UserProfileRepositoryInterface $profileRepository,
        protected UserPreferenceRepositoryInterface $preferenceRepository
    ) {
        parent::__construct($repository);
    }

    public function createWithProfile(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Use base service method for main entity
            $user = $this->create($data);

            // Use additional repositories for related entities
            $this->profileRepository->create([
                'user_id' => $user->id,
                'bio' => $data['bio'] ?? null,
            ]);

            $this->preferenceRepository->create([
                'user_id' => $user->id,
                'theme' => 'light',
            ]);

            return $user;
        });
    }
}
```

## Best Practices

### 1. Always Define Interfaces

```php
// ✅ Good
interface UserServiceInterface extends ServiceInterface { }
class UserService extends Service implements UserServiceInterface { }

// ❌ Bad
class UserService extends Service { }
```

### 2. Keep Business Logic in Services

```php
// ✅ Good - Business logic in service
class UserService extends Service
{
    public function activateUser(int $userId): User
    {
        $user = $this->findOrFail($userId);

        if ($user->email_verified_at === null) {
            throw new Exception('Email must be verified first');
        }

        return $this->update($userId, ['status' => 'active']);
    }
}

// ❌ Bad - Business logic in controller
class UserController
{
    public function activate(int $id)
    {
        $user = User::findOrFail($id);
        if ($user->email_verified_at === null) {
            throw new Exception('Email must be verified first');
        }
        $user->update(['status' => 'active']);
    }
}
```

### 3. Use Transactions for Multi-Step Operations

```php
public function createWithRelations(array $data): Model
{
    return DB::transaction(function () use ($data) {
        $model = $this->create($data);
        $this->relatedRepository->create(['model_id' => $model->id]);
        return $model;
    });
}
```

### 4. Validate Before Repository Calls

```php
public function create(array $data): Model
{
    // Validate and transform data
    if (isset($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    }

    // Call repository
    return parent::create($data);
}
```

### 5. Return Models, Not Arrays

```php
// ✅ Good
public function getUser(int $id): User
{
    return $this->findOrFail($id);
}

// ❌ Bad
public function getUser(int $id): array
{
    return $this->findOrFail($id)->toArray();
}
```

## Testing Services

### Unit Testing with Mocks

```php
public function test_create_user()
{
    $mock = Mockery::mock(UserServiceInterface::class);
    $mock->shouldReceive('create')
        ->with(['email' => 'test@example.com'])
        ->andReturn(new User(['id' => 1, 'email' => 'test@example.com']));

    $this->app->instance(UserServiceInterface::class, $mock);

    $user = $this->app->make(UserServiceInterface::class)->create([
        'email' => 'test@example.com'
    ]);

    $this->assertEquals(1, $user->id);
}
```

### Integration Testing

```php
public function test_create_user_with_profile()
{
    $service = app(UserServiceInterface::class);

    $user = $service->createWithProfile([
        'email' => 'test@example.com',
        'password' => 'password',
        'bio' => 'Test bio',
    ]);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    $this->assertDatabaseHas('user_profiles', ['user_id' => $user->id]);
}
```

## Benefits

1. **Separation of Concerns** - Business logic separate from controllers and
   data access
2. **Reusability** - Common CRUD operations available out of the box
3. **Consistency** - Uniform API across all modules
4. **Testability** - Easy to mock and test
5. **Maintainability** - Centralized business rules
6. **Flexibility** - Easy to extend with custom methods
7. **Transaction Management** - Handle complex multi-step operations

## Migration Guide

If you have existing services that don't extend Service:

1. Make your service interface extend `ServiceInterface`
2. Make your service class extend `Service`
3. Pass the main repository to `parent::__construct()`
4. Remove duplicate CRUD methods that are now provided by Service
5. Rename any conflicting methods (e.g., `delete()` → `deleteUser()`)
6. Update method signatures to match base interface

## Example: Users Module

See `packages/Users/src/Services/UserService.php` for a complete example of:

- Extending Service
- Working with multiple repositories
- Custom business logic methods
- Transaction management
- Security event logging

## Criteria Discovery

The CRUD package automatically discovers and registers criteria classes marked
with the `#[AsCriteria]` attribute.

### Creating a Criteria

```php
<?php

namespace App\Criteria;

use Pixielityud\Attributes\AsCriteria;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

#[AsCriteria(
    name: 'active',
    description: 'Filter only active records',
    tags: ['common', 'status']
)]
class ActiveCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('status', 'active');
    }
}
```

### Using Discovered Criteria

```php
use Pixielityud\Registries\CriteriaRegistry;

// Get by name
$criteria = CriteriaRegistry::make('active');
$repository->pushCriteria($criteria);

// Find by tag
$commonCriteria = CriteriaRegistry::findByTag('common');

// Get all registered
$all = CriteriaRegistry::all();
```

### Auto-Apply Criteria

Use the `#[UseCriteria]` attribute to automatically apply criteria to
repositories:

```php
use Pixielityud\Attributes\UseCriteria;

#[UseCriteria([ActiveCriteria::class, VerifiedCriteria::class])]
class UserRepository extends Repository
{
    // Criteria automatically applied to all queries
}
```

### Auto-Apply Scopes

Use the `#[UseScope]` attribute to automatically apply query scopes:

```php
use Pixielityud\Attributes\UseScope;

#[UseScope('active')]
#[UseScope('orderBy', parameters: ['created_at', 'desc'])]
class UserRepository extends Repository
{
    // Scopes automatically applied to all queries
}
```

## Service Provider

The `CrudServiceProvider` is automatically registered and handles:

1. **Criteria Discovery** - Scans for `#[AsCriteria]` attributes
2. **Registry Population** - Registers discovered criteria
3. **Logging** - Logs discovery results in local environment

### Manual Registration

If you need to manually register the provider (not recommended):

```php
// config/app.php
'providers' => [
    // ...
    Pixielityud\CrudServiceProvider::class,
],
```

## Complete Example with Attributes

```php
// Model Interface
#[Bind(User::class)]
interface UserInterface {}

// Criteria
#[AsCriteria(name: 'active', tags: ['common'])]
class ActiveCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('status', 'active');
    }
}

// Repository
#[UseModel(UserInterface::class)]
#[UseCriteria(ActiveCriteria::class)]
#[UseScope('orderBy', parameters: ['created_at', 'desc'])]
class UserRepository extends Repository implements UserRepositoryInterface {}

// Service
#[UseRepository(UserRepositoryInterface::class)]
#[UseData(UserData::class)]
class UserService extends Service implements UserServiceInterface {}

// Controller
#[UseService(UserServiceInterface::class)]
#[UseResource(UserResource::class)]
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        // Everything is auto-wired and configured via attributes
        $users = $this->service->paginate(15);
        return UserResource::collection($users)->toResponse();
    }
}
```

## Documentation

- [ATTRIBUTES.md](ATTRIBUTES.md) - Complete attribute guide
- [Repository Pattern](https://github.com/andersao/l5-repository) - Prettus L5
  Repository docs

## Benefits

1. **Separation of Concerns** - Business logic separate from controllers and
   data access
2. **Reusability** - Common CRUD operations available out of the box
3. **Consistency** - Uniform API across all modules
4. **Testability** - Easy to mock and test
5. **Maintainability** - Centralized business rules
6. **Flexibility** - Easy to extend with custom methods
7. **Transaction Management** - Handle complex multi-step operations
8. **Declarative Configuration** - Attributes reduce boilerplate
9. **Automatic Discovery** - Criteria auto-registered
10. **Type Safety** - Full IDE support and type checking
