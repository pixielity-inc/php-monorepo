<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use BackedEnum;
use OpenApi\Attributes as OA;
use Pixielity\Foundation\Enums\PolicyAbility;
use Spatie\RouteAttributes\Attributes\Post as SpatiePost;

/**
 * POST Endpoint Attribute.
 *
 * Extends Spatie's Post attribute to add OpenAPI documentation and authorization.
 * This is a composite attribute that combines routing, OpenAPI, and auth in one.
 *
 * ## Usage:
 *
 * ```php
 * use Pixielity\Routing\Attributes\Post;
 *
 * #[Post(
 *     uri: '/',
 *     name: 'incidents.store',
 *     summary: 'Create incident',
 *     tags: ['Incidents'],
 *     permissions: [IncidentPermissionEnum::CREATE],
 *     requestSchema: 'CreateIncident',
 *     responseSchema: 'Incident',
 *     responseCode: 201
 * )]
 * public function store(Request $request): JsonResponse
 * ```
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Post extends SpatiePost
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
        int|BackedEnum $responseCode = 201,
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

    /**
     * Generate OpenAPI attribute from this composite attribute.
     */
    public function getOpenApiAttribute(): ?OA\Post
    {
        if (! $this->summary) {
            return null;
        }

        // Build path parameters (if any)
        $oaParameters = [];
        foreach ($this->parameters as $parameter) {
            $oaParameters[] = new OA\Parameter(
                name: $parameter['name'],
                description: $parameter['description'] ?? null,
                in: $parameter['in'] ?? 'path',
                required: $parameter['required'] ?? true,
                schema: new OA\Schema(
                    type: $parameter['type'] ?? 'integer'
                )
            );
        }

        // Build request body
        $requestBody = $this->requestSchema
            ? new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: '#/components/schemas/' . $this->requestSchema)
            )
            : null;

        // Build response
        $responseContent = $this->responseSchema
            ? new OA\JsonContent(ref: '#/components/schemas/' . $this->responseSchema)
            : new OA\JsonContent(type: 'object');

        return new OA\Post(
            path: $this->uri,
            description: $this->description,
            summary: $this->summary,
            security: [['sanctum' => []]],
            requestBody: $requestBody,
            tags: $this->tags,
            parameters: $oaParameters,
            responses: [
                new OA\Response(
                    response: $this->responseCode,
                    description: 'Success',
                    content: $responseContent
                ),
                new OA\Response(response: 401, description: 'Unauthenticated'),
                new OA\Response(response: 403, description: 'Forbidden'),
                new OA\Response(response: 422, description: 'Validation Error'),
            ]
        );
    }
}
