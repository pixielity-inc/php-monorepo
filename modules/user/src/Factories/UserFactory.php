<?php

declare(strict_types=1);

namespace Pixielity\User\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Pixielity\User\Models\User;

/**
 * User Factory.
 *
 * @extends Factory<User>
 *
 * @category   Factories
 *
 * @since      1.0.0
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<User>
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'locale' => fake()->randomElement(['en', 'ar', 'fr']),
            'timezone' => fake()->timezone(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
