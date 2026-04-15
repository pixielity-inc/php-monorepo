<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Console\Commands;

use Exception;
use Illuminate\Support\Facades\File;
use Override;
use Pixielity\Foundation\Exceptions\RuntimeException;
use Pixielity\StubGenerator\StubGenerator;
use Pixielity\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function dirname;

/**
 * Base class for all make:* generator commands.
 *
 * Provides a complete code generation pipeline built on top of the
 * `pixielity/stub-generator` package. This class handles the full lifecycle
 * of generating a file from a stub template:
 *
 * 1. Loading a `.stub` template from the module's `stubs/` directory
 * 2. Resolving placeholder values (namespace, class name, module, etc.)
 * 3. Rendering the stub with `StubGenerator::create()->saveTo()`
 * 4. Writing the output file to the correct module path
 *
 * ## How StubGenerator Integration Works:
 *
 * The constructor sets `StubGenerator::setBasePath()` to the module's
 * `stubs/` directory (three levels up from this file). All stub paths
 * passed to `StubGenerator::create()` are resolved relative to that base.
 *
 * Placeholders use StubGenerator's auto-uppercase format — keys like
 * `'namespace'` are automatically matched to `$NAMESPACE$` in stubs.
 *
 * ## Subclass Contract:
 *
 * Subclasses **must** implement:
 * - `getGeneratorType()` — Returns the type identifier (e.g., 'action', 'controller')
 * - `getStubName()` — Returns the stub filename without `.stub` extension
 *
 * Subclasses **may** override:
 * - `getSubNamespace()` — Adds a sub-namespace segment (e.g., 'Controllers')
 * - `getPlaceholders()` — Adds or modifies placeholder values for the stub
 * - `handle()` — Customizes the generation workflow (e.g., generate multiple files)
 * - `generateFile()` — Customizes single-file generation logic
 *
 * ## Example Subclass:
 * ```php
 * #[AsCommand(name: 'make:action', description: 'Create a new action class')]
 * class MakeActionCommand extends GeneratorCommand
 * {
 *     protected $signature = 'make:action {package} {name} {--force}';
 *
 *     protected function getGeneratorType(): string { return 'action'; }
 *     protected function getStubName(): string { return 'action'; }
 *     protected function getSubNamespace(): string { return 'Actions'; }
 * }
 * ```
 *
 * @since 1.0.0
 *
 * @see \Pixielity\StubGenerator\StubGenerator  The underlying stub rendering engine
 * @see BaseCommand                              Parent class providing AOP hooks and output helpers
 */
abstract class GeneratorCommand extends BaseCommand
{
    /**
     * Initialize the generator command.
     *
     * Calls the parent constructor to set up the Symfony console command,
     * then configures StubGenerator's base path to point at the module's
     * `stubs/` directory. This ensures all `StubGenerator::create()` calls
     * resolve stub paths relative to `modules/foundation/stubs/`.
     *
     * The path is calculated as three directories up from this file:
     *   Commands/ → Console/ → src/ → foundation/ (contains stubs/)
     */
    public function __construct()
    {
        parent::__construct();

        // Point StubGenerator at the module's stubs directory so that
        // subclasses can reference stubs by name (e.g., 'action.stub')
        // without worrying about absolute paths.
        StubGenerator::setBasePath(dirname(__DIR__, 3) . '/stubs');
    }

    // -------------------------------------------------------------------------
    //  Abstract Methods — Must be implemented by subclasses
    // -------------------------------------------------------------------------

    /**
     * Get the generator type identifier.
     *
     * This value maps to configuration keys and is used for logging/feedback.
     * Each generator command should return a unique, lowercase identifier.
     *
     * @return string Generator type (e.g., 'action', 'controller', 'model', 'service')
     */
    abstract protected function getGeneratorType(): string;

    /**
     * Get the stub file name to use for generation.
     *
     * Returns the stub filename **without** the `.stub` extension. The extension
     * is appended automatically in `generateFile()`. The path is relative to
     * the base stubs directory set in the constructor.
     *
     * Subclasses can implement conditional logic to select different stubs
     * based on command options (e.g., `--invokable` for single-action controllers).
     *
     * @return string Stub file name (e.g., 'action', 'controllers/api-controller')
     */
    abstract protected function getStubName(): string;

    // -------------------------------------------------------------------------
    //  Overridable Hooks — Subclasses may customize these
    // -------------------------------------------------------------------------

    /**
     * Get the sub-namespace for generated classes.
     *
     * Override this to specify a namespace segment appended to the base
     * module namespace. For example, returning `'Controllers'` would place
     * the generated class under `Pixielity\{Module}\Controllers\`.
     *
     * Returns an empty string by default (class goes in the module root namespace).
     *
     * @return string Sub-namespace segment or empty string
     */
    protected function getSubNamespace(): string
    {
        return '';
    }

    /**
     * Build the placeholder replacement map for stub rendering.
     *
     * Returns an associative array where keys are placeholder names (lowercase)
     * and values are the replacement strings. StubGenerator automatically
     * converts keys to uppercase and wraps them with `$...$` delimiters,
     * so `'namespace' => 'App\Models'` replaces `$NAMESPACE$` in the stub.
     *
     * Override this method in subclasses to add generator-specific placeholders.
     * Call `parent::getPlaceholders($arguments)` to include the defaults.
     *
     * ## Default Placeholders:
     * | Key              | Example Value              | Stub Token          |
     * |------------------|----------------------------|---------------------|
     * | namespace        | Pixielity\User\Controllers | $NAMESPACE$         |
     * | class            | UserController             | $CLASS$             |
     * | package          | user                       | $PACKAGE$           |
     * | module_namespace | Pixielity\User             | $MODULE_NAMESPACE$  |
     * | name             | UserController             | $NAME$              |
     * | module           | user                       | $MODULE$            |
     * | lower_name       | user                       | $LOWER_NAME$        |
     * | resource         | User                       | $RESOURCE$          |
     * | resources        | Users                      | $RESOURCES$         |
     *
     * @param  array<string, mixed>  $arguments  Parsed command arguments ('name', 'package')
     * @return array<string, string> Placeholder key-value pairs for stub replacement
     */
    protected function getPlaceholders(array $arguments): array
    {
        // Extract the two required arguments
        $name = $arguments['name'];
        $package = $arguments['package'];

        // Resolve the full namespace, class name, and base namespace
        // from the package name and input class name
        $namespace = $this->resolveNamespace($package);
        $className = $this->extractClassName($name);
        $baseNamespace = $this->getBaseNamespace($package);

        // Return the placeholder map — StubGenerator auto-uppercases keys
        // and wraps them with $...$ delimiters for replacement
        return [
            'namespace'        => $namespace,
            'class'            => $className,
            'package'          => $package,
            'module_namespace' => $baseNamespace,
            'name'             => $className,
            'module'           => $package,
            'lower_name'       => $package,
            'resource'         => $className,
            'resources'        => Str::plural($className),
        ];
    }

    // -------------------------------------------------------------------------
    //  Execution Pipeline
    // -------------------------------------------------------------------------

    /**
     * Execute the console command.
     *
     * Orchestrates the full generation pipeline:
     * 1. Collects command arguments via `getArguments()`
     * 2. Delegates to `handle()` for the actual generation
     * 3. Displays a success message on completion
     * 4. Catches and displays any exceptions as error output
     *
     * Overrides `BaseCommand::execute()` to provide generator-specific
     * error handling and success feedback. The parent's AOP hooks
     * (`before()` / `after()`) still run because `BaseCommand::execute()`
     * wraps this via the Symfony command lifecycle.
     *
     * @param  InputInterface   $input   Symfony console input (arguments and options)
     * @param  OutputInterface  $output  Symfony console output for writing messages
     * @return int Exit code — `self::SUCCESS` (0) or `self::FAILURE` (1)
     */
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // Collect parsed arguments from the command signature
            $arguments = $this->collectArguments();

            // Delegate to the generation handler (can be overridden by subclasses)
            $this->handle($arguments);

            // Report success to the user
            $this->info(Str::format('Successfully created %s!', $arguments['name']));

            return self::SUCCESS;
        } catch (Exception $exception) {
            // Display the error message without a full stack trace
            $this->error('Generation failed: ' . $exception->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Handle the generation logic.
     *
     * Default implementation generates a single file from a stub with a
     * spinner for visual feedback. Subclasses can override this to implement
     * multi-file generation, interactive prompts, or conditional logic.
     *
     * ## Example Override (multi-file generation):
     * ```php
     * protected function handle(array $arguments): void
     * {
     *     $this->spin(fn () => $this->generateFile($arguments), 'Creating model...');
     *     $this->spin(fn () => $this->generateMigration($arguments), 'Creating migration...');
     * }
     * ```
     *
     * @param  array<string, mixed>  $arguments  Parsed command arguments
     */
    protected function handle(array $arguments): void
    {
        // Wrap file generation in a spinner for user feedback
        $this->spin(
            fn() => $this->generateFile($arguments),
            'Generating file...'
        );
    }

    // -------------------------------------------------------------------------
    //  File Generation
    // -------------------------------------------------------------------------

    /**
     * Generate a single file from a stub template using StubGenerator.
     *
     * This is the core generation method that:
     * 1. Resolves the target file path from the arguments
     * 2. Checks if the file already exists (respects `--force` option)
     * 3. Builds the placeholder map via `getPlaceholders()`
     * 4. Creates a `StubGenerator` instance with the stub name and placeholders
     * 5. Saves the rendered output to the target directory
     *
     * StubGenerator handles:
     * - Loading the `.stub` file from the configured base path
     * - Replacing `$PLACEHOLDER$` tokens with provided values
     * - Creating the output directory if it doesn't exist
     *
     * @param  array<string, mixed>  $arguments  Parsed command arguments
     *
     * @throws RuntimeException If the target file exists and `--force` was not passed
     */
    protected function generateFile(array $arguments): void
    {
        // Determine where the generated file should be written
        $targetPath = $this->resolveTargetPath($arguments);

        // Prevent accidental overwrites unless --force is explicitly passed
        if (File::exists($targetPath) && ! $this->getInput()->getOption('force')) {
            throw new RuntimeException(
                Str::format('File already exists: %s. Use --force to overwrite.', $targetPath)
            );
        }

        // Build the placeholder replacement map from command arguments
        $placeholders = $this->getPlaceholders($arguments);

        // Use StubGenerator to load the stub, replace placeholders, and save.
        // The stub name has '.stub' appended (e.g., 'action' → 'action.stub').
        // saveTo() creates the target directory if it doesn't exist.
        StubGenerator::create($this->getStubName() . '.stub', $placeholders)
            ->saveTo(dirname($targetPath), basename($targetPath));
    }

    // -------------------------------------------------------------------------
    //  Argument & Input Helpers
    // -------------------------------------------------------------------------

    /**
     * Collect the parsed command arguments.
     *
     * Returns an associative array with the two standard generator arguments:
     * - `package` — The target module/package name (e.g., 'user', 'billing')
     * - `name` — The class name to generate (e.g., 'CreateUserAction')
     *
     * Subclasses that define additional arguments should override this method
     * and merge with the parent result.
     *
     * @return array<string, mixed> Associative array of argument name => value
     */
    protected function collectArguments(): array
    {
        return [
            'package' => $this->getInput()->getArgument('package'),
            'name'    => $this->getInput()->getArgument('name'),
        ];
    }

    /**
     * Get the Symfony InputInterface instance.
     *
     * Provides access to the raw console input for reading arguments,
     * options, and interactive input. This is the same `$this->input`
     * property from `Illuminate\Console\Command`, exposed as a typed
     * accessor for better IDE support.
     *
     * @return InputInterface The Symfony console input instance
     */
    protected function getInput(): InputInterface
    {
        return $this->input;
    }
}
