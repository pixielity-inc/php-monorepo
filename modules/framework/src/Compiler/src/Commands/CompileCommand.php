<?php

declare(strict_types=1);

/**
 * DI Compile Command.
 *
 * Discovers and executes all registered compiler passes in priority order.
 * Equivalent to Magento 2's `bin/magento setup:di:compile`.
 *
 * @category Commands
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Commands;

use Illuminate\Console\Command;
use Pixielity\Compiler\CompilerEngine;
use Pixielity\Compiler\Contracts\CompilerContext;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Artisan command: php artisan di:compile
 */
#[AsCommand(name: 'di:compile', description: 'Compile all Pixielity packages — discovery, registries, proxies, caches')]
class CompileCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'di:compile
        {--force : Force recompilation even if caches exist}';

    /**
     * @var string
     */
    protected $description = 'Compile all Pixielity packages — discovery, registries, proxies, caches';

    /**
     * Execute the console command.
     *
     * @param  CompilerEngine  $engine  The compiler engine (injected).
     * @return int Exit code (0 = success, 1 = failure).
     */
    public function handle(CompilerEngine $engine): int
    {
        $context = new CompilerContext(
            container: $this->laravel,
            output: $this->output,
            verbose: $this->output->isVerbose(),
        );

        if ($this->option('force')) {
            $context->set('force', true);
        }

        $results = $engine->compile($context);

        foreach ($results as $entry) {
            if (! $entry['result']->success) {
                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }
}
