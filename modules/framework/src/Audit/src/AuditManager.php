<?php

declare(strict_types=1);

/**
 * Audit Manager.
 *
 * Wraps owen-it/laravel-auditing with collection and paginated query methods.
 * All calls guarded with class_exists() for graceful degradation.
 *
 * @category Services
 *
 * @since    1.0.0
 * @see https://laravel-auditing.com/
 */

namespace Pixielity\Audit;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Pixielity\Audit\Contracts\AuditManagerInterface;
use Throwable;

/**
 * Laravel Auditing wrapper for audit trail management.
 */
class AuditManager implements AuditManagerInterface
{
    /**
     * The laravel-auditing Audit model FQCN.
     */
    private const AUDIT_MODEL = 'OwenIt\\Auditing\\Models\\Audit';

    /**
     * {@inheritDoc}
     */
    public function log(string $action, ?object $subject = null, array $properties = []): void
    {
        if (! class_exists(self::AUDIT_MODEL)) {
            return;
        }

        try {
            $auditModel = self::AUDIT_MODEL;

            $data = [
                'event' => $action,
                'auditable_type' => $subject ? $subject::class : null,
                'auditable_id' => $subject && method_exists($subject, 'getKey') ? $subject->getKey() : null,
                'old_values' => $properties['old'] ?? [],
                'new_values' => $properties['new'] ?? $properties,
                'url' => request()?->fullUrl(),
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'tags' => $properties['tags'] ?? null,
            ];

            $user = $this->resolveAuthUser();

            if ($user !== null) {
                $data['user_type'] = $user::class;
                $data['user_id'] = method_exists($user, 'getKey') ? $user->getKey() : null;
            }

            $auditModel::query()->create($data);
        } catch (Throwable) {
            // Audit logging should never break the application.
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getForSubject(object $model): Collection
    {
        return $this->queryForSubject($model)?->latest()->get() ?? collect();
    }

    /**
     * {@inheritDoc}
     */
    public function getForSubjectPaginated(object $model, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->queryForSubject($model);

        if (! $query) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function getByUser(int|string $userId): Collection
    {
        return $this->queryByUser($userId)?->latest()->get() ?? collect();
    }

    /**
     * {@inheritDoc}
     */
    public function getByUserPaginated(int|string $userId, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->queryByUser($userId);

        if (! $query) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function getRecent(int $limit = 50): Collection
    {
        return $this->baseQuery()?->latest()->limit($limit)->get() ?? collect();
    }

    /**
     * {@inheritDoc}
     */
    public function getRecentPaginated(int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->baseQuery();

        if (! $query) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function getDiff(int|string $auditId): array
    {
        if (! class_exists(self::AUDIT_MODEL)) {
            return ['old' => [], 'new' => []];
        }

        try {
            $auditModel = self::AUDIT_MODEL;
            $audit = $auditModel::query()->find($auditId);

            if (! $audit) {
                return ['old' => [], 'new' => []];
            }

            return [
                'old' => $audit->old_values ?? [],
                'new' => $audit->new_values ?? [],
            ];
        } catch (Throwable) {
            return ['old' => [], 'new' => []];
        }
    }

    // =========================================================================
    // Query Builders
    // =========================================================================

    /**
     * Build a query for a subject model's audit records.
     */
    private function queryForSubject(object $model): ?object
    {
        if (! class_exists(self::AUDIT_MODEL)) {
            return null;
        }

        try {
            if (method_exists($model, 'audits')) {
                return $model->audits();
            }

            $auditModel = self::AUDIT_MODEL;

            return $auditModel::query()
                ->where('auditable_type', $model::class)
                ->where('auditable_id', method_exists($model, 'getKey') ? $model->getKey() : null);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Build a query for a user's audit records.
     */
    private function queryByUser(int|string $userId): ?object
    {
        if (! class_exists(self::AUDIT_MODEL)) {
            return null;
        }

        try {
            $auditModel = self::AUDIT_MODEL;

            return $auditModel::query()->where('user_id', $userId);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Build a base query on the Audit model.
     */
    private function baseQuery(): ?object
    {
        if (! class_exists(self::AUDIT_MODEL)) {
            return null;
        }

        try {
            $auditModel = self::AUDIT_MODEL;

            return $auditModel::query();
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Resolve the currently authenticated user.
     */
    private function resolveAuthUser(): ?object
    {
        try {
            return auth()->guard()->user();
        } catch (Throwable) {
            return null;
        }
    }
}
