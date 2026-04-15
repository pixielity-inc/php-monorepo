<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Solutions;

use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;
use Spatie\ErrorSolutions\Contracts\Solution;
use Throwable;

/**
 * Class AiSolution.
 *
 * Implements the Spatie Ignition Solution interface to provide AI-generated
 * solutions for exceptions using OpenAI's chat completions API.
 */
class AiSolution implements Solution
{
    /**
     * The AI-generated solution text.
     */
    protected string $solution;

    /**
     * @param  Throwable  $throwable  The exception that triggered this solution.
     * @param  string|null  $openAiApiKey  The OpenAI API key from config.
     */
    public function __construct(
        protected Throwable $throwable,
        #[Config('openai.api_key')]
        protected ?string $openAiApiKey = null,
    ) {
        $this->solution = $this->retrieveSolutionFromCache();
    }

    /**
     * {@inheritDoc}
     */
    public function getSolutionTitle(): string
    {
        return 'AI Suggested Solution';
    }

    /**
     * {@inheritDoc}
     */
    public function getSolutionDescription(): string
    {
        return $this->solution;
    }

    /**
     * {@inheritDoc}
     */
    public function getDocumentationLinks(): array
    {
        return [];
    }

    /**
     * Retrieve the solution from cache or generate a new one.
     */
    private function retrieveSolutionFromCache(): string
    {
        $cacheKey = 'ai-solution-' . sha1($this->throwable->getTraceAsString());

        return Cache::remember(
            $cacheKey,
            Date::now()->addHour(),
            fn(): string => $this->generateSolution(),
        ) ?: '';
    }

    /**
     * Generate a solution using OpenAI's chat completions API.
     */
    private function generateSolution(): string
    {
        if (! $this->openAiApiKey) {
            return 'OpenAI is not configured. Set OPENAI_API_KEY in your .env file.';
        }

        // Guard against missing package at runtime
        if (! class_exists(\OpenAI\Laravel\Facades\OpenAI::class)) {
            return 'openai-php/laravel is not installed. Run: composer require openai-php/laravel';
        }

        try {
            $prompt = $this->generatePrompt($this->throwable);

            $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a senior Laravel developer. Provide concise, actionable solutions for PHP exceptions. Keep responses under 200 words.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 300,
                'temperature' => 0,
            ]);

            return $response->choices[0]->message->content ?? 'No solution generated.';
        } catch (Throwable $e) {
            return 'Failed to generate AI solution: ' . $e->getMessage();
        }
    }

    /**
     * Get the first application frame from the throwable's backtrace.
     */
    private function getApplicationFrame(Throwable $throwable): ?Frame
    {
        $backtrace = Backtrace::createForThrowable($throwable);
        $frames = $backtrace->frames();
        $index = $backtrace->firstApplicationFrameIndex();

        return $frames[$index] ?? null;
    }

    /**
     * Generate the prompt for OpenAI based on the throwable context.
     */
    private function generatePrompt(Throwable $throwable): string
    {
        $applicationFrame = $this->getApplicationFrame($throwable);

        if (! $applicationFrame instanceof Frame) {
            return sprintf(
                "Exception: %s\nClass: %s\n\nNo application frame found in backtrace. Suggest general fixes.",
                $throwable->getMessage(),
                $throwable::class,
            );
        }

        $snippet = $applicationFrame->getSnippet(15);

        return view('foundation::prompts.solution', [
            'snippet' => collect($snippet)
                ->map(fn(string $line, int $number): string => $number . ' ' . $line)
                ->join(PHP_EOL),
            'file' => $applicationFrame->file,
            'line' => $applicationFrame->lineNumber,
            'exception' => $throwable->getMessage(),
        ])->render();
    }
}
