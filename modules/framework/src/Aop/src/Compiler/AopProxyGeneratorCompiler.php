<?php

declare(strict_types=1);

/**
 * AOP Proxy Generator Compiler.
 *
 * Generates proxy classes from the InterceptorMap built by the AopScannerCompiler.
 * Each proxy extends the original class and overrides intercepted methods to
 * route calls through the InterceptorEngine.
 *
 * Proxies are written atomically to the configured proxy directory and
 * autoloaded via the SPL autoloader registered by AopServiceProvider.
 *
 * Uses ReflectionClass at BUILD TIME ONLY to read original method signatures.
 * Generated proxy code uses zero reflection at runtime.
 *
 * @category Compiler
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Compiler;

use Illuminate\Container\Attributes\Config;
use Illuminate\Filesystem\Filesystem;
use Pixielity\Aop\Registry\InterceptorMap;
use Pixielity\Aop\Support\ProxyClassNamer;
use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

/**
 * Compiler pass that generates proxy classes from the InterceptorMap.
 */
#[AsCompiler(
    priority: 70,
    description: 'Generate AOP proxy classes from InterceptorMap',
    phase: CompilerPhase::GENERATION,
)]
class AopProxyGeneratorCompiler implements CompilerInterface
{
    public function __construct(
        #[Config('aop.enabled', true)]
        private readonly bool $aopEnabled = true,
        #[Config('aop.proxy_directory', 'storage/framework/aop')]
        private readonly string $proxyDirectory = 'storage/framework/aop',
    ) {}

    /**
     * {@inheritDoc}
     *
     * Reads the InterceptorMap from the context (set by AopScannerCompiler),
     * generates a proxy class for each intercepted target class, and writes
     * them to the proxy directory.
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        if (! $this->aopEnabled) {
            return CompilerResult::skipped('AOP is disabled');
        }

        /**
         * @var InterceptorMap|null $map
         */
        $map = $context->get('aop.interceptor_map');

        if ($map === null || $map->isEmpty()) {
            return CompilerResult::success('No interceptions — no proxies to generate');
        }

        $outputDir = $this->proxyDirectory;
        $filesystem = $context->container->make(Filesystem::class);

        // Ensure output directory exists
        if (! $filesystem->isDirectory($outputDir)) {
            $filesystem->makeDirectory($outputDir, 0755, true);
        }

        // Clean existing proxies
        $filesystem->cleanDirectory($outputDir);

        $generated = 0;

        // Sort target classes alphabetically for deterministic output
        $entries = $map->entries;
        ksort($entries);

        foreach ($entries as $targetClass => $methodInterceptors) {
            if (! class_exists($targetClass)) {
                continue;
            }

            $proxyClass = ProxyClassNamer::generate($targetClass);
            $shortName = ProxyClassNamer::shortName($proxyClass);
            $filePath = $outputDir . '/' . $shortName . '.php';

            $sourceCode = $this->generateProxySource($targetClass, $proxyClass, $methodInterceptors);

            $filesystem->put($filePath, $sourceCode);
            $generated++;
        }

        return CompilerResult::success(
            message: "Generated {$generated} proxy classes",
            metrics: ['proxies' => $generated],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'AOP Proxy Generator';
    }

    /**
     * Generate the complete PHP source code for a proxy class.
     *
     * The proxy extends the original class, overriding only intercepted
     * methods to route through the InterceptorEngine. Non-intercepted
     * methods fall through to the parent.
     *
     * @param  string  $targetClass  The original class FQCN.
     * @param  string  $proxyClassName  The proxy class FQCN.
     * @param  array  $methodInterceptors  Map of [methodName => list<InterceptorEntry>].
     * @return string The complete PHP source code.
     */
    private function generateProxySource(string $targetClass, string $proxyClassName, array $methodInterceptors): string
    {
        $proxyNamespace = substr($proxyClassName, 0, (int) strrpos($proxyClassName, '\\'));
        $proxyShortName = substr($proxyClassName, (int) strrpos($proxyClassName, '\\') + 1);

        $reflection = new ReflectionClass($targetClass);

        // Sort methods alphabetically for deterministic output
        $methodNames = array_keys($methodInterceptors);
        sort($methodNames);

        // Build method overrides
        $methodOverrides = [];

        foreach ($methodNames as $methodName) {
            if ($reflection->hasMethod($methodName)) {
                $methodOverrides[] = $this->buildMethodOverride($reflection->getMethod($methodName));
            }
        }

        $methodsCode = implode("\n\n", $methodOverrides);
        $timestamp = date('c');

        return <<<PHP
        <?php

        declare(strict_types=1);

        // Auto-generated by AOP Engine at {$timestamp}
        // DO NOT EDIT — regenerated by php artisan app:compile

        namespace {$proxyNamespace};

        use {$targetClass};
        use Pixielity\\Aop\\Engine\\InterceptorEngine;

        /**
         * AOP Proxy for \\{$targetClass}.
         *
         * @internal Auto-generated. Do not modify.
         */
        class {$proxyShortName} extends {$reflection->getShortName()}
        {
        {$methodsCode}
        }
        PHP . "\n";
    }

    /**
     * Build a method override that routes through the InterceptorEngine.
     *
     * @param  ReflectionMethod  $method  The original method reflection.
     * @return string PHP source code for the method override.
     */
    private function buildMethodOverride(ReflectionMethod $method): string
    {
        $name = $method->getName();
        $params = $this->buildParameterList($method->getParameters());
        $returnType = $this->buildReturnType($method);
        $isVoid = $this->isVoidReturn($method);
        $returnStmt = $isVoid ? '' : 'return ';

        $closureParams = $params;
        $parentArgs = $this->buildParentCallArgs($method->getParameters());

        return <<<PHP
            public function {$name}({$params}){$returnType}
            {
                {$returnStmt}app(InterceptorEngine::class)->execute(
                    target: \$this,
                    method: '{$name}',
                    args: func_get_args(),
                    original: function ({$closureParams}){$returnType} {
                        {$returnStmt}parent::{$name}({$parentArgs});
                    },
                );
            }
        PHP;
    }

    /**
     * Build the parameter list string preserving full type information.
     *
     * @param  array<ReflectionParameter>  $params  Method parameters.
     * @return string Comma-separated parameter declarations.
     */
    private function buildParameterList(array $params): string
    {
        $parts = [];

        foreach ($params as $param) {
            $part = '';
            $type = $param->getType();

            if ($type !== null) {
                $part .= $this->buildTypeString($type) . ' ';
            }

            if ($param->isVariadic()) {
                $part .= '...';
            }

            if ($param->isCompileredByReference()) {
                $part .= '&';
            }

            $part .= '$' . $param->getName();

            if (! $param->isVariadic() && $param->isDefaultValueAvailable()) {
                $part .= ' = ' . $this->exportDefaultValue($param);
            }

            $parts[] = $part;
        }

        return implode(', ', $parts);
    }

    /**
     * Build a type string from a ReflectionType.
     *
     * @param  \ReflectionType  $type  The reflection type.
     * @return string PHP type declaration string.
     */
    private function buildTypeString(\ReflectionType $type): string
    {
        if ($type instanceof ReflectionNamedType) {
            $name = $type->getName();

            if (! $type->isBuiltin()) {
                $name = '\\' . $name;
            }

            if ($type->allowsNull() && $name !== 'mixed' && $name !== 'null') {
                $name = '?' . $name;
            }

            return $name;
        }

        if ($type instanceof ReflectionUnionType) {
            return implode('|', array_map(fn (\ReflectionType $t) => $this->buildTypeString($t), $type->getTypes()));
        }

        if ($type instanceof ReflectionIntersectionType) {
            return implode('&', array_map(fn (\ReflectionType $t) => $this->buildTypeString($t), $type->getTypes()));
        }

        return (string) $type;
    }

    /**
     * Build the return type declaration for a method.
     *
     * @param  ReflectionMethod  $method  The reflected method.
     * @return string Return type declaration (e.g. ': string').
     */
    private function buildReturnType(ReflectionMethod $method): string
    {
        $returnType = $method->getReturnType();

        return $returnType !== null ? ': ' . $this->buildTypeString($returnType) : '';
    }

    /**
     * Check if a method has a void return type.
     *
     * @param  ReflectionMethod  $method  The reflected method.
     * @return bool True if void.
     */
    private function isVoidReturn(ReflectionMethod $method): bool
    {
        $type = $method->getReturnType();

        return $type instanceof ReflectionNamedType && $type->getName() === 'void';
    }

    /**
     * Build the argument forwarding expression for the parent call.
     *
     * @param  array<ReflectionParameter>  $params  Method parameters.
     * @return string Comma-separated argument expressions.
     */
    private function buildParentCallArgs(array $params): string
    {
        return implode(', ', array_map(
            fn (ReflectionParameter $p) => ($p->isVariadic() ? '...$' : '$') . $p->getName(),
            $params,
        ));
    }

    /**
     * Export a parameter's default value as PHP source code.
     *
     * @param  ReflectionParameter  $param  The parameter.
     * @return string PHP source representation.
     */
    private function exportDefaultValue(ReflectionParameter $param): string
    {
        if ($param->isDefaultValueConstant()) {
            return (string) $param->getDefaultValueConstantName();
        }

        $value = $param->getDefaultValue();

        return match (true) {
            $value === null => 'null',
            \is_bool($value) => $value ? 'true' : 'false',
            \is_int($value), \is_float($value) => (string) $value,
            \is_string($value) => var_export($value, true),
            \is_array($value) && $value === [] => '[]',
            default => var_export($value, true),
        };
    }
}
