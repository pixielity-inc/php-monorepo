<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;
use Closure;

/**
 * UseQueryScope Attribute for Repository Classes.
 *
 * Automatically applies query scopes to a repository via scopeQuery() method.
 * This is for query-level modifications (filtering, sorting, etc.).
 *
 * For global model scopes (addGlobalScope), use #[UseScope] instead.
 *
 * ## Mode 1: Named Model Scopes
 * ```php
 * use Pixielity\Crud\Attributes\UseQueryScope;
 *
 * #[UseQueryScope('active')]
 * #[UseQueryScope('verified')]
 * class UserRepository extends Repository
 * {
 *     // Applies: $query->active()->verified()
 * }
 * ```
 *
 * ## Mode 2: Callable Reference (Recommended)
 * ```php
 * #[UseScope(callable: [UserScopes::class, 'activeAndVerified'])]
 * class UserRepository extends Repository
 * {
 *     // Applies: UserScopes::activeAndVerified($query)
 * }
 * ```
 *
 * Scope class:
 * ```php
 * class UserScopes
 * {
 *     public static function activeAndVerified($query)
 *     {
 *         return $query->where('status', 'active')
 *                      ->whereNotNull('email_verified_at');
 *     }
 *
 *     public static function ofType($query, string $type)
 *     {
 *         return $query->where('type', $type);
 *     }
 * }
 * ```
 *
 * ## Mode 3: Inline Closure
 * ```php
 * #[UseScope(
 *     callable: fn($query) => $query->where('status', 'active')
 *                                   ->orderBy('created_at', 'desc')
 * )]
 * class PostRepository extends Repository
 * {
 *     // Applies the closure directly
 * }
 * ```
 *
 * ## With Parameters
 * ```php
 * #[UseScope(
 *     callable: [PostScopes::class, 'ofType'],
 *     parameters: ['article']
 * )]
 * class PostRepository extends Repository
 * {
 *     // Applies: PostScopes::ofType($query, 'article')
 * }
 * ```
 *
 * ## Multiple Scopes (Stacked)
 * ```php
 * #[UseScope('active')]
 * #[UseScope(callable: [PostScopes::class, 'published'])]
 * #[UseScope(callable: fn($q) => $q->orderBy('created_at', 'desc'))]
 * class PostRepository extends Repository
 * {
 *     // All three applied in order
 * }
 * ```
 *
 * ## How it works:
 * 1. Repository boot reads all #[UseScope] attributes
 * 2. Scopes are applied via scopeQuery() method
 * 3. Callable references are resolved and executed
 * 4. Query builder is passed to each scope
 *
 * ## Benefits:
 * - ✅ Type-safe (QueryBuilder passed to callable)
 * - ✅ Reusable (scope classes can be shared)
 * - ✅ Testable (scope classes easy to unit test)
 * - ✅ Cacheable (callable references can be cached)
 * - ✅ IDE-friendly (autocomplete for scope classes)
 * - ✅ Declarative (clear intent via attributes)
 *
 * ## Example Scope Class (Recommended Pattern)
 * ```php
 * namespace App\Scopes;
 *
 * use Illuminate\Database\Eloquent\Builder;
 *
 * class PostScopes
 * {
 *     public static function active(Builder $query): Builder
 *     {
 *         return $query->where('status', 'active');
 *     }
 *
 *     public static function published(Builder $query): Builder
 *     {
 *         return $query->where('published_at', '<=', now());
 *     }
 *
 *     public static function ofType(Builder $query, string $type): Builder
 *     {
 *         return $query->where('type', $type);
 *     }
 *
 *     public static function recent(Builder $query, int $days = 7): Builder
 *     {
 *         return $query->where('created_at', '>=', now()->subDays($days));
 *     }
 * }
 * ```
 *
 * ## NestJS-Style Decorator Pattern
 * ```php
 * // Define reusable scopes
 * class CommonScopes
 * {
 *     public static function tenantScope(Builder $query): Builder
 *     {
 *         $tenantId = auth()->user()?->tenant_id;
 *         return $tenantId ? $query->where('tenant_id', $tenantId) : $query;
 *     }
 *
 *     public static function activeOnly(Builder $query): Builder
 *     {
 *         return $query->where('status', 'active');
 *     }
 * }
 *
 * // Apply to repository
 * #[UseScope(callable: [CommonScopes::class, 'tenantScope'])]
 * #[UseScope(callable: [CommonScopes::class, 'activeOnly'])]
 * class UserRepository extends Repository {}
 * ```
 *
 * ## Reset Scopes
 * ```php
 * // Skip all scopes for this query
 * $allUsers = $repository->skipCriteria()->all();
 *
 * // Or reset scope query
 * $repository->resetScope();
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class UseQueryScope
{
    /**
     * Create a new UseQueryScope attribute instance.
     *
     * @param  string|null  $name  Named model scope (e.g., 'active', 'published')
     * @param  Closure|array|string|null  $callable  Callable reference [Class::class, 'method'] or Closure
     * @param  array  $parameters  Parameters to pass to the callable
     */
    public function __construct(
        public ?string $name = null,
        public Closure|array|string|null $callable = null,
        public array $parameters = [],
    ) {}
}
