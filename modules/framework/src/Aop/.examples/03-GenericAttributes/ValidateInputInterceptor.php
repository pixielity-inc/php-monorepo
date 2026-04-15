<?php

declare(strict_types=1);

/**
 * Validate Input Interceptor.
 *
 * A "before" interceptor that validates method arguments before the
 * method executes. If validation fails, it throws a ValidationException
 * and the original method is never called.
 *
 * ## Before Pattern:
 *
 *   ```
 *   // Validate BEFORE calling $next()
 *   if (invalid) throw ValidationException;
 *
 *   // Only reaches here if validation passes
 *   return $next();
 *   ```
 *
 *   The key difference from "around": we don't wrap $next() in anything.
 *   We either throw (blocking execution) or call $next() (allowing it).
 *
 * ## Usage with Generic #[Before] Attribute:
 *
 *   ```php
 *   #[Before(ValidateInputInterceptor::class, params: ['rules' => ['amount' => 'required|numeric|min:0.01']])]
 *   public function charge(float $amount): Receipt { ... }
 *   ```
 *
 *   The `params` array is forwarded to $args['__parameters'] in handle().
 *
 * @category Interceptors
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\GenericAttributes;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;

/**
 * Validates method arguments against configurable rules.
 */
final readonly class ValidateInputInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    /**
     * Validate method arguments before execution.
     *
     * Reads validation rules from the attribute's `params` array,
     * runs Laravel's Validator against the method arguments, and
     * throws ValidationException if any rules fail.
     *
     * @param  object   $target  The original object instance.
     * @param  string   $method  The method name.
     * @param  array    $args    Method arguments + '__parameters' with 'rules' key.
     * @param  Closure  $next    Calls the next interceptor or original method.
     * @return mixed The method's return value (only reached if validation passes).
     *
     * @throws ValidationException If validation fails.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        // =====================================================================
        // BEFORE pattern: validate BEFORE calling $next()
        // =====================================================================

        // Read the validation rules from the attribute's params
        // Usage: #[Before(ValidateInputInterceptor::class, params: ['rules' => [...]])]
        $rules = $this->param('rules', $args, []);

        if (! empty($rules)) {
            // Build a data array from the method arguments (excluding __parameters)
            $data = array_filter(
                $args,
                fn (string $k): bool => ! str_starts_with($k, '__'),
                ARRAY_FILTER_USE_KEY,
            );

            // Run Laravel's validator
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                // Throw ValidationException — the original method is NEVER called.
                // The exception propagates up through the pipeline and out to
                // the controller's exception handler.
                throw new ValidationException($validator);
            }
        }

        // Validation passed — proceed to the next interceptor or original method
        return $next();
    }
}
