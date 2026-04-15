<?php

declare(strict_types=1);

/**
 * ViolationReport Repository Interface.
 *
 * Defines the contract for the ViolationReportRepository with query operations.
 * Bound via #[Bind] attribute for automatic container registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Support\Collection;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Developer\Repositories\ViolationReportRepository;

/**
 * Contract for the ViolationReportRepository.
 */
#[Bind(ViolationReportRepository::class)]
#[Singleton]
interface ViolationReportRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all violation reports for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all unresolved violation reports.
     *
     * @return Collection
     */
    public function findUnresolved(): Collection;

    /**
     * Find all violation reports by a given reporter.
     *
     * @param  int|string  $reporterId  The reporter identifier.
     * @return Collection
     */
    public function findByReporter(int|string $reporterId): Collection;
}
