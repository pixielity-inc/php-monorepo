<?php

declare(strict_types=1);

/**
 * RequireTokenAbility Attribute.
 *
 * AOP interceptor attribute that checks whether the current Sanctum token
 * has a specific ability before allowing the method to execute. If the
 * token lacks the required ability, an AccessDeniedHttpException is thrown.
 *
 * Uses #[InterceptedBy] to bind to TokenAbilityInterceptor — no abstract
 * method needed.
 *
 * ## Usage:
 * ```php
 * #[RequireTokenAbility('write')]
 * public function store(array $data): Model { ... }
 *
 * #[RequireTokenAbility('delete')]
 * public function destroy(int $id): bool { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 * @see \Pixielity\Token\Interceptors\TokenAbilityInterceptor
 */

namespace Pixielity\Token\Attributes;

use Attribute;
use Pixielity\Aop\Attributes\InterceptedBy;
use Pixielity\Aop\Attributes\InterceptorAttribute;
use Pixielity\Token\Interceptors\TokenAbilityInterceptor;

/**
 * Requires the current API token to have a specific ability.
 */
#[InterceptedBy(TokenAbilityInterceptor::class)]
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class RequireTokenAbility extends InterceptorAttribute
{
    /**
     * @param  string  $ability  The required token ability (e.g. 'read', 'write', 'delete').
     * @param  int  $priority  Execution order — lower runs first. Default: 15 (after auth, before roles).
     * @param  string|null  $when  Optional ConditionInterface FQCN for conditional execution.
     */
    public function __construct(
        public readonly string $ability,
        int $priority = 15,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
