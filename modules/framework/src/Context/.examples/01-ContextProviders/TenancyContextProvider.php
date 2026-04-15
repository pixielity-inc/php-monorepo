<?php

declare(strict_types=1);

/**
 * Tenancy Context Provider — Example.
 *
 * Pushes current tenant information into the application context.
 * After this provider runs:
 *
 *   AppContext::get('tenancy.tenant_id')     → 1
 *   AppContext::get('tenancy.tenant_slug')   → 'acme-corp'
 *   AppContext::get('tenancy.tenant_name')   → 'Acme Corporation'
 *   AppContext::forModule('tenancy')         → ['tenant_id' => 1, 'tenant_slug' => 'acme-corp', ...]
 *
 * ## Why this matters for multi-tenancy:
 *
 *   Every log entry includes the tenant ID, so when debugging issues
 *   you can filter logs by tenant. Queue jobs carry the tenant context,
 *   so the job knows which tenant it's processing for.
 *
 * ## Priority: 20 (runs after auth)
 *
 *   Tenancy runs after auth because the tenant might be resolved from
 *   the authenticated user's tenant_id attribute.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\ContextProviders;

use Illuminate\Http\Request;
use Pixielity\Context\AbstractContextProvider;

/**
 * Pushes current tenant info into application context.
 */
class TenancyContextProvider extends AbstractContextProvider
{
    /**
     * The unique key for this context slice.
     *
     * @return string The context slice key.
     */
    public function key(): string
    {
        return 'tenancy';
    }

    /**
     * Resolve tenancy context data from the current request.
     *
     * Uses the tenant() helper (from the tenancy package) to get the
     * current tenant. If no tenant is initialized, returns empty array.
     *
     * @param  Request  $request  The current HTTP request.
     * @return array<string, mixed> The tenancy context data.
     */
    public function resolve(Request $request): array
    {
        // tenant() is a global helper from the tenancy package
        // Returns null if no tenant is initialized for this request
        if (! function_exists('tenant') || tenant() === null) {
            return [];
        }

        $tenant = tenant();

        return [
            'tenant_id' => $tenant->getTenantKey(),
            'tenant_slug' => $tenant->getAttribute('slug'),
            'tenant_name' => $tenant->getAttribute('name'),
        ];
    }

    /**
     * Priority: 20 — runs after auth (10) but before default providers (100).
     *
     * @return int The provider priority.
     */
    public function priority(): int
    {
        return 20;
    }
}
