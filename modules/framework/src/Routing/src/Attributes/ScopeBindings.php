<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\ScopeBindings as SpatieScopeBindings;

/**
 * Enable implicit model binding scoping.
 *
 * Extends Spatie's ScopeBindings attribute to enable automatic scoping
 * of child model bindings to their parent models.
 *
 * ## Purpose:
 * - Automatically scope child models to parent models
 * - Ensure child belongs to parent in route bindings
 * - Prevent unauthorized access to child resources
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\ScopeBindings;
 * use Pixielity\Routing\Attributes\Get;
 *
 * class CommentController
 * {
 *     // Without scoping: Any comment ID works
 *     #[Get('/posts/{post}/comments/{comment}')]
 *     public function show(Post $post, Comment $comment) { }
 *
 *     // With scoping: Comment must belong to the post
 *     #[Get('/posts/{post}/comments/{comment}')]
 *     #[ScopeBindings()]
 *     public function showScoped(Post $post, Comment $comment) {
 *         // $comment is guaranteed to belong to $post
 *     }
 * }
 * ```
 *
 * ## Requirements:
 * - Child model must have a relationship to parent model
 * - Relationship name should match parent parameter name
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class ScopeBindings extends SpatieScopeBindings {}
