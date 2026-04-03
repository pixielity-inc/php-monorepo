<?php

declare(strict_types=1);

namespace Pixielity\Scripts;

use Composer\Script\Event;

/**
 * WorkspaceDiscovery
 *
 * Discovers all Composer workspaces in the monorepo dynamically.
 *
 * Used by the root composer.json to run commands across every workspace
 * without hardcoding workspace paths. Any new application or module
 * added to applications/ or modules/ is automatically picked up.
 *
 * Workspace roots scanned:
 *   applications/*          — deployable Laravel/PHP applications
 *   modules/*               — shared libraries
 *   modules/*//*             — vendor-namespaced modules
 *   modules/*\/\/*\/*       — deeply nested modules
 *
 * @package Pixielity\Scripts
 */
class WorkspaceDiscovery
{
    /**
     * Glob patterns relative to the monorepo root that are scanned
     * for composer.json files.
     */
    private const WORKSPACE_GLOBS = [
        'applications/*',
        'modules/*',
        'modules/*/*',
        'modules/*/*/*',
    ];

    /**
     * Run a Composer script command across every discovered workspace.
     *
     * Reads the script name from the first element of $event->getArguments().
     * Falls back to the Composer event name if no argument is provided.
     *
     * Usage in composer.json:
     *   "env:dev": "Pixielity\\Scripts\\WorkspaceDiscovery::runAll env:dev"
     *
     * @param Event $event Composer script event.
     */
    public static function runAll(Event $event): void
    {
        $io        = $event->getIO();
        $root      = getcwd();
        $args      = $event->getArguments();
        $script    = $args[0] ?? $event->getName();
        $workspaces = self::discover($root);

        if (empty($workspaces)) {
            $io->write('<warning>No workspaces discovered.</warning>');
            return;
        }

        $io->write("<info>Running [{$script}] across " . count($workspaces) . " workspace(s)...</info>");
        $io->write('');

        $failed = [];

        foreach ($workspaces as $workspace) {
            $relative = self::relative($workspace, $root);
            $io->write("<comment>→ {$relative}</comment>");

            $composerBin = self::findComposerBin();
            $command     = escapeshellcmd($composerBin)
                . ' ' . escapeshellarg($script)
                . ' --working-dir=' . escapeshellarg($workspace)
                . ' 2>&1';

            passthru($command, $exitCode);

            if ($exitCode !== 0) {
                $failed[] = $relative;
            }

            $io->write('');
        }

        if (!empty($failed)) {
            $io->writeError('<error>Failed in:</error>');
            foreach ($failed as $path) {
                $io->writeError("  ✘ {$path}");
            }
            exit(1);
        }

        $io->write('<info>✔ Done.</info>');
    }

    /**
     * Discover all workspace directories that contain a composer.json.
     *
     * @param  string   $root Absolute monorepo root path.
     * @return string[] Absolute paths to workspace directories, sorted.
     */
    public static function discover(string $root): array
    {
        $found = [];

        foreach (self::WORKSPACE_GLOBS as $glob) {
            $matches = glob($root . '/' . $glob, GLOB_ONLYDIR) ?: [];

            foreach ($matches as $dir) {
                if (is_file($dir . '/composer.json')) {
                    $real = realpath($dir);
                    if ($real !== false) {
                        $found[$real] = true;
                    }
                }
            }
        }

        $paths = array_keys($found);
        sort($paths);

        return $paths;
    }

    /**
     * List all discovered workspaces to stdout.
     *
     * Usage: composer workspaces:list
     *
     * @param Event $event Composer script event.
     */
    public static function listWorkspaces(Event $event): void
    {
        $io   = $event->getIO();
        $root = getcwd();
        $ws   = self::discover($root);

        $io->write('');
        $io->write('<info>Discovered workspaces:</info>');

        if (empty($ws)) {
            $io->write('  <fg=yellow>None found.</>');
        } else {
            foreach ($ws as $path) {
                $rel  = self::relative($path, $root);
                $name = self::readName($path);
                $io->write("  <comment>{$rel}</comment>  ({$name})");
            }
        }

        $io->write('');
    }

    /**
     * Find the Composer binary path.
     *
     * @return string Path to the composer executable.
     */
    private static function findComposerBin(): string
    {
        // When running inside a Composer script, $_SERVER['SCRIPT_FILENAME']
        // points to the composer phar/binary.
        if (!empty($_SERVER['SCRIPT_FILENAME'])) {
            return PHP_BINARY . ' ' . escapeshellarg($_SERVER['SCRIPT_FILENAME']);
        }

        return 'composer';
    }

    /**
     * Read the "name" field from a workspace's composer.json.
     *
     * @param  string $dir Absolute path to the workspace directory.
     * @return string      Package name, or "unknown" if not readable.
     */
    private static function readName(string $dir): string
    {
        $path = $dir . '/composer.json';

        if (!is_file($path)) {
            return 'unknown';
        }

        $data = json_decode(file_get_contents($path), true) ?? [];

        return $data['name'] ?? 'unknown';
    }

    /**
     * Make a path relative to a base directory.
     *
     * @param  string $path Absolute path.
     * @param  string $base Base directory.
     * @return string       Relative path.
     */
    private static function relative(string $path, string $base): string
    {
        $base = rtrim($base, '/');

        if (str_starts_with($path, $base . '/')) {
            return substr($path, strlen($base) + 1);
        }

        return $path;
    }
}
