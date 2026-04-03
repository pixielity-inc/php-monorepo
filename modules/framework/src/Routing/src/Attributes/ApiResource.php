<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\ApiResource as SpatieApiResource;

/**
 * API resource route registration.
 *
 * Extends Spatie's ApiResource attribute to register RESTful API resource routes
 * for a controller, creating standard CRUD routes without create/edit forms.
 *
 * ## Purpose:
 * - Register API-specific RESTful resource routes
 * - Automatically create API CRUD routes (index, store, show, update, destroy)
 * - Exclude form-related routes (create, edit) for APIs
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\ApiResource;
 *
 * #[ApiResource('posts')]
 * class PostController
 * {
 *     public function index() { }      // GET /posts
 *     public function store() { }      // POST /posts
 *     public function show($id) { }    // GET /posts/{id}
 *     public function update($id) { }  // PUT/PATCH /posts/{id}
 *     public function destroy($id) { } // DELETE /posts/{id}
 * }
 * ```
 *
 * ## Difference from Resource:
 * - Excludes `create` (GET /posts/create)
 * - Excludes `edit` (GET /posts/{id}/edit)
 * - Designed for JSON APIs without HTML forms
 *
 * ## Options:
 * - `only`: Limit to specific actions (e.g., `only: ['index', 'show']`)
 * - `except`: Exclude specific actions (e.g., `except: ['destroy']`)
 * - `names`: Custom route names
 * - `parameters`: Custom parameter names
 * - `shallow`: Use shallow nesting for nested resources
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ApiResource extends SpatieApiResource
{
    /**
     * Create a new ApiResource attribute instance.
     *
     * @param  string  $resource  Resource name (e.g., 'posts', 'users')
     * @param  array<string>|string|null  $except  Actions to exclude
     * @param  array<string>|string|null  $only  Actions to include (exclusive)
     * @param  array<string>|string|null  $names  Custom route names
     * @param  array<string>|string|null  $parameters  Custom parameter names
     * @param  bool|null  $shallow  Use shallow nesting
     */
    public function __construct(
        public string $resource,
        public array|string|null $except = null,
        public array|string|null $only = null,
        public array|string|null $names = null,
        public array|string|null $parameters = null,
        public ?bool $shallow = null,
    ) {
        parent::__construct(
            resource: $resource,
            except: $except,
            only: $only,
            names: $names,
            parameters: $parameters,
            shallow: $shallow,
        );
    }
}
