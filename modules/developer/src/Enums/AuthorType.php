<?php

declare(strict_types=1);

/**
 * AuthorType Enum.
 *
 * Identifies the type of author for comments, support messages,
 * and violation reports. Distinguishes between tenants, developers,
 * and automated system actions.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self TENANT()
 * @method static self DEVELOPER()
 * @method static self SYSTEM()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum AuthorType: string
{
    use Enum;

    #[Label('Tenant')]
    #[Description('Author is a tenant who uses apps from the marketplace.')]
    case TENANT = 'tenant';

    #[Label('Developer')]
    #[Description('Author is a developer who builds apps for the marketplace.')]
    case DEVELOPER = 'developer';

    #[Label('System')]
    #[Description('Author is an automated system process such as a security scan.')]
    case SYSTEM = 'system';
}
