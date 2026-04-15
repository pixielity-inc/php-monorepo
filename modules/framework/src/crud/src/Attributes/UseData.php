<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * UseData Attribute for Service/Repository Classes.
 *
 * Defines the Data (DTO) class that should be used for transforming
 * input/output data. This attribute enables automatic transformation
 * between arrays and DTOs using Spatie Laravel Data.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Attributes\UseData;
 * use Pixielity\Users\Data\UserData;
 *
 * #[UseData(UserData::class)]
 * class UserService extends Service implements UserServiceInterface
 * {
 *     // Automatically transforms input arrays to UserData
 *     // and output models to UserData
 *
 *     public function create(array $data): UserData
 *     {
 *         $user = parent::create($data);
 *         return UserData::from($user); // Auto-transformed
 *     }
 * }
 * ```
 *
 * ## How it works:
 * 1. Service/Repository reads the #[UseData] attribute via reflection
 * 2. Input arrays are automatically validated and transformed to Data objects
 * 3. Output models are automatically transformed to Data objects
 * 4. Provides type safety and validation at the boundary
 *
 * ## Benefits:
 * - Type-safe data transfer
 * - Automatic validation
 * - Consistent data structure
 * - IDE autocomplete support
 * - Easy API resource transformation
 *
 * ## Example Data Class:
 * ```php
 * use Spatie\LaravelData\Data;
 *
 * class UserData extends Data
 * {
 *     public function __construct(
 *         public ?int $id,
 *         public string $name,
 *         public string $email,
 *         public ?string $avatar,
 *     ) {}
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UseData
{
    /**
     * Create a new Data attribute instance.
     *
     * @param  class-string  $class  The Data class name (extends Spatie\LaravelData\Data)
     */
    public function __construct(
        public string $class,
    ) {}
}
