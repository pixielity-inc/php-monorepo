<?php

declare(strict_types=1);

/**
 * Audit Manager Interface.
 *
 * Unified audit API wrapping owen-it/laravel-auditing. Provides both
 * collection-based and paginated query methods.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Audit\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Pixielity\Audit\AuditManager;

/**
 * Contract for audit trail management.
 */
#[Bind(AuditManager::class)]
#[Scoped]
interface AuditManagerInterface
{
    /**
     * Manually log an audit entry.
     */
    public function log(string $action, ?object $subject = null, array $properties = []): void;

    /**
     * Get all audit records for a given model.
     */
    public function getForSubject(object $model): Collection;

    /**
     * Get audit records for a model (paginated).
     */
    public function getForSubjectPaginated(object $model, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all audit records caused by a specific user.
     */
    public function getByUser(int|string $userId): Collection;

    /**
     * Get audit records by user (paginated).
     */
    public function getByUserPaginated(int|string $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get the most recent audit records.
     */
    public function getRecent(int $limit = 50): Collection;

    /**
     * Get recent audit records (paginated).
     */
    public function getRecentPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get the diff (old → new values) for a specific audit record.
     */
    public function getDiff(int|string $auditId): array;
}
