<?php

namespace Pixielity\Support;

use Pixielity\Support\Concerns\HasLaravelPaths;

/**
 * Path Helper.
 *
 * Pure path manipulation utility inspired by Node.js path module.
 * Provides static methods for working with file and directory paths.
 * Does NOT perform any filesystem operations - only string manipulation.
 *
 * ## Purpose:
 * - Manipulate path strings without touching the filesystem
 * - Provide cross-platform path operations
 * - Offer a clean, readable API for path resolution
 * - Support monorepo structure navigation (via HasLaravelPaths trait)
 *
 * ## Features:
 * - ✅ Pure path string manipulation (no filesystem I/O)
 * - ✅ Cross-platform support (Windows/Unix)
 * - ✅ Inspired by Node.js path module
 * - ✅ Laravel-specific path helpers (via trait)
 * - ✅ Monorepo-aware helpers (via trait)
 *
 * ## Core Path Methods:
 * ```php
 * // Join paths
 * Path::join('/path', 'to', 'file.php');
 * // Returns: /path/to/file.php
 *
 * // Get parent directory
 * Path::dirname('/path/to/file.php');
 * // Returns: /path/to
 *
 * // Get filename
 * Path::basename('/path/to/file.php');
 * // Returns: file.php
 *
 * // Normalize path
 * Path::normalize('/path/./to/../file.php');
 * // Returns: /path/file.php
 *
 * // Resolve absolute path
 * Path::resolve('path', 'to', 'file.php');
 * // Returns: /current/working/dir/path/to/file.php
 * ```
 *
 * ## Laravel-Specific Methods (from HasLaravelPaths trait):
 * ```php
 * // Monorepo navigation
 * Path::modules(__DIR__);            // /monorepo/modules
 * Path::monorepoRoot(__DIR__);       // /monorepo
 *
 * // Laravel directories
 * Path::storage($basePath);          // /app/storage
 * Path::config($srcPath);            // /app/src/config
 * Path::database($srcPath);          // /app/src/database
 * ```
 *
 * @since 1.0.0
 */
class Path
{
    use HasLaravelPaths;

    /**
     * The directory separator for the current platform.
     */
    public const SEPARATOR = DIRECTORY_SEPARATOR;

    /**
     * The path delimiter for the current platform.
     */
    public const DELIMITER = PATH_SEPARATOR;

    /**
     * Join all path segments together using the platform-specific separator.
     *
     * Normalizes the resulting path and resolves '..' and '.' segments.
     *
     * ## Example:
     * ```php
     * Path::join('/foo', 'bar', 'baz/asdf', 'quux', '..');
     * // Returns: /foo/bar/baz/asdf
     *
     * Path::join('foo', {}, 'bar');
     * // Throws TypeError
     * ```
     *
     * @param  string  ...$segments  Path segments to join
     * @return string The joined path
     */
    public static function join(string ...$segments): string
    {
        if ($segments === []) {
            return '.';
        }

        // Filter out empty segments
        $segments = Arr::filter($segments, fn ($seg): bool => $seg !== '');

        if ($segments === []) {
            return '.';
        }

        // Join with separator
        $joined = implode(self::SEPARATOR, $segments);

        // Normalize the result
        return static::normalize($joined);
    }

    /**
     * Resolve a sequence of paths into an absolute path.
     *
     * Processes the sequence from right to left, prepending each path until
     * an absolute path is constructed. If no absolute path is found, uses cwd.
     *
     * ## Example:
     * ```php
     * Path::resolve('/foo/bar', './baz');
     * // Returns: /foo/bar/baz
     *
     * Path::resolve('/foo/bar', '/tmp/file/');
     * // Returns: /tmp/file
     *
     * Path::resolve('wwwroot', 'static_files/png/', '../gif/image.gif');
     * // Returns: /current/working/dir/wwwroot/static_files/gif/image.gif
     * ```
     *
     * @param  string  ...$segments  Path segments to resolve
     * @return string The resolved absolute path
     */
    public static function resolve(string ...$segments): string
    {
        $resolvedPath = '';
        $resolvedAbsolute = false;

        // Process segments from right to left
        for ($i = count($segments) - 1; $i >= 0 && ! $resolvedAbsolute; $i--) {
            $path = $segments[$i];

            if ($path === '') {
                continue;
            }

            $resolvedPath = $path . ($resolvedPath !== '' ? self::SEPARATOR . $resolvedPath : '');
            $resolvedAbsolute = static::isAbsolute($resolvedPath);
        }

        // If still not absolute, prepend cwd
        if (! $resolvedAbsolute) {
            $resolvedPath = getcwd() . self::SEPARATOR . $resolvedPath;
        }

        return static::normalize($resolvedPath);
    }

    /**
     * Normalize a path, resolving '..' and '.' segments.
     *
     * When multiple slashes are found, they are replaced by a single slash.
     * Trailing slashes are preserved.
     *
     * ## Example:
     * ```php
     * Path::normalize('/foo/bar//baz/asdf/quux/..');
     * // Returns: /foo/bar/baz/asdf
     *
     * Path::normalize('C:\\temp\\\\foo\\bar\\..\\');
     * // Returns: C:\temp\foo\
     * ```
     *
     * @param  string  $path  The path to normalize
     * @return string The normalized path
     */
    public static function normalize(string $path): string
    {
        if ($path === '') {
            return '.';
        }

        // Check if path is absolute
        $isAbsolute = static::isAbsolute($path);

        // Check if path ends with separator
        $trailingSeparator = Str::endsWith($path, '/') || Str::endsWith($path, '\\');

        // Normalize separators to forward slash for processing
        $path = Str::replace('\\', '/', $path);

        // Split into segments
        $segments = explode('/', $path);
        $normalized = [];

        foreach ($segments as $segment) {
            if ($segment === '') {
                continue;
            }
            if ($segment === '.') {
                continue;
            }
            if ($segment === '..') {
                if ($normalized !== [] && end($normalized) !== '..') {
                    Arr::pop($normalized);
                } elseif (! $isAbsolute) {
                    $normalized[] = '..';
                }
            } else {
                $normalized[] = $segment;
            }
        }

        // Build result
        $result = implode(self::SEPARATOR, $normalized);

        // Add leading separator for absolute paths
        if ($isAbsolute) {
            // Handle Windows drive letters
            if (preg_match('/^[A-Z]:/i', $path)) {
                // Already has drive letter in first segment
            } else {
                $result = self::SEPARATOR . $result;
            }
        }

        // Add trailing separator if original had one
        if ($trailingSeparator && $result !== '' && ! Str::endsWith($result, self::SEPARATOR)) {
            $result .= self::SEPARATOR;
        }

        return $result !== '' ? $result : '.';
    }

    /**
     * Determine if a path is absolute.
     *
     * ## Example:
     * ```php
     * Path::isAbsolute('/foo/bar');  // true
     * Path::isAbsolute('foo/bar');   // false
     * Path::isAbsolute('C:/foo');    // true (Windows)
     * ```
     *
     * @param  string  $path  The path to check
     * @return bool True if path is absolute
     */
    public static function isAbsolute(string $path): bool
    {
        if ($path === '') {
            return false;
        }

        // Unix absolute path
        if ($path[0] === '/') {
            return true;
        }

        // Windows absolute path (C:\, D:\, etc.)
        return (bool) preg_match('/^[A-Z]:[\/\\\\]/i', $path);
    }

    /**
     * Return the directory name of a path (similar to Unix dirname).
     *
     * ## Example:
     * ```php
     * Path::dirname('/foo/bar/baz/asdf/quux');
     * // Returns: /foo/bar/baz/asdf
     *
     * Path::dirname('/foo/bar/baz/asdf/quux.html');
     * // Returns: /foo/bar/baz/asdf
     * ```
     *
     * @param  string  $path  The path
     * @return string The directory name
     */
    public static function dirname(string $path): string
    {
        if ($path === '') {
            return '.';
        }

        return dirname($path);
    }

    /**
     * Return the last portion of a path (similar to Unix basename).
     *
     * ## Example:
     * ```php
     * Path::basename('/foo/bar/baz/asdf/quux.html');
     * // Returns: quux.html
     *
     * Path::basename('/foo/bar/baz/asdf/quux.html', '.html');
     * // Returns: quux
     * ```
     *
     * @param  string  $path  The path
     * @param  string  $suffix  Optional suffix to remove
     * @return string The basename
     */
    public static function basename(string $path, string $suffix = ''): string
    {
        if ($path === '') {
            return '';
        }

        return basename($path, $suffix);
    }

    /**
     * Return the extension of the path.
     *
     * ## Example:
     * ```php
     * Path::extname('index.html');
     * // Returns: .html
     *
     * Path::extname('index.coffee.md');
     * // Returns: .md
     *
     * Path::extname('index.');
     * // Returns: .
     *
     * Path::extname('index');
     * // Returns: (empty string)
     * ```
     *
     * @param  string  $path  The path
     * @return string The extension (including the dot)
     */
    public static function extname(string $path): string
    {
        $basename = static::basename($path);

        if (in_array($basename, ['', '.', '..'], true)) {
            return '';
        }

        $lastDot = strrpos($basename, '.');

        if ($lastDot === false || $lastDot === 0) {
            return '';
        }

        return substr($basename, $lastDot);
    }

    /**
     * Return a path string from an object with dir, root, base, name, and ext.
     *
     * ## Example:
     * ```php
     * Path::format([
     *     'root' => '/',
     *     'dir' => '/home/user/dir',
     *     'base' => 'file.txt'
     * ]);
     * // Returns: /home/user/dir/file.txt
     *
     * Path::format([
     *     'dir' => '/home/user/dir',
     *     'name' => 'file',
     *     'ext' => '.txt'
     * ]);
     * // Returns: /home/user/dir/file.txt
     * ```
     *
     * @param  array  $pathObject  Object with path components
     * @return string The formatted path
     */
    public static function format(array $pathObject): string
    {
        $dir = $pathObject['dir'] ?? '';
        $base = $pathObject['base'] ?? '';

        // If base is not provided, construct from name and ext
        if ($base === '') {
            $name = $pathObject['name'] ?? '';
            $ext = $pathObject['ext'] ?? '';
            $base = $name . $ext;
        }

        if ($dir === '') {
            return $base;
        }

        return static::join($dir, $base);
    }

    /**
     * Return an object with properties representing significant elements of the path.
     *
     * ## Example:
     * ```php
     * Path::parse('/home/user/dir/file.txt');
     * // Returns: [
     * //   'root' => '/',
     * //   'dir' => '/home/user/dir',
     * //   'base' => 'file.txt',
     * //   'ext' => '.txt',
     * //   'name' => 'file'
     * // ]
     * ```
     *
     * @param  string  $path  The path to parse
     * @return array Path components
     */
    public static function parse(string $path): array
    {
        $root = '';

        // Determine root
        if (static::isAbsolute($path)) {
            $root = preg_match('/^([A-Z]:)[\/\\\\]/i', $path, $matches) ? $matches[1] . self::SEPARATOR : self::SEPARATOR;
        }

        $dir = static::dirname($path);
        $base = static::basename($path);
        $ext = static::extname($path);
        $name = $ext !== '' ? substr($base, 0, -strlen($ext)) : $base;

        return [
            'root' => $root,
            'dir' => $dir,
            'base' => $base,
            'ext' => $ext,
            'name' => $name,
        ];
    }

    /**
     * Solve the relative path from 'from' to 'to'.
     *
     * ## Example:
     * ```php
     * Path::relative('/data/orandea/test/aaa', '/data/orandea/impl/bbb');
     * // Returns: ../../impl/bbb
     * ```
     *
     * @param  string  $from  The source path
     * @param  string  $to  The destination path
     * @return string The relative path
     */
    public static function relative(string $from, string $to): string
    {
        $from = static::resolve($from);
        $to = static::resolve($to);

        if ($from === $to) {
            return '';
        }

        // Normalize separators
        $from = Str::replace('\\', '/', $from);
        $to = Str::replace('\\', '/', $to);

        $fromParts = explode('/', trim($from, '/'));
        $toParts = explode('/', trim($to, '/'));

        // Find common base
        $commonLength = 0;
        $minLength = min(count($fromParts), count($toParts));

        for ($i = 0; $i < $minLength; $i++) {
            if ($fromParts[$i] !== $toParts[$i]) {
                break;
            }
            $commonLength++;
        }

        // Build relative path
        $upCount = count($fromParts) - $commonLength;
        $relativeParts = Arr::fill(0, $upCount, '..');
        $relativeParts = Arr::merge($relativeParts, Arr::slice($toParts, $commonLength));

        return implode(self::SEPARATOR, $relativeParts);
    }

    /**
     * Get the parent directory of a path.
     *
     * Alias for dirname() for better readability.
     *
     * ## Example:
     * ```php
     * Path::parent('/path/to/file.php');
     * // Returns: /path/to
     * ```
     *
     * @param  string  $path  The path
     * @return string The parent directory
     */
    public static function parent(string $path): string
    {
        return static::dirname($path);
    }

    /**
     * Go up multiple directory levels from the given path.
     *
     * ## Example:
     * ```php
     * Path::up('/path/to/file.php', 2);
     * // Returns: /path
     * ```
     *
     * @param  string  $path  The starting path
     * @param  int  $levels  Number of levels to go up
     * @return string The path after going up
     */
    public static function up(string $path, int $levels = 1): string
    {
        return dirname($path, $levels);
    }
}
