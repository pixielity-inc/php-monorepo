<?php

declare(strict_types=1);

namespace Pixielity\Container\Attributes;

use Attribute;

/**
 * Tagged Attribute.
 *
 * Marks a class to be tagged in the Laravel service container.
 * This is a marker attribute used by service providers to identify
 * which classes should be registered with a specific tag.
 *
 * ## Usage on Classes:
 * ```php
 * #[Tagged('verification.channels')]
 * class EmailVerificationChannel implements VerificationChannelInterface
 * {
 *     // ...
 * }
 * ```
 *
 * ## Service Provider Registration:
 * Service providers should scan for classes with this attribute and register them:
 * ```php
 * $this->app->tag([
 *     EmailVerificationChannel::class,
 *     SmsVerificationChannel::class,
 * ], 'verification.channels');
 * ```
 *
 * ## Resolution:
 * To inject all tagged services, use Laravel's #[Tag] attribute on parameters:
 * ```php
 * use Illuminate\Container\Attributes\Tag;
 *
 * public function __construct(
 *     #[Tag('verification.channels')] iterable $channels
 * ) {
 *     // $channels contains all services tagged with 'verification.channels'
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Tagged
{
    /**
     * Create a new Tagged attribute instance.
     *
     * @param  string  $tag  The tag name to register this service under
     */
    public function __construct(
        public readonly string $tag,
    ) {}
}
