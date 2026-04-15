<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * UseService Attribute for Controller Classes.
 *
 * Defines the service interface that a controller uses. This attribute
 * eliminates the need for a constructor in each controller class.
 *
 * The service interface should have #[Bind] and #[Scoped] attributes
 * pointing to the concrete service class.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Attributes\UseService;
 * use Pixielity\Users\Contracts\UserServiceInterface;
 *
 * #[UseService(UserServiceInterface::class)]
 * class UserController extends Controller
 * {
 *     // No constructor needed - service is resolved via attribute
 *
 *     public function index(): JsonResponse
 *     {
 *         // Access service via $this->service
 *         $users = $this->service->paginate(15);
 *         return response()->json($users);
 *     }
 * }
 * ```
 *
 * ## How it works:
 * 1. Controller base class reads the #[UseService] attribute via reflection
 * 2. The attribute contains the service interface class name
 * 3. The interface has #[Bind(ConcreteService::class)] attribute
 * 4. Container resolves the interface to the concrete service
 * 5. Service is assigned to $this->service
 *
 * ## Multiple Services:
 * For controllers that need multiple services, use constructor injection
 * for additional services:
 *
 * ```php
 * #[UseService(UserServiceInterface::class)]
 * class UserController extends Controller
 * {
 *     public function __construct(
 *         protected readonly NotificationServiceInterface $notificationService
 *     ) {
 *         parent::__construct(); // Resolves main service from attribute
 *     }
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UseService
{
    /**
     * Create a new Service attribute instance.
     *
     * @param  class-string  $interface  The service interface class name
     */
    public function __construct(
        public string $interface,
    ) {}
}
