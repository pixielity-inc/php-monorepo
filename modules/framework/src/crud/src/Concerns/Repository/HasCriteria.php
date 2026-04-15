<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Repository;

use Illuminate\Support\Collection;
use Pixielity\Crud\Contracts\CriteriaInterface;

/**
 * HasCriteria Trait.
 *
 * Manages the criteria stack for repository queries. Criteria are
 * applied to the query builder during prepareQuery() and persist
 * across queries until explicitly removed or reset.
 *
 * Expects the host class to declare no additional properties — this
 * trait owns the criteria state entirely.
 *
 * @since 2.0.0
 */
trait HasCriteria
{
    /**
     * The collection of applied criteria.
     *
     * @var Collection<int, CriteriaInterface>
     */
    protected Collection $criteria;

    /**
     * Whether to skip criteria on the next query.
     */
    protected bool $skipCriteria = false;

    /**
     * Push a criteria onto the stack.
     *
     * @param  CriteriaInterface  $criteria  The criteria to push.
     */
    public function pushCriteria(CriteriaInterface $criteria): static
    {
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * Remove a criteria by class name.
     *
     * @param  class-string<CriteriaInterface>  $criteriaClass  The criteria class to remove.
     */
    public function popCriteria(string $criteriaClass): static
    {
        $this->criteria = $this->criteria->reject(
            fn (CriteriaInterface $c): bool => $c instanceof $criteriaClass
        )->values();

        return $this;
    }

    /**
     * Reset all criteria.
     */
    public function resetCriteria(): static
    {
        $this->criteria = new Collection;

        return $this;
    }

    /**
     * Skip criteria application for the next query.
     *
     * @param  bool  $skip  Whether to skip.
     */
    public function skipCriteria(bool $skip = true): static
    {
        $this->skipCriteria = $skip;

        return $this;
    }

    /**
     * Get all applied criteria.
     *
     * @return Collection<int, CriteriaInterface> The criteria collection.
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }
}
