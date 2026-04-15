<?php

declare(strict_types=1);

/**
 * DeleteWhenMissingModels Attribute.
 *
 * When applied, the job is automatically deleted if any of its serialized
 * models can no longer be found in the database (instead of failing).
 * Replaces the `$deleteWhenMissingModels` property on job classes.
 *
 * ## Usage:
 * ```php
 * #[DeleteWhenMissingModels]
 * class SendOrderConfirmation implements ShouldQueue
 * {
 *     public function __construct(public Order $order) {}
 *     // If the Order is deleted before the job runs, the job is silently deleted
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Auto-delete the job if serialized models are missing.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class DeleteWhenMissingModels {}
