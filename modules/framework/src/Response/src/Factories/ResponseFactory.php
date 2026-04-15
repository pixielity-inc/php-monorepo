<?php

declare(strict_types=1);

namespace Pixielity\Response\Factories;

use Illuminate\Container\Attributes\Singleton;
use Pixielity\Contracts\Framework\Response\Preset;
use Pixielity\Response\Builders\Response;
use Pixielity\Response\Presets\AdminPreset;
use Pixielity\Response\Presets\ApiPreset;
use Pixielity\Response\Presets\MobilePreset;

/**
 * Factory for creating Response builder instances.
 *
 * Provides methods for creating pre-configured Response builders
 * with different presets (API, Admin, Mobile) and shorthand methods
 * for common HTTP status codes.
 *
 * The factory serves as the main entry point for creating responses
 * in controllers. It handles preset configuration and ensures
 * proper dependency injection.
 *
 * Usage:
 *   ```php
 *   // Via Facade (recommended)
 *   use Pixielity\Response\Facades\Response;
 *
 *   return Response::make()->success()->data($users);
 *   return Response::api()->success()->data($data);
 *   return Response::admin()->view('dashboard', $data);
 *
 *   // Direct factory usage
 *   $factory = app(ResponseFactory::class);
 *   return $factory->make()->success()->data($users);
 *   ```
 *
 * @category Factories
 *
 * @since    1.0.0
 *
 * @see Response The builder class this factory creates.
 * @see Preset The preset interface for configuration.
 */
#[Singleton]
class ResponseFactory
{
    /**
     * Create a new Response builder.
     *
     * Creates a blank Response builder without any preset configuration.
     *
     * @return Response Fresh Response builder instance.
     */
    public function make(): Response
    {
        return Response::make();
    }

    /**
     * Create a Response builder with API preset.
     *
     * Configured for JSON API responses with strict security headers,
     * API versioning, and compact JSON output.
     *
     * @return Response Response builder with API preset.
     */
    public function api(): Response
    {
        return $this->make()->preset($this->resolvePreset(ApiPreset::class));
    }

    /**
     * Create a Response builder for admin/dashboard.
     *
     * Configured for admin panel responses with relaxed headers,
     * debug information, and pretty-printed JSON.
     *
     * @return Response Response builder with admin preset.
     */
    public function admin(): Response
    {
        return $this->make()->preset($this->resolvePreset(AdminPreset::class));
    }

    /**
     * Create a Response builder for mobile clients.
     *
     * Configured for mobile app responses with compact JSON,
     * minimal headers, and client-side caching.
     *
     * @return Response Response builder with mobile preset.
     */
    public function mobile(): Response
    {
        return $this->make()->preset($this->resolvePreset(MobilePreset::class));
    }

    /**
     * Create a 200 OK response.
     *
     * @param  mixed    $data Optional data payload.
     * @return Response Response builder.
     */
    public function ok(mixed $data = null): Response
    {
        return $this->make()->ok($data);
    }

    /**
     * Create a 201 Created response.
     *
     * @param  mixed    $data Optional data payload.
     * @return Response Response builder.
     */
    public function created(mixed $data = null): Response
    {
        return $this->make()->created($data);
    }

    /**
     * Create a 204 No Content response.
     *
     * @return Response Response builder.
     */
    public function noContent(): Response
    {
        return $this->make()->noContent();
    }

    /**
     * Create a 400 Bad Request response.
     *
     * @param  string|null $message Error message.
     * @return Response    Response builder.
     */
    public function badRequest(?string $message = null): Response
    {
        return $this->make()->badRequest($message);
    }

    /**
     * Create a 401 Unauthorized response.
     *
     * @param  string|null $message Error message.
     * @return Response    Response builder.
     */
    public function unauthorized(?string $message = null): Response
    {
        return $this->make()->unauthorized($message);
    }

    /**
     * Create a 403 Forbidden response.
     *
     * @param  string|null $message Error message.
     * @return Response    Response builder.
     */
    public function forbidden(?string $message = null): Response
    {
        return $this->make()->forbidden($message);
    }

    /**
     * Create a 404 Not Found response.
     *
     * @param  string|null $message Error message.
     * @return Response    Response builder.
     */
    public function notFound(?string $message = null): Response
    {
        return $this->make()->notFound($message);
    }

    /**
     * Create a 422 Unprocessable Entity response.
     *
     * @param  array<string, array<string>>|null $errors  Validation errors.
     * @param  string|null                       $message Error message.
     * @return Response                          Response builder.
     */
    public function unprocessable(?array $errors = null, ?string $message = null): Response
    {
        return $this->make()->unprocessable($errors, $message);
    }

    /**
     * Create a 500 Server Error response.
     *
     * @param  string|null $message Error message.
     * @return Response    Response builder.
     */
    public function serverError(?string $message = null): Response
    {
        return $this->make()->serverError($message);
    }

    /**
     * Resolve a preset instance from the container.
     *
     * @param  class-string<Preset> $presetClass The preset class to resolve.
     * @return Preset               The resolved preset instance.
     */
    private function resolvePreset(string $presetClass): Preset
    {
        return resolve($presetClass);
    }
}
