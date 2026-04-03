<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Solutions;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use OpenAI\Laravel\Facades\OpenAI;
use Pixielity\Support\Reflection;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;
use Spatie\Ignition\Contracts\Solution;
use Throwable;

/**
 * Class AiSolution.
 *
 * This class implements the Solution interface for handling exceptions
 * and providing solutions for errors in the Pixielity Integration .
 */
class AiSolution implements Solution
{
    /**
     * The proposed solution to resolve an error or exception.
     */
    protected string $solution;

    /**
     * AiSolution constructor.
     *
     * @param  Throwable  $throwable  The throwable exception that triggered the solution.
     *                                This constructor initializes the solution by retrieving it from cache
     *                                or generating a new one if not already cached.
     */
    public function __construct(
        protected Throwable $throwable
    ) {
        // Retrieve the solution from cache or generate a new one if not cached
        $this->solution = $this->retrieveSolutionFromCache();
    }

    /**
     * Gets the title of the solution.
     *
     * @return string The title of the solution.
     *                This method should return a concise title for the solution, but
     *                it has not yet been implemented.
     */
    public function getSolutionTitle(): string
    {
        return 'Ai Solution';
    }

    /**
     * Gets the description of the solution.
     *
     * @return string The description of the solution.
     *                This method should return a detailed description for the solution, but
     *                it has not yet been implemented.
     */
    public function getSolutionDescription(): string
    {
        return '';
    }

    /**
     * Gets the documentation links related to the solution.
     *
     * @return array<string, string> An array of documentation links.
     *                               This method should return an array of relevant links for further
     *                               documentation related to the solution, but it has not yet been implemented.
     */
    public function getDocumentationLinks(): array
    {
        // Return an empty array as a placeholder
        return [];
    }

    /**
     * Retrieves the solution from cache or generates a new one if not found.
     *
     * @return mixed The generated solution text.
     *               This method checks if a solution for the current throwable is cached.
     *               If it is cached, it returns the cached solution. Otherwise, it generates
     *               a new solution using OpenAI's API.
     */
    private function retrieveSolutionFromCache(): mixed
    {
        return Cache::remember(
            'solution-' . sha1($this->throwable->getTraceAsString()),  // Create a unique cache key
            Date::now()->addHour(),  // Cache the solution for 1 hour
            fn (): string => $this->generateSolution(),  // Generate a new solution if not cached
        ) ?: '';
    }

    /**
     * Generates the solution using OpenAI's GPT-4 model.
     *
     * This method makes a call to the OpenAI API using the GPT-4 model,
     * with a prompt derived from the throwable's information. The result
     * is a generated solution text to help resolve the issue.
     *
     * @return string The generated solution text.
     *                This method uses GPT-4 to produce a deterministic
     *                solution based on the throwable's context.
     */
    private function generateSolution(): string
    {
        // Check if OpenAI is configured to avoid crashes in local/dev environments
        if (! config('openai.api_key')) {
            return 'OpenAI is not configured. Please set OPENAI_API_KEY in your .env file.';
        }

        try {
            return OpenAI::completions()->create([
                'model' => 'gpt-4',  // Specify GPT-4 as the model for the API call
                'prompt' => $this->generatePrompt($this->throwable),  // Generate a prompt using throwable data
                'max_tokens' => 100,  // Limit the response to 100 tokens for concise output
                'temperature' => 0,  // Set temperature to 0 for consistent and deterministic output
            ])->choices[0]->text ?? 'No solution generated.';
        } catch (Throwable $throwable) {
            return 'Failed to generate AI solution: ' . $throwable->getMessage();
        }
    }

    /**
     * Retrieves the application frame from the backtrace for the given throwable.
     *
     * @param  Throwable  $throwable  The throwable to analyze.
     * @return Frame|null The application frame or null if not found.
     *                    This method analyzes the backtrace of the given throwable and retrieves
     *                    the first application frame, which contains the file and line number
     *                    where the error occurred.
     */
    private function getApplicationFrame(Throwable $throwable): ?Frame
    {
        // Create a backtrace for the throwable, focusing on the application path
        $backtrace = Backtrace::createForThrowable($throwable);

        // Retrieve all frames from the backtrace
        $frames = $backtrace->frames();

        // Return the first application frame if it exists, or null
        return $frames[$backtrace->firstApplicationFrameIndex()] ?? null;
    }

    /**
     * Generates a prompt for OpenAI based on the throwable.
     *
     * @param  Throwable  $throwable  The throwable to analyze.
     * @return string The generated prompt.
     *                This method generates a prompt for OpenAI's API based on the
     *                application frame and the message from the throwable. It formats
     *                the code snippet and includes the relevant context to assist in generating
     *                a solution.
     */
    private function generatePrompt(Throwable $throwable): string
    {
        // Retrieve the application frame for the throwable
        $applicationFrame = $this->getApplicationFrame($throwable);

        if (! Reflection::implements($applicationFrame, Frame::class)) {
            return 'No application frame found in backtrace.';
        }

        // Get 15 lines of context
        $snippet = $applicationFrame->getSnippet(15);

        // Render the prompt view with the necessary data
        return view('foundation::prompts.solution', [
            'snippet' => collect($snippet)->map(fn (string $line, int $number): string => $number . ' ' . $line)->join(PHP_EOL),
            'file' => $applicationFrame->file,
            'line' => $applicationFrame->lineNumber,
            'exception' => $throwable->getMessage(),
        ])->render();
    }
}
