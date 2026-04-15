<?php

declare(strict_types=1);

/**
 * Audit Controller.
 *
 * API endpoints for querying audit data — model changes, activity log,
 * auth events, and unified user timeline. All list endpoints are paginated.
 * Uses the Response builder for consistent API responses.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Audit\Contracts\AuditManagerInterface
 */

namespace Pixielity\Audit\Controllers;

use Illuminate\Container\Attributes\Config;
use Illuminate\Http\Request;
use Pixielity\Audit\Contracts\AuditManagerInterface;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * Audit trail API controller.
 *
 * Endpoints:
 *   GET /api/audit/{type}/{id}  — Audit history for a model
 *   GET /api/audit/user/{userId} — Audit history for a user
 *   GET /api/audit/recent       — Recent audit entries
 *   GET /api/audit/diff/{id}    — Diff for a specific audit record
 *   GET /api/audit/me           — Authenticated user's timeline
 */
#[AsController]
class AuditController extends Controller
{
    /**
     * Create a new AuditController instance.
     *
     * @param  AuditManagerInterface  $auditManager  The audit manager service.
     * @param  array                  $modelMap      Model type → class mapping from config.
     */
    public function __construct(
        private readonly AuditManagerInterface $auditManager,
        #[Config('audit.model_map', [])]
        private readonly array $modelMap = [],
    ) {}

    /**
     * Get audit history for a specific model (paginated).
     *
     * @param  Request     $request  The HTTP request.
     * @param  string      $type     The URL-friendly model type.
     * @param  int|string  $id       The model ID.
     * @return mixed Paginated audit entries or 404.
     */
    public function forSubject(Request $request, string $type, int|string $id): mixed
    {
        $modelClass = $this->resolveModelClass($type);

        if (! $modelClass || ! class_exists($modelClass)) {
            return $this->notFound('Unknown model type.');
        }

        $model = $modelClass::find($id);

        if (! $model) {
            return $this->notFound('Model not found.');
        }

        $perPage = (int) $request->input('per_page', 15);
        $audits = $this->auditManager->getForSubjectPaginated($model, $perPage);

        return $this->ok($audits);
    }

    /**
     * Get audit history for a specific user as actor (paginated).
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $userId   The user ID.
     * @return mixed Paginated audit entries.
     */
    public function forUser(Request $request, int|string $userId): mixed
    {
        $perPage = (int) $request->input('per_page', 15);
        $audits = $this->auditManager->getByUserPaginated($userId, $perPage);

        return $this->ok($audits);
    }

    /**
     * Get recent audit entries (paginated).
     *
     * @param  Request  $request  The HTTP request.
     * @return mixed Paginated recent audit entries.
     */
    public function recent(Request $request): mixed
    {
        $perPage = (int) $request->input('per_page', 15);
        $audits = $this->auditManager->getRecentPaginated($perPage);

        return $this->ok($audits);
    }

    /**
     * Get the diff for a specific audit record.
     *
     * @param  int|string  $auditId  The audit record ID.
     * @return mixed The diff data.
     */
    public function diff(int|string $auditId): mixed
    {
        $diff = $this->auditManager->getDiff($auditId);

        return $this->ok($diff);
    }

    /**
     * Get the authenticated user's own audit timeline (paginated).
     *
     * @param  Request  $request  The HTTP request.
     * @return mixed Paginated audit entries or 401.
     */
    public function me(Request $request): mixed
    {
        $user = $request->user();

        if (! $user) {
            return $this->unauthorized('Unauthenticated.');
        }

        $perPage = (int) $request->input('per_page', 15);
        $audits = $this->auditManager->getByUserPaginated($user->getKey(), $perPage);

        return $this->ok($audits);
    }

    /**
     * Resolve a model class from a URL-friendly type string.
     *
     * @param  string       $type  The URL-friendly model type.
     * @return string|null  The model class or null.
     */
    private function resolveModelClass(string $type): ?string
    {
        return $this->modelMap[$type] ?? null;
    }
}
