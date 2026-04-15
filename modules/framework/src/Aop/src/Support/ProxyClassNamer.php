<?php

declare(strict_types=1);

/**
 * Proxy Class Namer.
 *
 * Generates deterministic proxy class names from target class FQCNs.
 * All generated proxies live under a single namespace to simplify
 * autoloading and avoid conflicts with the original classes.
 *
 * ## Naming Convention:
 *   Target:  App\Services\ProductService
 *   Proxy:   Pixielity\Aop\Generated\App_Services_ProductService
 *
 * The proxy class extends the original, so instanceof checks pass.
 *
 * @category Support
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Support;

/**
 * Generates proxy class names from target class FQCNs.
 */
final class ProxyClassNamer
{
    /**
     * @var string The namespace prefix for all generated proxy classes.
     */
    public const PROXY_NAMESPACE = 'Pixielity\\Aop\\Generated';

    /**
     * Generate a proxy class FQCN from a target class FQCN.
     *
     * Replaces namespace separators with underscores and prepends
     * the proxy namespace.
     *
     * @param  string  $targetClass  The original class FQCN.
     * @return string The proxy class FQCN.
     */
    public static function generate(string $targetClass): string
    {
        $safeName = str_replace('\\', '_', $targetClass);

        return self::PROXY_NAMESPACE . '\\' . $safeName;
    }

    /**
     * Extract the short class name from a proxy FQCN.
     *
     * @param  string  $proxyClass  The proxy class FQCN.
     * @return string The short name (e.g. 'App_Services_ProductService').
     */
    public static function shortName(string $proxyClass): string
    {
        $prefix = self::PROXY_NAMESPACE . '\\';

        if (str_starts_with($proxyClass, $prefix)) {
            return substr($proxyClass, \strlen($prefix));
        }

        return $proxyClass;
    }

    /**
     * Resolve the original target class from a proxy FQCN.
     *
     * @param  string  $proxyClass  The proxy class FQCN.
     * @return string The original target class FQCN.
     */
    public static function resolveTarget(string $proxyClass): string
    {
        $shortName = self::shortName($proxyClass);

        return str_replace('_', '\\', $shortName);
    }

    /**
     * Check if a class name is a generated proxy.
     *
     * @param  string  $class  The class FQCN to check.
     * @return bool True if the class is a generated proxy.
     */
    public static function isProxy(string $class): bool
    {
        return str_starts_with($class, self::PROXY_NAMESPACE . '\\');
    }
}
