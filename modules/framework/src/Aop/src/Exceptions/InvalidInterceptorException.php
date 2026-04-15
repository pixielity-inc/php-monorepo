<?php

declare(strict_types=1);

/**
 * Invalid Interceptor Exception.
 *
 * Thrown at build time when an interceptor attribute references a class that
 * does not implement the InterceptorInterface contract. This is a configuration
 * error caught during `php artisan aop:cache`, not at runtime.
 *
 * @category Exceptions
 *
 * @since    1.0.0
 * @see \Pixielity\Aop\Contracts\InterceptorInterface
 */

namespace Pixielity\Aop\Exceptions;

use Pixielity\Aop\Contracts\ConditionInterface;
use Pixielity\Aop\Contracts\InterceptorInterface;

/**
 * Exception for invalid interceptor class references.
 */
class InvalidInterceptorException extends \RuntimeException
{
    /**
     * Create an exception for a class that doesn't implement InterceptorInterface.
     *
     * @param  string  $interceptorClass  The FQCN of the invalid interceptor class.
     * @param  string  $targetClass  The FQCN of the class where the attribute was found.
     * @param  string  $method  The method name where the attribute was applied.
     */
    public static function classDoesNotImplementInterface(
        string $interceptorClass,
        string $targetClass,
        string $method,
    ): static {
        return new static(
            "Interceptor class [{$interceptorClass}] referenced on [{$targetClass}::{$method}()] "
            . 'does not implement ' . InterceptorInterface::class . '.',
        );
    }

    /**
     * Create an exception for a class that doesn't exist.
     *
     * @param  string  $interceptorClass  The FQCN of the missing interceptor class.
     * @param  string  $targetClass  The FQCN of the class where the attribute was found.
     * @param  string  $method  The method name where the attribute was applied.
     */
    public static function classDoesNotExist(
        string $interceptorClass,
        string $targetClass,
        string $method,
    ): static {
        return new static(
            "Interceptor class [{$interceptorClass}] referenced on [{$targetClass}::{$method}()] does not exist.",
        );
    }

    /**
     * Create an exception for an invalid condition class.
     *
     * @param  string  $conditionClass  The FQCN of the invalid condition class.
     * @param  string  $interceptorClass  The FQCN of the interceptor that references it.
     * @param  string  $targetClass  The FQCN of the target class.
     * @param  string  $method  The method name.
     */
    public static function invalidCondition(
        string $conditionClass,
        string $interceptorClass,
        string $targetClass,
        string $method,
    ): static {
        return new static(
            "Condition class [{$conditionClass}] referenced by interceptor [{$interceptorClass}] "
            . "on [{$targetClass}::{$method}()] does not implement "
            . ConditionInterface::class . '.',
        );
    }
}
