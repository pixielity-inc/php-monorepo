<?php

namespace Pixielity\Octane\Providers;

use Laravel\Octane\OctaneServiceProvider as LaravelOctaneServiceProvider;
use Pixielity\Octane\Console\Commands\RestartAppCommand;
use Pixielity\Octane\Console\Commands\StartAppCommand;
use Pixielity\Octane\Console\Commands\StopAppCommand;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Laravel\Octane\Events\RequestReceived;
use Laravel\Octane\Events\RequestTerminated;
use Laravel\Octane\Events\TaskReceived;
use Laravel\Octane\Events\TickReceived;
use Override;
use Pixielity\Foundation\Enums\ContainerToken;
use Pixielity\Support\Reflection;
use Pixielity\Support\ServiceProvider;


class OctaneServiceProvider extends LaravelOctaneServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    #[\Override]
    public function boot(): void
    {
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                StartAppCommand::class,
                StopAppCommand::class,
                RestartAppCommand::class,
            ]);
        }

        parent::boot();

        // Only register Octane listeners if Octane is actually running
        // This prevents errors when running traditional PHP-FPM or CLI commands
        if (! $this->app->bound(ContainerToken::OCTANE->value)) {
            return;
        }

        // Register Octane event listeners
        $this->registerTaskReceivedListener();
        $this->registerTickReceivedListener();
        $this->registerRequestReceivedListener();
        $this->registerRequestTerminatedListener();
    }

    /**
     * Register listener for RequestReceived event.
     *
     * This event fires when Octane receives a new request, BEFORE processing.
     * Use this to reset application state and prepare for the new request.
     *
     * ## What to Reset:
     * - Carbon test time (for testing)
     * - Request-scoped singletons
     * - Temporary caches
     * - Global state variables
     * - Log context (important!)
     *
     * ## Why This Matters:
     * Without proper state reset, data from previous requests can leak into
     * new requests, causing security issues, data corruption, or unexpected behavior.
     */
    protected function registerRequestReceivedListener(): void
    {
        /** 
 * @var Dispatcher $events 
 */
        $events = $this->app[ContainerToken::EVENTS()];
        $events->listen(RequestReceived::class, function (RequestReceived $requestReceived): void {
            // Reset Carbon test time if it was set
            // This is crucial for testing - prevents test time from persisting
            if (Reflection::exists(Carbon::class)) {
                Date::setTestNow();
            }

            // Set initial log context for the request
            // This provides basic request information for all logs
            Log::withContext([
                'request_method' => $requestReceived->request->method(),
                'request_path' => $requestReceived->request->path(),
                'request_ip' => $requestReceived->request->ip(),
            ]);

            // Reset any request-specific state here
            // Examples:
            // - Clear request-scoped caches
            // - Reset singleton state
            // - Clear temporary data

            // Example: Reset a custom service
            // if ($this->app->bound(CustomService::class)) {
            //     $this->app->make(CustomService::class)->reset();
            // }

            // Example: Clear request-specific cache
            // cache()->tags(['request'])->flush();
        });
    }

    /**
     * Register listener for RequestTerminated event.
     *
     * This event fires AFTER the request has been fully processed and the
     * response sent to the client. Use this for cleanup operations that
     * don't need to block the response.
     *
     * ## Cleanup Operations:
     * - Clear temporary files
     * - Reset global state
     * - Flush buffers
     * - Log request metrics
     *
     * ## Performance Note:
     * Operations here don't block the response to the client, but they do
     * block the worker from handling the next request. Keep cleanup fast.
     */
    protected function registerRequestTerminatedListener(): void
    {
        /** 
 * @var Dispatcher $events 
 */
        $events = $this->app[ContainerToken::EVENTS()];
        $events->listen(RequestTerminated::class, function (RequestTerminated $requestTerminated): void {
            // Perform cleanup operations after request is complete

            // Example: Clear temporary files
            // $this->clearTemporaryFiles();

            // Example: Reset global state
            // GlobalState::reset();

            // Example: Log request metrics
            // if ($event->response->getStatusCode() >= 500) {
            //     logger()->error('Server error occurred', [
            //         'url' => $event->request->fullUrl(),
            //         'status' => $event->response->getStatusCode(),
            //     ]);
            // }
        });
    }

    /**
     * Register listener for TaskReceived event.
     *
     * This event fires when a background task is dispatched via Octane.
     * Tasks run in the same worker but outside the request lifecycle.
     *
     * ## Use Cases:
     * - Background processing
     * - Async operations
     * - Deferred tasks
     *
     * ## Example Task Dispatch:
     * ```php
     * Octane::concurrently([
     *     fn () => $this->processImages(),
     *     fn () => $this->sendNotifications(),
     * ]);
     * ```
     */
    protected function registerTaskReceivedListener(): void
    {
        /** 
 * @var Dispatcher $events 
 */
        $events = $this->app[ContainerToken::EVENTS()];
        $events->listen(TaskReceived::class, function (TaskReceived $taskReceived): void {
            // Set log context for background tasks
            // This helps distinguish task logs from request logs
            Log::withContext([
                'octane_task' => true,
            ]);

            // Prepare application for background task execution

            // Example: Set task context
            // app()->instance('task.id', $event->task->id);

            // Example: Configure logging for tasks
            // logger()->info('Background task started');
        });
    }

    /**
     * Register listener for TickReceived event.
     *
     * This event fires at regular intervals (configured in octane.php).
     * Use this for periodic maintenance operations.
     *
     * ## Maintenance Operations:
     * - Clear old cache entries
     * - Cleanup temporary files
     * - Health checks
     * - Metrics collection
     * - Memory usage monitoring
     *
     * ## Configuration:
     * ```php
     * // config/octane.php
     * 'tick_interval' => 1000, // milliseconds (1 second)
     * ```
     *
     * ## Performance Warning:
     * Tick operations run on the worker thread and can block request processing.
     * Keep tick operations lightweight and fast (< 10ms).
     */
    protected function registerTickReceivedListener(): void
    {
        /** 
 * @var Dispatcher $events 
 */
        $events = $this->app[ContainerToken::EVENTS()];
        $events->listen(TickReceived::class, function (TickReceived $tickReceived): void {
            // Set log context for tick operations
            Log::withContext([
                'octane_tick' => true,
            ]);

            // Handle periodic maintenance tasks

            // Example: Clear old cache entries
            // cache()->tags(['temporary'])->flush();

            // Example: Cleanup old temporary files
            // $this->cleanupOldFiles(storage_path('temp'), 3600);

            // Example: Monitor memory usage
            // $memoryUsage = memory_get_usage(true) / 1024 / 1024;
            // if ($memoryUsage > 128) { // 128MB threshold
            //     logger()->warning('High memory usage detected', [
            //         'memory_mb' => round($memoryUsage, 2),
            //     ]);
            // }

            // Example: Health check
            // if (!$this->isHealthy()) {
            //     logger()->error('Worker health check failed');
            // }
        });
    }
}
