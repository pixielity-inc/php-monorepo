<?php

declare(strict_types=1);

namespace Pixielity\Response\Concerns;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Provides lazy data resolution for response builders.
 *
 * Handles deferred data loading using closures and automatic
 * conversion of various data types to arrays for response output.
 *
 * Features:
 *   - Closure resolution for lazy loading
 *   - Eloquent Model and Collection conversion
 *   - JSON Resource and ResourceCollection resolution
 *   - Arrayable interface handling
 *   - Recursive nested lazy data resolution
 *   - Conditional data resolution
 *
 * @category Concerns
 *
 * @since    1.0.0
 */
trait ResolvesLazyData
{
    /**
     * Resolve data to its final form.
     *
     * Handles closures for lazy loading and converts various
     * data types to arrays for response output.
     *
     * @param  mixed $data The data to resolve.
     * @return mixed Resolved data.
     */
    protected function resolveLazyData(mixed $data): mixed
    {
        if ($data instanceof Closure) {
            $data = $data();
        }

        return $this->convertToOutput($data);
    }

    /**
     * Convert data to output format.
     *
     * Handles ResourceCollection, JsonResource, Eloquent Collection,
     * Eloquent Model, and Arrayable instances by converting them to arrays.
     * Scalar values and arrays are returned as-is.
     *
     * @param  mixed $data Data to convert.
     * @return mixed Converted data.
     */
    protected function convertToOutput(mixed $data): mixed
    {
        if ($data instanceof ResourceCollection) {
            return $data->resolve();
        }

        if ($data instanceof JsonResource) {
            return $data->resolve();
        }

        if ($data instanceof Collection) {
            return $data->toArray();
        }

        if ($data instanceof Model) {
            return $data->toArray();
        }

        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        return $data;
    }

    /**
     * Check if data is lazy (a closure).
     *
     * @param  mixed $data Data to check.
     * @return bool  True if data is a closure.
     */
    protected function isLazyData(mixed $data): bool
    {
        return $data instanceof Closure;
    }

    /**
     * Wrap data in a lazy closure.
     *
     * @param  callable $callback Callback to wrap.
     * @return Closure  Lazy closure.
     */
    protected function lazy(callable $callback): Closure
    {
        return Closure::fromCallable($callback);
    }

    /**
     * Resolve nested lazy data recursively.
     *
     * Resolves the top-level data first, then recursively resolves
     * any nested closures found within array values.
     *
     * @param  mixed $data Data that may contain nested closures.
     * @return mixed Fully resolved data.
     */
    protected function resolveNestedData(mixed $data): mixed
    {
        $resolved = $this->resolveLazyData($data);

        if (is_array($resolved)) {
            return array_map(
                fn (mixed $item): mixed => $this->isLazyData($item) ? $this->resolveNestedData($item) : $item,
                $resolved,
            );
        }

        return $resolved;
    }

    /**
     * Conditionally resolve data.
     *
     * Only resolves the data if the given condition is true,
     * otherwise returns the default value.
     *
     * @param  bool  $condition Condition to check.
     * @param  mixed $data      Data to resolve if condition is true.
     * @param  mixed $default   Default value if condition is false.
     * @return mixed Resolved data or default.
     */
    protected function resolveDataIf(bool $condition, mixed $data, mixed $default = null): mixed
    {
        if (! $condition) {
            return $default;
        }

        return $this->resolveLazyData($data);
    }
}
