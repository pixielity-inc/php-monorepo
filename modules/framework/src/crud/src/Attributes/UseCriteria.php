<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * UseCriteria Attribute for Repository Classes.
 *
 * Automatically applies one or more criteria to a repository when it's instantiated.
 * This is useful for repositories that should always have certain filters applied.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Attributes\UseCriteria;
 * use Pixielity\Users\Criteria\ActiveCriteria;
 * use Pixielity\Users\Criteria\VerifiedCriteria;
 *
 * #[UseCriteria([ActiveCriteria::class, VerifiedCriteria::class])]
 * class UserRepository extends Repository implements UserRepositoryInterface
 * {
 *     // ActiveCriteria and VerifiedCriteria are automatically applied
 *     // All queries will filter for active AND verified users
 * }
 * ```
 *
 * ## How it works:
 * 1. Repository boot method reads the #[UseCriteria] attribute
 * 2. Each criteria class is instantiated
 * 3. Criteria are pushed to the repository's criteria collection
 * 4. Applied automatically to all queries
 *
 * ## Single Criteria:
 * ```php
 * #[UseCriteria(ActiveCriteria::class)]
 * class UserRepository extends Repository
 * {
 *     // Only ActiveCriteria is applied
 * }
 * ```
 *
 * ## Criteria with Parameters:
 * For criteria that need constructor parameters, use the array syntax:
 *
 * ```php
 * #[UseCriteria([
 *     ActiveCriteria::class,
 *     [StatusCriteria::class, ['status' => 'published']],
 *     [OrderByCriteria::class, ['field' => 'created_at', 'direction' => 'desc']]
 * ])]
 * class PostRepository extends Repository
 * {
 *     // Criteria with parameters are instantiated with those parameters
 * }
 * ```
 *
 * ## Skip Criteria:
 * You can temporarily skip criteria using the repository method:
 *
 * ```php
 * // Skip all criteria for this query
 * $allUsers = $repository->skipCriteria()->all();
 *
 * // Skip specific criteria
 * $repository->popCriteria(ActiveCriteria::class);
 * ```
 *
 * ## Benefits:
 * - Default filters applied automatically
 * - Consistent query behavior
 * - Reduces boilerplate
 * - Easy to override when needed
 * - Self-documenting repository behavior
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UseCriteria
{
    /**
     * @var array<class-string|array>
     */
    public array $criteria;

    /**
     * Create a new UseCriteria attribute instance.
     *
     * @param  class-string|array<class-string|array>  $criteria  Single criteria class or array of criteria classes
     */
    public function __construct(
        string|array $criteria,
    ) {
        $this->criteria = is_string($criteria) ? [$criteria] : $criteria;
    }
}
