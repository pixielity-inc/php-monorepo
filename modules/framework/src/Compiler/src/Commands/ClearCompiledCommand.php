<?php

declare(strict_types=1);

/**
 * DI Clear Command.
 *
 * Clears all compiled caches, generated proxies, and registry files
 * produced by `php artisan di:compile`.
 *
 * @category Commands
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Attributes\Config;
use Illuminate\Filesystem\Filesystem;

use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\spin;

use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Artisan command: php artisan di:clear
 */
#[AsCommand(name: 'di:clear', description: 'Clear all compiled caches, proxies, and registries')]
class ClearCompiledCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'di:clear';

    /**
     * @var string
     */
    protected $description = 'Clear all compiled caches, proxies, and registries';

    /**
     * Execute the console command.
     *
     * @param  Filesystem  $filesystem  The filesystem instance (injected).
     * @param  string  $proxyDirectory  The AOP proxy directory path (injected).
     * @param  string  $cachePath  The AOP interceptor cache path (injected).
     * @return int Exit code.
     */
    public function handle(
        Filesystem $filesystem,
        #[Config('aop.proxy_directory', 'storage/framework/aop')]
        string $proxyDirectory = 'storage/framework/aop',
        #[Config('aop.cache_path', 'bootstrap/cache/interceptors.php')]
        string $cachePath = 'bootstrap/cache/interceptors.php',
    ): int {
        intro('DI Clear');

        $cleared = 0;

        // Clear AOP proxy directory
        $cleared += $this->clearDirectory(
            $filesystem,
            $proxyDirectory,
            'AOP proxy directory',
        );

        // Clear AOP interceptor map cache
        $cleared += $this->clearFile(
            $filesystem,
            $cachePath,
            'AOP interceptor cache',
        );

        // Clear Laravel framework caches
        note('');
        info('Clearing Laravel framework caches...');

        spin(fn () => $this->callSilently('config:clear'), 'Config cache');
        spin(fn () => $this->callSilently('route:clear'), 'Route cache');
        spin(fn () => $this->callSilently('view:clear'), 'View cache');
        spin(fn () => $this->callSilently('event:clear'), 'Event cache');
        $cleared += 4;

        outro("Cleared {$cleared} compiled artifacts.");

        return self::SUCCESS;
    }

    /**
     * Clear a directory if it exists.
     *
     * @param  Filesystem  $filesystem  The filesystem instance.
     * @param  string  $path  The directory path.
     * @param  string  $label  Human-readable label.
     * @return int 1 if cleared, 0 if not found.
     */
    private function clearDirectory(Filesystem $filesystem, string $path, string $label): int
    {
        if ($filesystem->isDirectory($path)) {
            spin(fn () => $filesystem->cleanDirectory($path), $label);

            return 1;
        }

        return 0;
    }

    /**
     * Clear a file if it exists.
     *
     * @param  Filesystem  $filesystem  The filesystem instance.
     * @param  string  $path  The file path.
     * @param  string  $label  Human-readable label.
     * @return int 1 if cleared, 0 if not found.
     */
    private function clearFile(Filesystem $filesystem, string $path, string $label): int
    {
        if ($filesystem->exists($path)) {
            spin(fn () => $filesystem->delete($path), $label);

            return 1;
        }

        return 0;
    }
}
