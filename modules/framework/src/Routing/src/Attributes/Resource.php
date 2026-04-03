<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\Resource as SpatieResource;

/**
 * Resource route registration.
 *
 * Extends Spatie's Resource attribute to register RESTful resource routes
 * for a controller, creating standard CRUD routes automatically.
 *
 * ## Purpose:
 * - Register standard RESTful resource routes
 * - Automatically create CRUD routes (index, create, store, show, edit, update, destroy)
 * - Simplify controller route registration
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\Resource;
 *
 * #[Resource('posts')]
 * class PostController
 * {
 *     public function index() { }      // GET /posts
 *     public function create() { }     // GET /posts/create
 *     public function store() { }      // POST /posts
 *     public function show($id) { }    // GET /posts/{id}
 *     public function edit($id) { }    // GET /posts/{id}/edit
 *     public function update($id) { }  // PUT/PATCH /posts/{id}
 *     public function destroy($id) { } // DELETE /posts/{id}
 * }
 * ```
 *
 * ## Options:
 * - `only`: Limit to specific actions (e.g., `only: ['index', 'show']`)
 * - `except`: Exclude specific actions (e.g., `except: ['create', 'edit']`)
 * - `apiResource`: Use API resource (excludes create/edit)
 * - `names`: Custom route names
 * - `parameters`: Custom parameter names
 * - `shallow`: Use shallow nesting for nested resources
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Resource extends SpatieResource
{
    /**
     * Create a new Resource attribute instance.
     *
     * @param  string  $resource  Resource name (e.g., 'posts', 'users')
     * @param  bool  $apiResource  Whether to use API resource (excludes create/edit)
     * @param  array<string>|string|null  $except  Actions to exclude
     * @param  array<string>|string|null  $only  Actions to include (exclusive)
     * @param  array<string>|string|null  $names  Custom route names
     * @param  array<string>|string|null  $parameters  Custom parameter names
     * @param  bool|null  $shallow  Use shallow nesting
     */
    public function __construct(
        public string $resource,
        public bool $apiResource = false,
        public array|string|null $except = null,
        public array|string|null $only = null,
        public array|string|null $names = null,
        public array|string|null $parameters = null,
        public ?bool $shallow = null,
    ) {
        parent::__construct(
            resource: $resource,
            apiResource: $apiResource,
            except: $except,
            only: $only,
            names: $names,
            parameters: $parameters,
            shallow: $shallow,
        );
    }
}
