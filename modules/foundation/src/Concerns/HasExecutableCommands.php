<?php

namespace Pixielity\Foundation\Concerns;

use Illuminate\Support\ProcessUtils;

use function is_array;

use Pixielity\Foundation\Enums\ExecutableType;
use Pixielity\Foundation\Exceptions\InvalidArgumentException;
use Pixielity\Support\Arr;
use Pixielity\Support\Str;

/**
 * Has Executable Commands Trait.
 *
 * Provides executable command formatting functionality for the application.
 * Allows formatting commands for different execution types (node, tsx, yarn, npm, php).
 *
 * ## Purpose:
 * - Format commands for different executables
 * - Support multiple execution types
 * - Handle command escaping and formatting
 *
 * ## Features:
 * - ✅ Node.js command formatting
 * - ✅ TSX command formatting
 * - ✅ Yarn command formatting
 * - ✅ NPM command formatting
 * - ✅ PHP command formatting
 * - ✅ Automatic argument escaping
 *
 * @since 1.0.0
 */
trait HasExecutableCommands
{
    /**
     * Format the given command as a fully-qualified executable command.
     *
     * @param  string|array<int, string>  $string  The command string to format (e.g., the command to run).
     * @param  string  $executionType  The type of executable (node, tsx, yarn, npm, php).
     * @return string The formatted command string.
     *
     * @throws InvalidArgumentException if the execution type is not supported.
     */
    public static function formatExecutableCommandString(string|array $string, string $executionType): string
    {
        /** @var self $app */
        $app = app();

        // If the command is an array, format it into a string
        // If the command is an array, wrap each part in single quotes and join into a string
        $commandString = is_array($string)
            ? implode(' ', Arr::map($string, fn ($part) => ProcessUtils::escapeArgument($part)))
            : ProcessUtils::escapeArgument($string);

        // Determine the executable based on the provided execution type using match
        $executable = match ($executionType) {
            ExecutableType::TSX() => $app->tsxBinary(),
            ExecutableType::YARN() => $app->yarnBinary(),
            ExecutableType::NODE() => $app->nodeBinary(),
            ExecutableType::PHP() => $app->formatCommandString($commandString),
            ExecutableType::NPM() => Str::format('%s %s, %s', $app->npmBinary(), 'run', '--'),
            default => throw new InvalidArgumentException('Unsupported execution type: ' . $executionType),
        };

        // Format and return the final command string
        return Str::format('%s %s', $executable, $commandString);
    }

    /**
     * Format the given command as a fully-qualified executable command.
     *
     * @param  string  $string
     */
    public function formatCommandString($string): string
    {
        return Str::format('%s %s %s', $this->phpBinary(), $this->laravelBinary(), $string);
    }
}
