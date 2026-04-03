<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use BackedEnum;
use Exception;
use OpenApi\Attributes as OA;
use Pixielity\Foundation\Enums\PolicyAbility;
use Pixielity\Support\Reflection;
use Spatie\RouteAttributes\Attributes\Get as SpatieGet;

/**
 * GET Endpoint Attribute.
 *
 * Extends Spatie's Get attribute to add OpenAPI documentation and authorization.
 * This is a composite attribute that combines routing, OpenAPI, and auth in one.
 *
 * ## Usage:
 *
 * ```php
 * use Pixielity\Routing\Attributes\Get;
 *
 * #[Get(
 *     uri: '/',
 *     name: 'incidents.index',
 *     summary: 'Get all incidents',
 *     tags: ['Incidents'],
 *     permissions: [IncidentPermissionEnum::READ],
 *     parameters: [
 *         ['name' => 'status', 'type' => 'string', 'enum' => ['open', 'closed']],
 *         ['name' => 'per_page', 'type' => 'integer', 'default' => 15],
 *     ],
 *     responseSchema: 'Incident',
 *     responseType: 'array'
 * )]
 * public function index(Request $request): JsonResponse
 * ```
 *
 * ## What It Does:
 *
 * 1. **Routing**: Registers GET route (via Spatie parent class)
 * 2. **OpenAPI**: Generates OA\Get attribute with all documentation
 * 3. **Authorization**: Middleware processes permissions/policies
 *
 * ## How It Works:
 *
 * This attribute extends Spatie's Get, so Spatie's route scanner picks it up.
 * The getOpenApiAttribute() method generates the OpenAPI documentation.
 * The middleware reads authorization properties and enforces them.
 *
 * ## Parameters:
 *
 * ### Routing (from Spatie):
 *
 * @param  string  $uri  Route URI (e.g., '/', '/{id}', '/open')
 * @param  string|null  $name  Route name (e.g., 'incidents.index')
 *
 * ### OpenAPI:
 * @param  string|null  $summary  OpenAPI summary (required for docs)
 * @param  string|null  $description  OpenAPI description
 * @param  array<string>  $tags  OpenAPI tags (e.g., ['Incidents'])
 * @param  array<array>  $parameters  Query/path parameters
 * @param  string|null  $responseSchema  Response schema name (e.g., 'Incident')
 * @param  string  $responseType  'object', 'array', or 'paginated' (default: 'object')
 * @param  int  $responseCode  HTTP response code (default: 200)
 *
 * ### Authorization:
 * @param  array<BackedEnum|string>|null  $permissions  Permission enums or strings
 * @param  string  $permissionLogic  'any' or 'all' (default: 'all')
 * @param  string|BackedEnum|null  $ability  Policy ability (e.g., 'view', 'update')
 * @param  string|null  $role  Required role
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Get extends SpatieGet
{
    public int $responseCode;

    public function __construct(
        // Routing (Spatie)
        string $uri,
        ?string $name = null,
        array $middleware = [],
        array $withoutMiddleware = [],
        // OpenAPI
        public ?string $summary = null,
        public ?string $description = null,
        public array $tags = [],
        public array $parameters = [],
        public string|object|null $responseSchema = null,  // ← Accept class or string
        public string $responseType = 'object',
        int|BackedEnum $responseCode = 200,
        // Authorization
        public ?array $permissions = null,
        public string $permissionLogic = 'all',
        public PolicyAbility|string|null $ability = null,
        public ?string $role = null,
    ) {
        // Convert BackedEnum to value
        $this->responseCode = $responseCode instanceof BackedEnum ? $responseCode->value : $responseCode;

        // Convert class to schema name
        if (is_object($this->responseSchema)) {
            $this->responseSchema = $this->extractSchemaName($this->responseSchema);
        }

        // Call parent Spatie constructor for routing
        parent::__construct(
            uri: $uri,
            name: $name,
            middleware: $middleware,
            withoutMiddleware: $withoutMiddleware
        );
    }

    /**
     * Generate OpenAPI attribute from this composite attribute.
     *
     * This method is called by OpenAPI generators to build documentation.
     * It converts our simplified configuration into a full OA\Get attribute.
     */
    public function getOpenApiAttribute(): ?OA\Get
    {
        if (! $this->summary) {
            return null;  // No OpenAPI if no summary provided
        }

        // Build parameters
        $oaParameters = [];
        foreach ($this->parameters as $parameter) {
            $oaParameters[] = new OA\Parameter(
                name: $parameter['name'],
                description: $parameter['description'] ?? null,
                in: $parameter['in'] ?? 'query',
                required: $parameter['required'] ?? false,
                schema: new OA\Schema(
                    type: $parameter['type'] ?? 'string',
                    default: $parameter['default'] ?? null,
                    enum: $parameter['enum'] ?? null
                )
            );
        }

        // Build response based on type
        $schemaRef = is_string($this->responseSchema) ? $this->responseSchema : 'object';
        $responseContent = match ($this->responseType) {
            'array' => new OA\JsonContent(
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/' . $schemaRef)
            ),
            'paginated' => new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/' . $schemaRef)
                    ),
                    new OA\Property(property: 'meta', type: 'object'),
                    new OA\Property(property: 'links', type: 'object'),
                ]
            ),
            default => new OA\JsonContent(ref: '#/components/schemas/' . $schemaRef)
        };

        return new OA\Get(
            path: $this->uri,
            description: $this->description,
            summary: $this->summary,
            security: [['sanctum' => []]],
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
            ]
        );
    }

    /**
     * Extract schema name from class reference.
     */
    protected function extractSchemaName(object|string $class): string
    {
        $className = is_string($class) ? $class : $class::class;

        // Get reflection to read OA\Schema attribute
        try {
            if (! Reflection::exists($className)) {
                return Reflection::getShortName($className);
            }

            $attributes = Reflection::getAttributes($className, OA\Schema::class);

            if ($attributes !== []) {
                /** @var OA\Schema $schema */
                $schema = $attributes[0]->newInstance();

                return $schema->schema ?: Reflection::getShortName($className);
            }
        } catch (Exception) {
            // Fallback to class basename
        }

        return Reflection::getShortName($className);
    }
}
