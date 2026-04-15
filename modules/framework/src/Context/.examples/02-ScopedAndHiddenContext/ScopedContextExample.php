<?php

declare(strict_types=1);

/**
 * Scoped Context — Example.
 *
 * Shows how to temporarily override context values for a closure,
 * then automatically revert to the original values when done.
 *
 * ## How scope() works internally:
 *
 *   1. Saves the current values of the keys being overridden
 *   2. Sets the temporary values
 *   3. Runs the closure
 *   4. Restores the original values (even if the closure throws)
 *
 * ## Why this is useful:
 *
 *   - Admin impersonation: temporarily act as another user
 *   - Batch processing: process items as different tenants
 *   - Testing: override context for a specific test case
 *   - Nested operations: run a sub-operation with different context
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\ScopedAndHiddenContext;

use Pixielity\Context\Facades\AppContext;

/**
 * Demonstrates scoped context for temporary overrides.
 */
class ScopedContextExample
{
    /**
     * Admin impersonation — temporarily switch user context.
     *
     * An admin wants to see what a specific user sees. We temporarily
     * override the auth context so all code inside the closure thinks
     * the admin IS that user. After the closure, the admin's own
     * context is restored.
     *
     * @param  int  $targetUserId  The user to impersonate.
     * @return mixed The result of the impersonated operation.
     */
    public function impersonateUser(int $targetUserId): mixed
    {
        // Before scope: auth.user_id = admin's ID (e.g., 1)

        return AppContext::scope(
            // Temporary context values — override auth.user_id
            ['auth.user_id' => $targetUserId, 'auth.impersonating' => true],

            // Closure runs with the overridden context
            function () {
                // Inside scope: auth.user_id = $targetUserId
                // All logs, jobs, and events see the impersonated user

                $userId = AppContext::get('auth.user_id');
                // → $targetUserId (not the admin's ID)

                // Any jobs dispatched here inherit the impersonated context
                // dispatch(new GenerateReport());

                return ['viewed_as' => $userId];
            },
        );

        // After scope: auth.user_id = admin's ID (restored automatically)
    }

    /**
     * Batch processing — process items as different tenants.
     *
     * When processing a batch of items across tenants, each item
     * needs to run in the context of its own tenant. scope() ensures
     * the tenant context is correct for each iteration and restored
     * after.
     *
     * @param  array<array{tenant_id: int, data: array}>  $items  Items to process.
     * @return array<int, mixed> Results per tenant.
     */
    public function processBatch(array $items): array
    {
        $results = [];

        foreach ($items as $item) {
            // Each iteration runs with a different tenant context
            $results[] = AppContext::scope(
                ['tenancy.tenant_id' => $item['tenant_id']],
                function () use ($item) {
                    // Inside scope: tenancy.tenant_id = this item's tenant
                    // All database queries, logs, and jobs see this tenant

                    return $this->processItem($item['data']);
                },
            );
            // After each iteration: tenant context reverts to the original
        }

        return $results;
    }

    /**
     * Process a single item (placeholder).
     *
     * @param  array  $data  The item data.
     * @return array The processed result.
     */
    private function processItem(array $data): array
    {
        return ['processed' => true, 'tenant' => AppContext::get('tenancy.tenant_id')];
    }
}
