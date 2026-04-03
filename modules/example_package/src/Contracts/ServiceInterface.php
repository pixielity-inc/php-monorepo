<?php

declare(strict_types=1);

namespace Monorepo\ExamplePackage\Contracts;

/**
 * ServiceInterface
 *
 * Defines the contract that all services in this module must satisfy.
 *
 * Coding to an interface rather than a concrete class allows consuming
 * applications to swap implementations (e.g. for testing or feature flags)
 * without changing call-sites.
 *
 * @package Monorepo\ExamplePackage\Contracts
 */
interface ServiceInterface
{
    /**
     * Produce a greeting for the given recipient name.
     *
     * @param  string $name  The recipient's name.
     * @return string        A human-readable greeting string.
     */
    public function greet(string $name): string;
}
