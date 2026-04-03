<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing different types of executables.
 *
 * @method static NPM() Returns the NPM enum instance
 * @method static YARN() Returns the YARN enum instance
 * @method static TSX() Returns the TSX enum instance
 * @method static NODE() Returns the NODE enum instance
 * @method static PHP() Returns the PHP enum instance
 */
enum ExecutableType: string
{
    use Enum;

    /**
     * Represents the NPM executable.
     */
    #[Label('NPM Executable')]
    #[Description('Represents the NPM executable.')]
    case NPM = 'npm';

    /**
     * Represents the Yarn executable.
     */
    #[Label('Yarn Executable')]
    #[Description('Represents the Yarn executable.')]
    case YARN = 'yarn';

    /**
     * Represents the TSX executable.
     */
    #[Label('TSX Executable')]
    #[Description('Represents the TSX executable.')]
    case TSX = 'tsx';

    /**
     * Represents the Node.js executable.
     */
    #[Label('Node.js Executable')]
    #[Description('Represents the Node.js executable.')]
    case NODE = 'node';

    /**
     * Represents the PHP executable.
     */
    #[Label('PHP Executable')]
    #[Description('Represents the PHP executable.')]
    case PHP = 'php';
}
