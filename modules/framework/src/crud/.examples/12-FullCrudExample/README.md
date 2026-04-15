# Full CRUD Example: Product Module

A complete end-to-end example showing how to build a Product module using the
`pixielity/laravel-service-provider` and `pixielity/laravel-crud` packages.

## Architecture

```
Request → Controller → Service → Repository → Model → Database
                ↓           ↓          ↓
           Resource       Data     Criteria/Scopes
```

## Files in this example

| Layer      | File                                       | Purpose                                    |
| ---------- | ------------------------------------------ | ------------------------------------------ |
| Interface  | `Contracts/Data/ProductInterface.php`      | Model contract with ATTR\_\* constants     |
| Model      | `Models/Product.php`                       | Pure schema object (no query logic)        |
| Repository | `Contracts/ProductRepositoryInterface.php` | Repository contract                        |
| Repository | `Repositories/ProductRepository.php`       | All query logic, criteria, scopes, caching |
| Service    | `Contracts/ProductServiceInterface.php`    | Service contract                           |
| Service    | `Services/ProductService.php`              | Business logic, delegates to repository    |
| Resource   | `Resources/ProductResource.php`            | API JSON transformation                    |
| Controller | `Controllers/ProductController.php`        | HTTP layer, delegates to service           |
| Provider   | `Providers/ProductServiceProvider.php`     | Module wiring with attributes              |
| Migration  | `Migrations/create_products_table.php`     | Database schema                            |
| Config     | `config/config.php`                        | Module configuration                       |
| Routes     | `routes/api.php`                           | API route definitions                      |

## Key Patterns

- Model = pure schema object (no scopes, no filtering, no query logic)
- Repository = owns ALL query logic (scopes, criteria, caching, filtering)
- Service = business logic orchestration, delegates to repository
- Controller = HTTP layer, delegates to service
- All interfaces use ATTR\_\* constants (Magento 2 pattern)
- All interfaces use `#[Bind]` on the INTERFACE, not the implementation
- All attributes read from `composer-attribute-collector` cache — zero
  reflection
