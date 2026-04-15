<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * UseScope Attribute for Repository Classes.
 *
 * Automatically applies global scopes to the model via addGlobalScope().
 * Global scopes are applied at the model level and affect ALL queries.
 *
 * For query-level scopes (scopeQuery), use #[UseQueryScope] instead.
 *
 * ## Difference: Global Scope vs Query Scope
 *
 * ### Global Scope (#[UseScope])
 * - Applied via `$model->addGlobalScope()`
 * - Affects ALL queries on the model
 * - Cannot be easily bypassed
 * - Perfect for security, multi-tenancy, soft deletes
 *
 * ### Query Scope (#[UseQueryScope])
 * - Applied via `scopeQuery()`
 * - Affects only repository queries
 * - Can be bypassed with `skipCriteria()`
 * - Perfect for filtering, sorting, default conditions
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Attributes\UseScope;
 * use App\Scopes\TenantScope;
 * use App\Scopes\ExcludeArchivedScope;
 *
 * #[UseScope(TenantScope::class)]
 * #[UseScope(ExcludeArchivedScope::class)]
 * class UserRepository extends Repository
 * {
 *     // Global scopes automatically applied to model
 *     // ALL queries will be filtered by tenant and exclude archived
 * }
 * ```
 *
 * ## Example Global Scope Class:
 * ```php
 * use Illuminate\Database\Eloquent\Builder;
 * use Illuminate\Database\Eloquent\Model;
 * use Illuminate\Database\Eloquent\Scope;
 *
 * class TenantScope implements Scope
 * {
 *     public function apply(Builder $builder, Model $model): void
 *     {
 *         $tenantId = auth()->user()?->tenant_id;
 *
 *         if ($tenantId) {
 *             $builder->where($model->getTable() . '.tenant_id', $tenantId);
 *         }
 *     }
 * }
 * ```
 *
 * ## How It Works:
 * 1. Repository boot reads all #[UseScope] attributes
 * 2. For each scope class, instantiates the scope
 * 3. Calls `$model->addGlobalScope(new ScopeClass())`
 * 4. Scope is applied to ALL queries on that model
 *
 * ## Multiple Scopes:
 * ```php
 * #[UseScope(TenantScope::class)]
 * #[UseScope(ExcludeArchivedScope::class)]
 * #[UseScope(ActiveOnlyScope::class)]
 * class PostRepository extends Repository
 * {
 *     // All three scopes applied globally
 * }
 * ```
 *
 * ## Benefits:
 * - ✅ Security (cannot be bypassed easily)
 * - ✅ Consistency (applied everywhere)
 * - ✅ Declarative (clear intent)
 * - ✅ Reusable (scope classes)
 * - ✅ Testable (scope classes)
 *
 * ## Use Cases:
 * - Multi-tenancy (tenant isolation)
 * - Soft deletes (exclude deleted)
 * - Security (row-level permissions)
 * - Status filters (active/published only)
 * - Archived records (exclude archived)
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class UseScope
{
    /**
     * @var array<class-string>
     */
    public array $scopes;

    /**
     * Create a new UseScope attribute instance.
     *
     * @param  class-string|array<class-string>  $scopes  Single scope class or array of scope classes
     */
    public function __construct(
        string|array $scopes,
    ) {
        $this->scopes = is_string($scopes) ? [$scopes] : $scopes;
    }
}
