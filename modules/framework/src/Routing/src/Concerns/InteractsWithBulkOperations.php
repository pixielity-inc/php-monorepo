<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

use Pixielity\Response\Builders\Response;

/**
 * Interacts With Bulk Operations Trait.
 *
 * Provides convenient methods for handling bulk operations in API endpoints.
 *
 * ## Usage:
 * ```php
 * class UserController extends BaseController
 * {
 *     use InteractsWithBulkOperations;
 *
 *     public function bulkCreate()
 *     {
 *         $users = $this->input('users', []);
 *         $created = [];
 *
 *         foreach ($users as $userData) {
 *             $created[] = User::create($userData);
 *         }
 *
 *         return $this->bulkCreated($created, count($created) . ' users created');
 *     }
 *
 *     public function bulkDelete()
 *     {
 *         $ids = $this->input('ids', []);
 *         $deleted = User::whereIn('id', $ids)->delete();
 *
 *         return $this->bulkDeleted($deleted, $deleted . ' users deleted');
 *     }
 * }
 * ```
 *
 * @method Response response() Get the Response facade for advanced chaining`
 * @method Response bulkCreated(mixed $data, ?string $message = null) Return bulk create Response builder
 * @method Response bulkUpdated(mixed $data, ?string $message = null) Return bulk update Response builder
 * @method Response bulkDeleted(int $count, ?string $message = null) Return bulk delete Response builder
 * @method Response bulkOperation(mixed $data, string $operation, ?string $message = null) Return generic bulk operation Response builder
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithBulkOperations
{
    /**
     * Return a bulk create response.
     *
     * @param  mixed  $data  Created items
     * @param  string|null  $message  Optional success message
     */
    protected function bulkCreated(mixed $data, ?string $message = null): Response
    {
        $message ??= 'Items created successfully';

        return $this->response()
            ->created($data)
            ->message($message)
            ->meta([
                'operation' => 'bulk_create',
                'count' => is_countable($data) ? count($data) : 1,
            ]);
    }

    /**
     * Return a bulk update response.
     *
     * @param  mixed  $data  Updated items or count
     * @param  string|null  $message  Optional success message
     */
    protected function bulkUpdated(mixed $data, ?string $message = null): Response
    {
        $message ??= 'Items updated successfully';

        $count = is_int($data) ? $data : (is_countable($data) ? count($data) : 1);

        return $this->response()
            ->ok(is_int($data) ? null : $data)
            ->message($message)
            ->meta([
                'operation' => 'bulk_update',
                'count' => $count,
            ]);
    }

    /**
     * Return a bulk delete response.
     *
     * @param  int  $count  Number of deleted items
     * @param  string|null  $message  Optional success message
     */
    protected function bulkDeleted(int $count, ?string $message = null): Response
    {
        $message ??= 'Items deleted successfully';

        return $this->response()
            ->ok(null)
            ->message($message)
            ->meta([
                'operation' => 'bulk_delete',
                'count' => $count,
            ]);
    }

    /**
     * Return a generic bulk operation response.
     *
     * @param  mixed  $data  Operation result data
     * @param  string  $operation  Operation name
     * @param  string|null  $message  Optional success message
     */
    protected function bulkOperation(
        mixed $data,
        string $operation,
        ?string $message = null
    ): Response {
        $message ??= 'Bulk operation completed successfully';

        return $this->response()
            ->ok($data)
            ->message($message)
            ->meta([
                'operation' => $operation,
                'count' => is_countable($data) ? count($data) : 1,
            ]);
    }

    /**
     * Return a partial success response for bulk operations.
     *
     * Use when some items succeeded and some failed.
     *
     * @param  array  $successful  Successfully processed items
     * @param  array  $failed  Failed items with error messages
     * @param  string|null  $message  Optional message
     */
    protected function bulkPartialSuccess(
        array $successful,
        array $failed,
        ?string $message = null
    ): Response {
        $message ??= 'Bulk operation completed with some failures';

        return $this->response()
            ->ok([
                'successful' => $successful,
                'failed' => $failed,
            ])
            ->message($message)
            ->meta([
                'operation' => 'bulk_partial',
                'successful_count' => count($successful),
                'failed_count' => count($failed),
                'total_count' => count($successful) + count($failed),
            ]);
    }
}
