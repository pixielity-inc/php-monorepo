<?php

declare(strict_types=1);

namespace Pixielity\Support\Facades;

use Beeyev\DisposableEmailFilter\DisposableEmailDomains\DisposableEmailDomains;
use Beeyev\DisposableEmailFilter\DisposableEmailFilter as DisposableEmailFilterManager;
use Illuminate\Support\Facades\Facade;

/**
 * Facade for the DisposableEmailFilter service.
 *
 * @method static DisposableEmailDomains disposableEmailDomains()
 * @method static CustomEmailDomainFilterInterface blacklistedDomains()
 * @method static CustomEmailDomainFilterInterface whitelistedDomains()
 * @method static bool isDisposableEmailAddress(string $emailAddress)
 * @method static bool isEmailAddressValid(string $emailAddress)
 *
 * @see DisposableEmailFilterManager
 */
class DisposableEmail extends Facade
{
    /**
     * Get the accessor for the facade.
     *
     * This method must be implemented by subclasses to return the accessor string
     * corresponding to the underlying service or class the facade represents.
     *
     * @return string The accessor for the facade.
     */
    protected static function getAccessor(): string
    {
        return DisposableEmailFilterManager::class;
    }
}
