<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * AsScope Attribute for Scope Classes.
 *
 * Marks a class as a global scope for automatic discovery and registration.
 * Global scopes are applied at the model level via addGlobalScope() and
 * affect ALL queries on that model.
 *
 * ## Purpose:
 * - Automatic discovery via Discovery package
 * - Registration in ScopeRegistry
 * - Tag-based organization
 * - Metadata for documentation
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Attributes\AsScope;
 * use Illuminate\Database\Eloquent\Builder;
 * use Illuminate\Database\Eloquent\Model;
 * use Illuminate\Database\Eloquent\Scope;
 *
 * #[AsScope(
 *     name: 'tenant',
 *     description: 'Filter records by tenant',
 *     tags: ['security', 'multi-tenancy']
 * )]
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
 * ## Discovery:
 * Scopes are automatically discovered and registered during application boot:
 *
 * ```php
 * // In ServiceProvider
 * public function boot(): void
 * {
 *     $this->discoverScopes(); // Finds all #[AsScope] classes
 * }
 * ```
 *
 * ## Apply to Repository:
 * ```php
 * use Pixielity\Crud\Attributes\UseScope;
 *
 * #[UseScope(TenantScope::class)]
 * #[UseScope(ExcludeArchivedScope::class)]
 * class UserRepository extends Repository
 * {
 *     // Scopes automatically applied to model
 * }
 * ```
 *
 * ## Benefits:
 * - ✅ Automatic discovery (no manual registration)
 * - ✅ Tag-based organization
 * - ✅ Self-documenting code
 * - ✅ Reusable across repositories
 * - ✅ Testable in isolation
 *
 * ## Common Use Cases:
 * - Multi-tenancy (tenant isolation)
 * - Soft deletes (exclude deleted)
 * - Security (row-level permissions)
 * - Status filters (active/published only)
 * - Archived records (exclude archived)
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class AsScope
{
    /**
     * Create a new AsScope attribute instance.
     *
     * @param  string  $name  Unique name for the scope (e.g., 'tenant', 'active')
     * @param  string|null  $description  Human-readable description
     * @param  array<string>  $tags  Tags for categorization (e.g., ['security', 'multi-tenancy'])
     */
    public function __construct(
        public string $name,
        public ?string $description = null,
        public array $tags = [],
    ) {}
}
