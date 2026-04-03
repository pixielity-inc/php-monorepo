<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Solutions;

use Illuminate\Support\Facades\App;
use Pixielity\Support\Reflection;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Class AiSolutionProvider.
 *
 * This class implements the HasSolutionForThrowable interface to provide
 * solutions for throwable exceptions in the Pixielity Integration .
 */
class AiSolutionProvider implements HasSolutionsForThrowable
{
    /**
     * Determines if the provider can solve the given throwable.
     *
     * @param  Throwable  $throwable  The throwable exception to check.
     * @return bool True if the provider can solve the throwable; otherwise, false.
     */
    public function canSolve(Throwable $throwable): bool
    {
        // Skip AI solutions for common client errors where a solution is obvious or non-technical
        if (Reflection::implements($throwable, HttpException::class)) {
            $statusCode = $throwable->getStatusCode();
            if (in_array($statusCode, [401, 403, 404, 429])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieves an array of solutions for the given throwable.
     *
     * @param  Throwable  $throwable  The throwable exception for which to retrieve solutions.
     * @return array<int, mixed> An array of solutions corresponding to the throwable.
     */
    public function getSolutions(Throwable $throwable): array
    {
        // Return an array containing a new AiSolution instance for the given throwable.
        return [App::make(AiSolution::class, ['throwable' => $throwable])];
    }
}
