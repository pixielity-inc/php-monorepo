<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Scripts;

use Composer\Script\Event;

/**
 * AnalyseScripts
 *
 * Composer script handlers for static analysis via PHPStan.
 *
 * Available commands:
 *   "analyse"          : "Pixielity\\Foundation\\Scripts\\AnalyseScripts::run"
 *   "analyse:baseline" : "Pixielity\\Foundation\\Scripts\\AnalyseScripts::baseline"
 *
 * @package Pixielity\Foundation\Scripts
 */
class AnalyseScripts
{
    /**
     * Memory limit passed to PHPStan.
     * 512M is sufficient for most projects; raise if you hit OOM errors.
     */
    private const MEMORY_LIMIT = '512M';

    /**
     * Run PHPStan static analysis.
     *
     * Exits non-zero if any errors are found.
     *
     * Usage: composer analyse
     */
    public static function run(Event $event): void
    {
        $event->getIO()->write('<info>🔍 Running PHPStan analysis...</info>');
        self::phpstan($event, ['analyse', '--no-progress']);
    }

    /**
     * Generate a PHPStan baseline file.
     *
     * The baseline suppresses all currently existing errors so you can
     * introduce PHPStan into a legacy codebase incrementally.
     * The generated file is `phpstan-baseline.neon`.
     *
     * Usage: composer analyse:baseline
     */
    public static function baseline(Event $event): void
    {
        $event->getIO()->write('<info>🔍 Generating PHPStan baseline...</info>');
        self::phpstan($event, ['analyse', '--no-progress', '--generate-baseline']);
        $event->getIO()->write('<info>✔ Baseline written to phpstan-baseline.neon</info>');
    }

    /**
     * Run vendor/bin/phpstan with the given sub-command and flags.
     *
     * @param Event    $event   Composer event.
     * @param string[] $subArgs PHPStan sub-command and flags.
     */
    private static function phpstan(Event $event, array $subArgs): void
    {
        $memFlag  = '--memory-limit=' . self::MEMORY_LIMIT;
        $args     = implode(' ', array_map('escapeshellarg', array_merge($subArgs, [$memFlag])));
        $exitCode = 0;

        passthru("vendor/bin/phpstan {$args}", $exitCode);

        if ($exitCode !== 0) {
            $event->getIO()->writeError('<error>✖ PHPStan analysis failed.</error>');
            exit($exitCode);
        }
    }
}
