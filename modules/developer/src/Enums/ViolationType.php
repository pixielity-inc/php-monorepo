<?php

declare(strict_types=1);

/**
 * ViolationType Enum.
 *
 * Categorizes policy violations reported against marketplace apps.
 * Used by the violation reporting and enforcement systems.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self SECURITY()
 * @method static self PERFORMANCE()
 * @method static self POLICY()
 * @method static self CONTENT()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum ViolationType: string
{
    use Enum;

    #[Label('Security')]
    #[Description('Security-related violation such as data leaks or unauthorized access.')]
    case SECURITY = 'security';

    #[Label('Performance')]
    #[Description('Performance-related violation such as excessive resource usage.')]
    case PERFORMANCE = 'performance';

    #[Label('Policy')]
    #[Description('Marketplace policy violation such as misleading descriptions.')]
    case POLICY = 'policy';

    #[Label('Content')]
    #[Description('Content-related violation such as inappropriate or prohibited material.')]
    case CONTENT = 'content';
}
