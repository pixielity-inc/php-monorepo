<?php

declare(strict_types=1);

/**
 * AppContext Facade.
 *
 * Static interface to the ContextManager. Named AppContext (not Context)
 * to avoid collision with Laravel's built-in Context facade.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Context\Facades\AppContext;
 *
 * AppContext::set('request_id', Str::uuid());
 * AppContext::setMany('auth', ['user_id' => 1, 'actor' => 'human']);
 * AppContext::get('auth.user_id');       // 1
 * AppContext::forModule('auth');          // ['user_id' => 1, 'actor' => 'human']
 * AppContext::setHidden('api_key', 'secret');
 * AppContext::scope(['tenant_id' => 99], fn () => dispatch(new Job()));
 * ```
 *
 * @category Facades
 *
 * @since    1.0.0
 *
 * @method static void set(string $key, mixed $value)
 * @method static void setMany(string $module, array $data)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static array forModule(string $module)
 * @method static array all()
 * @method static bool has(string $key)
 * @method static void forget(string $key)
 * @method static void setHidden(string $key, mixed $value)
 * @method static mixed getHidden(string $key, mixed $default = null)
 * @method static mixed scope(array $context, \Closure $callback)
 * @method static void registerProvider(\Pixielity\Context\Contracts\ContextProviderInterface $provider)
 * @method static void resolveProviders(\Illuminate\Http\Request $request)
 * @method static void flush()
 *
 * @see \Pixielity\Context\ContextManager
 */

namespace Pixielity\Context\Facades;

use Illuminate\Support\Facades\Facade;
use Pixielity\Context\Contracts\ContextManagerInterface;

/**
 * Facade for the ContextManager.
 */
class AppContext extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return ContextManagerInterface::class;
    }
}
