# Pixielity AOP Engine

Attribute-driven Aspect-Oriented Programming for Laravel 13.

Intercept method calls with PHP attributes — zero boilerplate, build-time proxy
generation, Octane-safe.

## Built-in Interceptors

| Attribute                    | Purpose                           |
| ---------------------------- | --------------------------------- |
| `#[Cache(ttl: 300)]`         | Transparent method-level caching  |
| `#[Transaction]`             | Wrap in database transaction      |
| `#[Log(level: 'info')]`      | Log method calls                  |
| `#[Authorize('permission')]` | Permission check before execution |
| `#[Metric]`                  | Performance metrics               |
| `#[Before(class: ...)]`      | Custom before interceptor         |
| `#[After(class: ...)]`       | Custom after interceptor          |
| `#[Around(class: ...)]`      | Custom around interceptor         |

## Usage

```php
use Pixielity\Aop\Attributes\Cache;
use Pixielity\Aop\Attributes\Transaction;
use Pixielity\Aop\Attributes\Log;

class ProductRepository extends Repository
{
    #[Cache(ttl: 3600)]
    public function findBySlug(string $slug): ?Product
    {
        return $this->query()->where('slug', $slug)->first();
    }

    #[Transaction]
    #[Log]
    public function bulkUpdate(array $ids, array $data): int
    {
        // Wrapped in transaction, logged automatically
    }
}
```

## Commands

```bash
php artisan aop:cache    # Build interceptor map + generate proxies
php artisan aop:clear    # Clear cached map + proxies
php artisan aop:list     # List all registered interceptions
```
