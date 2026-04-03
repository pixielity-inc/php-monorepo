<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use BackedEnum;
use Pixielity\Foundation\Enums\PolicyAbility;
use Spatie\RouteAttributes\Attributes\Put as SpatiePut;

/**
 * PUT Endpoint Attribute.
 *
 * Extends Spatie's Put attribute to add OpenAPI documentation and authorization.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Put extends SpatiePut
{
    public int $responseCode;

    public function __construct(
        // Routing (Spatie)
        string $uri,
        ?string $name = null,
        array|string $middleware = [],
        array|string $withoutMiddleware = [],
        // OpenAPI
        public ?string $summary = null,
        public ?string $description = null,
        public array $tags = [],
        public array $parameters = [],
        public ?string $requestSchema = null,
        public ?string $responseSchema = null,
        public string $responseType = 'object',
        int|BackedEnum $responseCode = 200,
        // Authorization
        public ?array $permissions = null,
        public string $permissionLogic = 'all',
        public PolicyAbility|string|null $ability = null,
        public ?string $modelClass = null,
        public ?string $role = null,
    ) {
        // Convert BackedEnum to value
        $this->responseCode = $responseCode instanceof BackedEnum ? $responseCode->value : $responseCode;

        parent::__construct(
            uri: $uri,
            name: $name,
            middleware: $middleware,
            withoutMiddleware: $withoutMiddleware
        );
    }
}
