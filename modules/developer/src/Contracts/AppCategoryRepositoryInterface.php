<?php

declare(strict_types=1);

/**
 * AppCategory Repository Interface.
 *
 * Defines the contract for the AppCategoryRepository with query operations.
 * Bound via #[Bind] attribute for automatic container registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Singleton;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppCategoryInterface;
use Pixielity\Developer\Repositories\AppCategoryRepository;

/**
 * Contract for the AppCategoryRepository.
 */
#[Bind(AppCategoryRepository::class)]
#[Singleton]
interface AppCategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a category by its slug.
     *
     * @param  string  $slug  The unique slug identifier.
     * @return AppCategoryInterface|null The matching category or null.
     */
    public function findBySlug(string $slug): ?AppCategoryInterface;
}
