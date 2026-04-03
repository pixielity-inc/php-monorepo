<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

use Pixielity\Crud\Attributes\UseService;
use Pixielity\Foundation\Exceptions\RuntimeException;
use Pixielity\Support\Reflection;
use Pixielity\Support\Str;

/**
 * Interacts With Services Trait.
 *
 * Provides convenient methods for resolving and accessing services
 * in controllers. Supports both attribute-based and manual resolution.
 *
 * ## Features:
 * - ✅ Automatic service resolution from #[UseService] attribute
 * - ✅ Manual service resolution via makeService()
 * - ✅ Cached service instances
 * - ✅ Type-safe service access
 *
 * ## Usage with Attribute:
 * ```php
 * use Pixielity\Crud\Attributes\UseService;
 * use Pixielity\Users\Contracts\UserService;
 *
 * #[UseService(UserServiceInterface::class)]
 * class UserController extends BaseController
 * {
 *     public function index()
 *     {
 *         // Service automatically resolved from attribute
 *         $users = $this->service()->paginate(15);
 *         return $this->ok($users);
 *     }
 * }
 * ```
 *
 * ## Usage with Manual Resolution:
 * ```php
 * class UserController extends BaseController
 * {
 *     private UserService $userService;
 *
 *     public function __construct()
 *     {
 *         $this->userService = $this->makeService(UserServiceInterface::class);
 *     }
 *
 *     public function index()
 *     {
 *         $users = $this->userService->paginate(15);
 *         return $this->ok($users);
 *     }
 * }
 * ```
 *
 * ## Multiple Services:
 * ```php
 * #[UseService(UserServiceInterface::class)]
 * class UserController extends BaseController
 * {
 *     private NotificationService $notificationService;
 *
 *     public function __construct()
 *     {
 *         $this->notificationService = $this->makeService(NotificationServiceInterface::class);
 *     }
 *
 *     public function store(Request $request)
 *     {
 *         // Use main service from attribute
 *         $user = $this->service()->create($request->validated());
 *
 *         // Use additional service
 *         $this->notificationService->sendWelcomeEmail($user);
 *
 *         return $this->created($user);
 *     }
 * }
 * ```
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithServices
{
    /**
     * Cached service instance from #[UseService] attribute.
     */
    protected mixed $serviceInstance = null;

    /**
     * Get the main service instance.
     *
     * Resolves the service from the #[UseService] attribute if present.
     * The service is cached after first resolution.
     *
     * @return mixed The resolved service instance
     *
     * @throws RuntimeException If no #[UseService] attribute is found
     *
     * @example
     * ```php
     * #[UseService(UserServiceInterface::class)]
     * class UserController extends BaseController
     * {
     *     public function index()
     *     {
     *         $users = $this->service()->all();
     *         return $this->ok($users);
     *     }
     * }
     * ```
     */
    protected function service(): mixed
    {
        // Return cached instance if available
        if ($this->serviceInstance !== null) {
            return $this->serviceInstance;
        }

        // Resolve service from #[UseService] attribute on the child class
        $this->serviceInstance = $this->makeService();

        return $this->serviceInstance;
    }

    /**
     * Resolve service from #[UseService] attribute.
     *
     * Reads the #[UseService] attribute from the child class and resolves
     * the service interface to a concrete instance via the container.
     *
     * @return mixed Service instance
     *
     * @throws RuntimeException If no #[UseService] attribute is found
     */
    private function makeService(): mixed
    {
        $attributes = Reflection::getAttributes($this, UseService::class);

        if ($attributes === []) {
            throw new RuntimeException(Str::format(
                'Controller [%s] must have a #[UseService] attribute or use makeService() method.',
                static::class
            ));
        }

        /** @var UseService $serviceAttribute */
        $serviceAttribute = $attributes[0]->newInstance();
        $interface = $serviceAttribute->interface;

        // Resolve the service interface via container
        // The interface should have #[Bind] and #[Scoped] attributes
        return resolve($interface);
    }
}
