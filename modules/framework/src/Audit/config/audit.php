<?php

declare(strict_types=1);
use Pixielity\User\Models\User;

/**
 * Audit Configuration.
 *
 * Maps URL-friendly model type names to their FQCNs for the audit API.
 * Add your models here so the audit controller can resolve them.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Model Map
    |--------------------------------------------------------------------------
    |
    | Maps URL type strings to model FQCNs for the audit API.
    | Example: GET /api/audit/users/42 → resolves to User::find(42)
    |
    */
    'model_map' => [
        'users' => User::class,
        // 'tenants' => \Pixielity\Tenancy\Models\Tenant::class,
        // 'orders' => \App\Models\Order::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth Event Logging
    |--------------------------------------------------------------------------
    |
    | Enable/disable automatic auth event logging (login, logout, failed, lockout).
    |
    */
    'auth_events' => env('AUDIT_AUTH_EVENTS', true),

];
