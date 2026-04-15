<?php

declare(strict_types=1);

/**
 * Token Configuration.
 *
 * Configures the token driver (sanctum or passport) and driver-specific
 * settings. The TokenManager reads this at runtime to resolve the active driver.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Token Driver
    |--------------------------------------------------------------------------
    |
    | The token driver to use for API authentication. Supported: "sanctum", "passport".
    | Sanctum: personal access tokens (simple, stateless).
    | Passport: full OAuth2 (auth code, client credentials, scopes).
    |
    */
    'driver' => env('TOKEN_DRIVER', 'sanctum'),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Configuration
    |--------------------------------------------------------------------------
    |
    | Additional Sanctum-specific settings. Most config lives in config/sanctum.php.
    |
    */
    'sanctum' => [
        'default_abilities' => ['*'],
        'expiration_minutes' => null, // null = no expiration
    ],

    /*
    |--------------------------------------------------------------------------
    | Passport Configuration
    |--------------------------------------------------------------------------
    |
    | Additional Passport-specific settings. Most config lives in config/passport.php.
    | Scopes are registered here and discovered via #[AsScope] at build time.
    |
    */
    'passport' => [
        'default_scopes' => [],
    ],

];
