<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * AsCriteria Attribute for Criteria Classes.
 *
 * Marks a class as a Criteria that can be discovered and registered
 * automatically. Criteria are reusable query filters that can be applied
 * to repositories.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Attributes\AsCriteria;
 * use Pixielity\Crud\Contracts\CriteriaInterface;
 * use Pixielity\Crud\Contracts\RepositoryInterface;
 *
 * // Global criteria - applied to ALL repositories automatically
 * #[AsCriteria(
 *     name: 'active',
 *     description: 'Filter only active records',
 *     tags: ['common', 'status'],
 *     global: true
 * )]
 * class ActiveCriteria implements CriteriaInterface
 * {
 *     public function apply($model, RepositoryInterface $repository)
 *     {
 *         return $model->where('status', 'active');
 *     }
 * }
 *
 * // Non-global criteria - applied manually via pushCriteria()
 * #[AsCriteria(
 *     name: 'recent',
 *     description: 'Filter recent records',
 *     tags: ['filter']
 * )]
 * class RecentCriteria implements CriteriaInterface
 * {
 *     public function apply($model, RepositoryInterface $repository)
 *     {
 *         return $model->where('created_at', '>=', now()->subDays(7));
 *     }
 * }
 * ```
 *
 * ## How it works:
 * 1. HasDiscovery trait scans for classes with #[AsCriteria] attribute
 * 2. Criteria are registered in a central registry
 * 3. Can be applied by name: `$repository->pushCriteria('active')`
 * 4. Can be discovered by tags: `CriteriaRegistry::findByTag('common')`
 *
 * ## Benefits:
 * - Reusable query logic
 * - Discoverable criteria
 * - Composable filters
 * - Testable in isolation
 * - Self-documenting
 *
 * ## Example with Parameters:
 * ```php
 * #[AsCriteria(
 *     name: 'status',
 *     description: 'Filter by status',
 *     tags: ['filter']
 * )]
 * class StatusCriteria implements CriteriaInterface
 * {
 *     public function __construct(
 *         private string $status
 *     ) {}
 *
 *     public function apply($model, RepositoryInterface $repository)
 *     {
 *         return $model->where('status', $this->status);
 *     }
 * }
 *
 * // Usage
 * $repository->pushCriteria(new StatusCriteria('active'));
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class AsCriteria
{
    /**
     * Create a new Criteria attribute instance.
     *
     * @param  string  $name  Unique name for the criteria
     * @param  string|null  $description  Human-readable description
     * @param  array<string>  $tags  Tags for categorization and discovery
     * @param  bool  $global  Apply to ALL repositories automatically (default: false)
     */
    public function __construct(
        public string $name,
        public ?string $description = null,
        public array $tags = [],
        public bool $global = false,
    ) {}
}
