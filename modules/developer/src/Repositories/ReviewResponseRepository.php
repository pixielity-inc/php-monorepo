<?php

declare(strict_types=1);

/**
 * ReviewResponse Repository.
 *
 * All query logic for the ReviewResponse model. Uses `$this->query()` for reads
 * and `$this->modelInstance->newQuery()` for writes.
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Repositories;

use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\Data\ReviewResponseInterface;
use Pixielity\Developer\Contracts\ReviewResponseRepositoryInterface;

/**
 * Repository for the ReviewResponse model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to ReviewResponseInterface (resolved to ReviewResponse model)
 *   - #[OrderBy]       → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(ReviewResponseInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class ReviewResponseRepository extends Repository implements ReviewResponseRepositoryInterface
{
    /**
     * Find all responses for a given review.
     *
     * @param  int|string  $reviewId  The review identifier.
     * @return Collection
     */
    public function findByReview(int|string $reviewId): Collection
    {
        return $this->query()
            ->where(ReviewResponseInterface::ATTR_APP_REVIEW_ID, $reviewId)
            ->get();
    }
}
