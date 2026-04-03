<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Module Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the User module.
    |
    */

    /**
     * Default user locale.
     */
    'default_locale' => env('USER_DEFAULT_LOCALE', 'en'),

    /**
     * Default user timezone.
     */
    'default_timezone' => env('USER_DEFAULT_TIMEZONE', 'UTC'),

    /**
     * Enable email verification.
     */
    'email_verification' => env('USER_EMAIL_VERIFICATION', true),

    /**
     * Password reset token expiration (in minutes).
     */
    'password_reset_expiration' => env('USER_PASSWORD_RESET_EXPIRATION', 60),
];
