<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Scripts;

use Composer\Script\Event;

/**
 * LintScripts
 *
 * Composer script handlers for code style checking and fixing via Laravel Pint.
 *
 * Available commands:
 *   "lint"     : "Pixielity\\Foundation\\Scripts\\LintScripts::check"
 *   "lint:fix" : "Pixielity\\Foundation\\Scripts\\LintScripts::fix"
 *
 * @package Pixielity\Foundation\Scripts
 */
class LintScripts
{
    /**
     * Check code style without making changes (CI-safe).
     *
     * Exits non-zero if any file would be reformatted.
     *
     * Usage: composer lint
     */
    public static function check(Event $event): void
    {
        $event->getIO()->write('<info>🎨 Checking code style with Pint...</info>');
        self::pint($event, ['--test']);
    }

    /**
     * Fix code style issues in-place.
     *
     * Usage: composer lint:fix
     */
    public static function fix(Event $event): void
    {
        $event->getIO()->write('<info>🎨 Fixing code style with Pint...</info>');
        self::pint($event, []);
    }

    /**
     * Run vendor/bin/pint with the given flags.
     *
     * @param Event    $event Composer event.
     * @param string[] $flags Additional flags to pass to Pint.
     */
    private static function pint(Event $event, array $flags): void
    {
        $args     = implode(' ', array_map('escapeshellarg', $flags));
        $exitCode = 0;

        passthru("vendor/bin/pint {$args}", $exitCode);

        if ($exitCode !== 0) {
            $event->getIO()->writeError('<error>✖ Pint failed.</error>');
            exit($exitCode);
        }
    }
}
