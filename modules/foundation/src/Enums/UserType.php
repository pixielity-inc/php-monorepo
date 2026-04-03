<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing user types.
 *
 * @method static USER() Returns the USER enum instance
 * @method static CUSTOMER() Returns the CUSTOMER enum instance
 * @method static GUEST() Returns the GUEST enum instance
 * @method static ADMIN() Returns the ADMIN enum instance
 */
enum UserType: string
{
    use Enum;

    /**
     * Represents a user.
     */
    #[Label('User')]
    #[Description('A general user with standard access.')]
    case USER = 'user';

    /**
     * Represents a customer.
     */
    #[Label('Customer')]
    #[Description('A customer with access related to purchasing or managing orders.')]
    case CUSTOMER = 'customer';

    /**
     * Represents a guest.
     */
    #[Label('Guest')]
    #[Description('A guest with limited access, typically without the ability to make purchases or access account-specific features.')]
    case GUEST = 'guest';

    /**
     * Represents an admin.
     */
    #[Label('Admin')]
    #[Description('An admin with full access to the system, including management of users, settings, and configurations.')]
    case ADMIN = 'admin';
}
