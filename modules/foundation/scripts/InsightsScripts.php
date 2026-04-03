<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Scripts;

use Composer\Script\Event;

/**
 * InsightsScripts
 *
 * Composer script handlers for code quality analysis via PHP Insights.
 *
 * PHP Insights analyses code quality, architecture, complexity, and style
 * across four dimensions: Code, Complexity, Architecture, and Style.
 *
 * Available commands:
 *   "insights"     : "Pixielity\\Foundation\\Scripts\\InsightsScripts::run"
 *   "insights:fix" : "Pixielity\\Foundation\\Scripts\\InsightsScripts::fix"
 *
 * @package Pixielity\Foundation\Scripts
 */
class InsightsScripts
{
    /**
     * Run PHP Insights quality analysis (read-only).
     *
     * Displays scores for Code, Complexity, Architecture, and Style.
     * Exits non-zero if minimum quality thresholds are not met.
     *
     * Usage: composer insights
     */
    public static function run(Event $event): void
    {
        $event->getIO()->write('<info>💡 Running PHP Insights analysis...</info>');
        self::insights($event, []);
    }

    /**
     * Run PHP Insights and automatically apply safe fixes.
     *
     * Only applies fixes that are safe to automate (style issues).
     * Review changes before committing.
     *
     * Usage: composer insights:fix
     */
    public static function fix(Event $event): void
    {
        $event->getIO()->write('<info>💡 Running PHP Insights with auto-fix...</info>');
        self::insights($event, ['--fix']);
        $event->getIO()->write('<info>✔ Insights fixes applied. Review before committing.</info>');
    }

    /**
     * Run vendor/bin/phpinsights analyse with the given flags.
     *
     * @param Event    $event Composer event.
     * @param string[] $flags Additional flags to pass to PHP Insights.
     */
    private static function insights(Event $event, array $flags): void
    {
        $args     = implode(' ', array_map('escapeshellarg', array_merge(['--no-interaction'], $flags)));
        $exitCode = 0;

        passthru("vendor/bin/phpinsights analyse {$args}", $exitCode);

        if ($exitCode !== 0) {
            $event->getIO()->writeError('<error>✖ PHP Insights analysis failed.</error>');
            exit($exitCode);
        }
    }
}
