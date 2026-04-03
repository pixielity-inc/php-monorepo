<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Attributes\Meta;
use Pixielity\Enum\Enum;

/**
 * User Status Enum.
 *
 * Represents the various states a user account can be in within the system.
 * This enum provides labeled and described status values for user account management.
 *
 * ## Features:
 * - Labeled status values for display purposes
 * - Detailed descriptions for each status
 * - Type-safe status handling
 * - Easy conversion to arrays and options
 *
 * ## Usage:
 * ```php
 * // Get status value
 * $status = UserStatus::ACTIVE->value; // 'active'
 *
 * // Get label
 * $label = UserStatus::ACTIVE->label(); // 'Active User'
 *
 * // Get description
 * $description = UserStatus::ACTIVE->description(); // 'The user account is active...'
 *
 * // Get all status names
 * $names = UserStatus::names(); // ['ACTIVE', 'INACTIVE', 'SUSPENDED', 'PENDING']
 *
 * // Get all status values
 * $values = UserStatus::values(); // ['active', 'inactive', 'suspended', 'pending']
 *
 * // Get options for select dropdown
 * $options = UserStatus::options(); // ['ACTIVE' => 'active', ...]
 *
 * // Compare statuses
 * if ($user->status->is(UserStatus::ACTIVE)) {
 *     // User is active
 * }
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
#[Meta([Description::class, Label::class])]
enum UserStatus: string
{
    use Enum;

    /**
     * Active User Status.
     *
     * Indicates that the user account is fully active and has complete access
     * to all system features and functionalities.
     */
    #[Label('Active User')]
    #[Description('The user account is active and can access the system')]
    case ACTIVE = 'active';

    /**
     * Inactive User Status.
     *
     * Indicates that the user account has been deactivated and cannot access
     * the system. This is typically a temporary state.
     */
    #[Label('Inactive User')]
    #[Description('The user account is inactive and cannot access the system')]
    case INACTIVE = 'inactive';

    /**
     * Suspended User Status.
     *
     * Indicates that the user account has been suspended due to policy violations,
     * security concerns, or administrative actions.
     */
    #[Label('Suspended User')]
    #[Description('The user account has been suspended due to policy violations')]
    case SUSPENDED = 'suspended';

    /**
     * Pending Verification Status.
     *
     * Indicates that the user account is awaiting email verification or
     * other forms of identity confirmation before full activation.
     */
    #[Label('Pending Verification')]
    #[Description('The user account is pending email verification')]
    case PENDING = 'pending';
}
