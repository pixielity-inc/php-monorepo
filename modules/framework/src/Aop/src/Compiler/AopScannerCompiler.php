<?php

declare(strict_types=1);

/**
 * AOP Scanner Compiler.
 *
 * Scans all configured directories for classes and methods annotated with
 * interceptor attributes (#[Before], #[After], #[Around] and their subclasses),
 * builds the InterceptorMap, and persists it to the cache file.
 *
 * Supports both method-level and class-level attributes. Class-level attributes
 * are propagated to all public methods of the class (unless the method has
 * #[IgnoreInterceptor]).
 *
 * Uses ReflectionClass at BUILD TIME ONLY to read attributes.
 * The generated InterceptorMap is loaded at runtime via require() — zero
 * reflection in the hot path.
 *
 * @category Compiler
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Compiler;

use Illuminate\Container\Attributes\Config;
use Pixielity\Aop\Attributes\IgnoreInterceptor;
use Pixielity\Aop\Attributes\InterceptedBy;
use Pixielity\Aop\Attributes\InterceptorAttribute;
use Pixielity\Aop\Contracts\InterceptorInterface;
use Pixielity\Aop\Exceptions\InvalidInterceptorException;
use Pixielity\Aop\Registry\InterceptorRegistry;
use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;
use SplFileInfo;

/**
 * Compiler pass that scans for interceptor attributes and builds the InterceptorMap.
 */
#[AsCompiler(
    priority: 60,
    description: 'Scan interceptor attributes and build InterceptorMap cache',
    phase: CompilerPhase::GENERATION,
)]
class AopScannerCompiler implements CompilerInterface
{
    public function __construct(
        #[Config('aop.enabled', true)]
        private readonly bool $aopEnabled = true,
        #[Config('aop.scan_directories', [])]
        private readonly array $scanDirectories = [],
        #[Config('aop.global_interceptors', [])]
        private readonly array $globalInterceptors = [],
    ) {}

    /**
     * {@inheritDoc}
     *
     * Scans configured directories for methods with interceptor attributes,
     * builds the InterceptorMap, persists to cache, and stores the map in
     * the context for the proxy generator pass.
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        if (! $this->aopEnabled) {
            return CompilerResult::skipped('AOP is disabled');
        }

        $directories = (array) $this->scanDirectories;
        $globalInterceptors = (array) $this->globalInterceptors;

        // Scan all directories for interceptor attributes
        $interceptions = $this->scan($directories);

        // Build the InterceptorMap
        /**
         * @var InterceptorRegistry $registry
         */
        $registry = $context->container->make(InterceptorRegistry::class);
        $map = $registry->build($interceptions, $globalInterceptors);

        // Persist to cache file
        $registry->persist($map);

        // Store in context for the proxy generator pass
        $context->set('aop.interceptor_map', $map);

        // Count stats
        $classCount = \count($map->getTargetClasses());
        $methodCount = 0;

        foreach ($map->entries as $methods) {
            $methodCount += \count($methods);
        }

        return CompilerResult::success(
            message: "Built InterceptorMap: {$classCount} classes, {$methodCount} methods",
            metrics: ['classes' => $classCount, 'methods' => $methodCount],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'AOP Scanner';
    }

    /**
     * Scan directories for methods with interceptor attributes.
     *
     * Uses ReflectionClass at BUILD TIME to read method-level attributes.
     * Produces a nested array: [targetClass => [method => [record, ...]]].
     *
     * @param  array<string>  $directories  Absolute paths to scan.
     * @return array<class-string, array<string, list<array>>> The discovered interceptions.
     */
    private function scan(array $directories): array
    {
        $interceptions = [];
        $phpFiles = $this->collectPhpFiles($directories);

        foreach ($phpFiles as $filePath) {
            $className = $this->extractClassName($filePath);

            if ($className === null || ! class_exists($className)) {
                continue;
            }

            try {
                $reflection = new ReflectionClass($className);

                // Skip abstract classes and interfaces
                if ($reflection->isAbstract() || $reflection->isInterface()) {
                    continue;
                }

                // Collect class-level interceptor attributes (applied to all public methods)
                $classInterceptors = $this->extractClassInterceptors($reflection);

                // Scan public methods declared in this class (not inherited)
                foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                    if ($method->getDeclaringClass()->getName() !== $className) {
                        continue;
                    }

                    // Skip magic methods
                    if (str_starts_with($method->getName(), '__')) {
                        continue;
                    }

                    // Check for #[IgnoreInterceptor] on the method
                    $ignoreAttributes = $method->getAttributes(IgnoreInterceptor::class);

                    // Apply class-level interceptors to this method (unless ignored)
                    if (empty($ignoreAttributes)) {
                        foreach ($classInterceptors as $classInterceptor) {
                            $interceptions[$className][$method->getName()][] = $classInterceptor;
                        }
                    }

                    $this->processMethod($className, $method, $interceptions);
                }
            } catch (InvalidInterceptorException $e) {
                throw $e;
            } catch (\Throwable) {
                // Skip classes that can't be reflected (missing deps, etc.)
                continue;
            }
        }

        return $interceptions;
    }

    /**
     * Process a single method for interceptor attributes.
     *
     * @param  string  $className  The target class FQCN.
     * @param  ReflectionMethod  $method  The reflected method.
     * @param  array  $interceptions  The interceptions array (modified by reference).
     */
    private function processMethod(string $className, ReflectionMethod $method, array &$interceptions): void
    {
        $attributes = $method->getAttributes(InterceptorAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($attributes as $reflectionAttribute) {
            /**
             * @var InterceptorAttribute $attribute
             */
            $attribute = $reflectionAttribute->newInstance();
            $interceptorClass = $this->resolveInterceptorClass($attribute);

            if ($interceptorClass === null) {
                continue;
            }

            // Validate the interceptor class implements InterceptorInterface
            if (! is_subclass_of($interceptorClass, InterceptorInterface::class)) {
                throw InvalidInterceptorException::classDoesNotImplementInterface(
                    $interceptorClass,
                    $className,
                    $method->getName(),
                );
            }

            $interceptions[$className][$method->getName()][] = [
                'interceptorClass' => $interceptorClass,
                'priority' => $attribute->priority,
                'whenCondition' => $attribute->when,
                'parameters' => $this->extractParameters($attribute),
            ];
        }
    }

    /**
     * Extract class-level interceptor attributes.
     *
     * Class-level interceptor attributes (e.g. #[RequireAuth] on a class)
     * are propagated to ALL public methods of that class. This method reads
     * them and returns pre-built interception records.
     *
     * @param  ReflectionClass  $reflection  The reflected class.
     * @return list<array{interceptorClass: string, priority: int, whenCondition: string|null, parameters: array}> The class-level interception records.
     */
    private function extractClassInterceptors(ReflectionClass $reflection): array
    {
        $records = [];
        $attributes = $reflection->getAttributes(InterceptorAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($attributes as $reflectionAttribute) {
            /**
             * @var InterceptorAttribute $attribute
             */
            $attribute = $reflectionAttribute->newInstance();
            $interceptorClass = $this->resolveInterceptorClass($attribute);

            if ($interceptorClass === null) {
                continue;
            }

            if (! is_subclass_of($interceptorClass, InterceptorInterface::class)) {
                throw InvalidInterceptorException::classDoesNotImplementInterface(
                    $interceptorClass,
                    $reflection->getName(),
                    '(class-level)',
                );
            }

            $records[] = [
                'interceptorClass' => $interceptorClass,
                'priority' => $attribute->priority,
                'whenCondition' => $attribute->when,
                'parameters' => $this->extractParameters($attribute),
            ];
        }

        return $records;
    }

    /**
     * Resolve the interceptor class from an attribute.
     *
     * Resolution order:
     *   1. If the attribute has a `$class` property (generic Before/After/Around) → use it
     *   2. Read #[InterceptedBy] meta-attribute from the attribute's class → use it
     *   3. Return null (no interceptor found)
     *
     * @param  InterceptorAttribute  $attribute  The attribute instance.
     * @return class-string<InterceptorInterface>|null The interceptor class, or null.
     */
    private function resolveInterceptorClass(InterceptorAttribute $attribute): ?string
    {
        // Strategy 1: Generic attributes (Before, After, Around) have a $class property
        $reflection = new \ReflectionObject($attribute);

        if ($reflection->hasProperty('class')) {
            $classProp = $reflection->getProperty('class');
            $value = $classProp->getValue($attribute);

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        // Strategy 2: Custom attributes use #[InterceptedBy] meta-attribute
        $attributeReflection = new ReflectionClass($attribute);
        $interceptedByAttrs = $attributeReflection->getAttributes(InterceptedBy::class);

        if ($interceptedByAttrs !== []) {
            /**
             * @var InterceptedBy $interceptedBy
             */
            $interceptedBy = $interceptedByAttrs[0]->newInstance();

            return $interceptedBy->interceptor;
        }

        return null;
    }

    /**
     * Extract interceptor-specific parameters from an attribute.
     *
     * Reads all public properties except 'priority', 'when', and 'class'
     * (which are framework-level, not interceptor-specific).
     *
     * @param  InterceptorAttribute  $attribute  The attribute instance.
     * @return array<string, mixed> The extracted parameters.
     */
    private function extractParameters(InterceptorAttribute $attribute): array
    {
        $reflection = new \ReflectionObject($attribute);
        $params = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();

            // Skip framework-level properties
            if (\in_array($name, ['priority', 'when', 'class'], true)) {
                continue;
            }

            // Handle 'params' array (from generic Before/After/Around)
            if ($name === 'params') {
                $value = $property->getValue($attribute);

                if (\is_array($value)) {
                    $params = [...$params, ...$value];
                }

                continue;
            }

            $params[$name] = $property->getValue($attribute);
        }

        return $params;
    }

    /**
     * Collect all PHP files from the given directories recursively.
     *
     * @param  array<string>  $directories  Absolute paths to scan.
     * @return array<string> Absolute file paths.
     */
    private function collectPhpFiles(array $directories): array
    {
        $files = [];

        foreach ($directories as $directory) {
            if (! is_dir($directory)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            );

            /**
             * @var SplFileInfo $file
             */
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $files[] = $file->getRealPath();
                }
            }
        }

        return $files;
    }

    /**
     * Extract the FQCN from a PHP file using tokenization.
     *
     * @param  string  $filePath  Absolute path to the PHP file.
     * @return string|null The FQCN, or null if not found.
     */
    private function extractClassName(string $filePath): ?string
    {
        $contents = file_get_contents($filePath);

        if ($contents === false) {
            return null;
        }

        $namespace = null;
        $class = null;
        $tokens = token_get_all($contents);
        $count = \count($tokens);

        for ($i = 0; $i < $count; $i++) {
            if (! \is_array($tokens[$i])) {
                continue;
            }

            // Extract namespace
            if ($tokens[$i][0] === T_NAMESPACE) {
                $namespace = '';

                for ($j = $i + 1; $j < $count; $j++) {
                    if (\is_array($tokens[$j]) && \in_array($tokens[$j][0], [T_NAME_QUALIFIED, T_STRING], true)) {
                        $namespace .= $tokens[$j][1];
                    } elseif ($tokens[$j] === ';' || $tokens[$j] === '{') {
                        break;
                    }
                }
            }

            // Extract class name (skip ::class references)
            if ($tokens[$i][0] === T_CLASS) {
                if ($i > 0 && \is_array($tokens[$i - 1]) && $tokens[$i - 1][0] === T_DOUBLE_COLON) {
                    continue;
                }

                for ($j = $i + 1; $j < $count; $j++) {
                    if (\is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                        $class = $tokens[$j][1];

                        break;
                    }
                }

                break;
            }
        }

        if ($class === null) {
            return null;
        }

        return $namespace ? "{$namespace}\\{$class}" : $class;
    }
}
