<?php

declare(strict_types=1);

/**
 * RequireTokenScope Attribute.
 *
 * AOP interceptor attribute that checks whether the current OAuth2 token
 * (Passport) has a specific scope. Works alongside RequireTokenAbility
 * which checks Sanctum abilities.
 *
 * ## Usage:
 * ```php
 * #[RequireTokenScope('read:users')]
 * public function index(): Collection { ... }
 *
 * #[RequireTokenScope('write:orders')]
 * public function store(array $data): Model { ... }
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
 * Requires the current token to have a specific OAuth2 scope.
 */
#[InterceptedBy(TokenAbilityInterceptor::class)]
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class RequireTokenScope extends InterceptorAttribute
{
    /**
     * @param  string  $scope  The required OAuth2 scope (e.g. 'read:users').
     * @param  int  $priority  Execution order. Default: 15.
     * @param  string|null  $when  Optional ConditionInterface FQCN.
     */
    public function __construct(
        public readonly string $scope,
        int $priority = 15,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
