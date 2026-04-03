<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Scripts;

use Composer\Script\Event;

/**
 * RefactorScripts
 *
 * Composer script handlers for automated refactoring via Rector.
 *
 * Available commands:
 *   "refactor"     : "Pixielity\\Foundation\\Scripts\\RefactorScripts::dryRun"
 *   "refactor:fix" : "Pixielity\\Foundation\\Scripts\\RefactorScripts::apply"
 *
 * @package Pixielity\Foundation\Scripts
 */
class RefactorScripts
{
    /**
     * Preview Rector changes without writing anything (dry-run).
     *
     * Safe to run at any time — no files are modified.
     * Shows a diff of what would change.
     *
     * Usage: composer refactor
     */
    public static function dryRun(Event $event): void
    {
        $event->getIO()->write('<info>🔧 Previewing Rector changes (dry-run)...</info>');
        self::rector($event, ['--dry-run']);
    }

    /**
     * Apply Rector refactoring changes to the codebase.
     *
     * Modifies files in-place. Commit your work before running this.
     *
     * Usage: composer refactor:fix
     */
    public static function apply(Event $event): void
    {
        $event->getIO()->write('<info>🔧 Applying Rector refactoring...</info>');
        self::rector($event, []);
        $event->getIO()->write('<info>✔ Rector applied. Review the changes before committing.</info>');
    }

    /**
     * Run vendor/bin/rector process with the given flags.
     *
     * @param Event    $event Composer event.
     * @param string[] $flags Additional flags to pass to Rector.
     */
    private static function rector(Event $event, array $flags): void
    {
        $args     = implode(' ', array_map('escapeshellarg', $flags));
        $exitCode = 0;

        passthru("vendor/bin/rector process {$args}", $exitCode);

        if ($exitCode !== 0) {
            $event->getIO()->writeError('<error>✖ Rector failed.</error>');
            exit($exitCode);
        }
    }
}
