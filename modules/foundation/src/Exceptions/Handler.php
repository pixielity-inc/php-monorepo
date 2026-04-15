<?php

namespace Pixielity\Foundation\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Attributes\Config;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;
use Override;
use Pixielity\Foundation\Enums\ContainerToken;
use Pixielity\Foundation\Enums\HttpStatusCode;
use Pixielity\Sentry\Services\SentryContextRegistry;
use Pixielity\Support\Reflection;
use Pixielity\Support\Str;
use Psr\Log\LogLevel;

use function Sentry\captureException;
use function Sentry\configureScope;

use Sentry\State\Scope;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Common Module Exception Handler.
 *
 * This is the application's main exception handler that extends Laravel's base
 * exception handler. It provides custom error handling for both web and API requests,
 * including styled error pages and JSON API responses.
 *
 * ## Key Features:
 * - Custom styled error pages with translations (401, 402, 404, 419, 429, 500, 503)
 * - API-friendly JSON error responses with proper status codes
 * - Automatic detection of API vs web requests
 * - Validation error formatting (422 status)
 * - Debug information in development mode
 * - Secure error messages in production
 * - Translatable error messages
 *
 * ## Error Page Rendering:
 * For web requests, this handler uses the `RenderCustomErrorViews` class to display
 * custom styled error pages from the Common module instead of Laravel's default pages.
 * These pages are:
 * - Fully translatable (English and Arabic)
 * - Styled with shadcn/ui design system
 * - Responsive and accessible
 * - Consistent with application branding
 *
 * ## API Error Responses:
 * For API requests (routes starting with 'api/' or expecting JSON), this handler
 * returns structured JSON responses with:
 * - `success`: Always false for errors
 * - `message`: Translated error message
 * - `errors`: Validation errors (for 422 responses)
 * - `debug`: Exception details (only in debug mode)
 *
 * ## Registration:
 * This handler is registered in `CommonServiceProvider::registerBindings()` as a
 * singleton binding that overrides Laravel's default exception handler.
 *
 * ## Usage Example:
 * ```php
 * // The handler is automatically used for all exceptions
 * // No manual invocation needed
 *
 * // For custom exceptions, just throw them:
 * throw new \Exception('Something went wrong');
 *
 * // For API responses:
 * // GET /api/v1/users/999 (non-existent)
 * // Response: {"success": false, "message": "Not Found"}
 *
 * // For web requests:
 * // GET /non-existent-page
 * // Response: Custom 404 error page with styling
 * ```
 *
 * ## Exception Types Handled:
 * - `AuthenticationException`: 401 Unauthorized
 * - `AuthorizationException`: 403 Forbidden
 * - `ValidationException`: 422 Unprocessable Entity
 * - `HttpException`: Various HTTP status codes
 * - `Throwable`: 500 Internal Server Error (fallback)
 *
 * @see ExceptionHandler
 * @see RenderCustomErrorViews
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * This allows you to customize which log level is used for specific exception types.
     * For example, you might want to log authentication failures as 'warning' instead of 'error'.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     *
     * @example
     * ```php
     * protected $levels = [
     *     \Illuminate\Auth\AuthenticationException::class => 'warning',
     *     \Illuminate\Database\QueryException::class => 'critical',
     * ];
     * ```
     */
    protected $levels = [
        QueryException::class => 'critical',
        AuthenticationException::class => 'warning',
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * Exceptions in this list will not be logged or sent to error tracking services.
     * This is useful for expected exceptions that don't indicate actual errors.
     *
     * @var array<int, class-string<Throwable>>
     *
     * @example
     * ```php
     * protected $dontReport = [
     *     \Illuminate\Auth\AuthenticationException::class,
     *     \Illuminate\Validation\ValidationException::class,
     * ];
     * ```
     */
    protected $dontReport = [
        ValidationException::class,
        AuthenticationException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * When a validation exception occurs, Laravel flashes the input to the session
     * so it can be repopulated in the form. However, sensitive fields like passwords
     * should never be flashed for security reasons.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'current_password',
        'password_confirmation',
    ];

    /**
     * Create a new exception handler instance.
     *
     * @param  Container  $container  The service container
     * @param  string  $apiPrefix  The API route prefix (e.g., 'api')
     * @param  string  $apiVersion  The API version (e.g., 'v1')
     * @param  bool  $debugMode  Whether debug mode is enabled
     * @param  SentryContextRegistry|null  $sentryRegistry  Optional Sentry context registry
     */
    public function __construct(
        Container $container,
        #[Config('api.prefix', 'api')]
        protected readonly string $apiPrefix,
        #[Config('api.version', 'v1')]
        protected readonly string $apiVersion,
        #[Config('app.debug')]
        protected readonly bool $debugMode,
        protected readonly ?SentryContextRegistry $sentryRegistry = null,
    ) {
        parent::__construct($container);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * This method is called during the application bootstrap process and allows
     * you to register custom exception reporting and rendering logic.
     *
     * ## Available Methods:
     * - `reportable()`: Register a callback for reporting specific exception types
     * - `renderable()`: Register a callback for rendering specific exception types
     * - `stop()`: Stop reporting specific exception types
     */
    public function register(): void
    {
        // Register Sentry exception reporting for all exceptions
        $this->reportable(function (Throwable $throwable): void {
            // Only report if Sentry is configured and exception should be reported
            if ($this->container->bound(ContainerToken::SENTRY->value) && $this->shouldReport($throwable)) {
                // Configure Sentry scope with context
                configureScope(function (Scope $scope) use ($throwable): void {
                    // Apply all registered context providers
                    if ($this->sentryRegistry instanceof SentryContextRegistry && Reflection::implements($this->sentryRegistry, SentryContextRegistry::class)) {
                        $this->sentryRegistry->applyAll($scope, $throwable);
                    }

                    // Custom fingerprinting for better error grouping
                    $this->setCustomFingerprint($scope, $throwable);
                });

                // Capture the exception to Sentry
                captureException($throwable);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * This is the main entry point for exception rendering. It determines whether
     * the request is an API request or a web request and delegates to the appropriate
     * handler method.
     *
     * ## Request Type Detection:
     * - API Request: URL starts with 'api/' OR request expects JSON response
     * - Web Request: All other requests
     *
     * ## Response Types:
     * - API: JSON response with error details
     * - Web: HTML error page (custom or Laravel default)
     *
     * @param  Request  $request  The incoming HTTP request
     * @param  Throwable  $e  The exception that was thrown
     * @return Response The HTTP response
     */
    public function render($request, Throwable $e)
    {
        // Build the full API path pattern
        // Example: 'api/v1/*' or just 'api/*' if version is empty
        $apiPattern = $this->apiVersion !== '' && $this->apiVersion !== '0'
            ? Str::format('%s/%s/*', $this->apiPrefix, $this->apiVersion)
            : $this->apiPrefix . '/*';

        // Check if this is an API request
        // API requests either:
        // 1. Have URLs matching the configured API pattern (e.g., /api/v1/users)
        // 2. Explicitly expect JSON response (Accept: application/json header)
        if ($request->is($apiPattern) || $request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        // For web requests, use the parent handler which will:
        // 1. Call prepareResponse() to generate the response
        // 2. Apply our custom error views via RenderCustomErrorViews
        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions and return JSON response.
     *
     * This method converts exceptions into structured JSON responses suitable for
     * API consumption. It provides consistent error formatting across all API endpoints.
     *
     * ## Response Structure:
     * ```json
     * {
     *   "success": false,
     *   "message": "Error message",
     *   "errors": {}, // Only for validation errors
     *   "debug": {}    // Only in debug mode
     * }
     * ```
     *
     * ## Status Codes:
     * - 401: Authentication required (AuthenticationException)
     * - 403: Permission denied (AuthorizationException)
     * - 422: Validation failed (ValidationException)
     * - 4xx/5xx: HTTP exceptions (HttpException)
     * - 500: Internal server error (all other exceptions)
     *
     * ## Debug Information:
     * When `app.debug` is enabled, the response includes:
     * - Exception class name
     * - File and line number where exception occurred
     * - Stack trace (first 5 frames)
     *
     * ## Translation:
     * All error messages use the `foundation::exceptions` translation namespace,
     * allowing for multilingual error messages.
     *
     * ## Sentry Integration:
     * Exceptions are automatically reported to Sentry via the register() method.
     * No need to manually report here as it's handled globally.
     *
     * @param  Request  $request  The incoming HTTP request
     * @param  Throwable  $throwable  The exception that was thrown
     * @return JsonResponse JSON response with error details
     */
    public function handleApiException($request, Throwable $throwable): JsonResponse
    {
        // Exception is already reported to Sentry in register() method
        // No need to call Sentry here to avoid duplicate reports

        // Default to 500 Internal Server Error
        $statusCode = HttpStatusCode::INTERNAL_SERVER_ERROR();
        $message = __('foundation::exceptions.internal_server_error');
        $errors = null;

        // Determine the appropriate status code and message based on exception type
        // Check for custom exceptions with getStatusCode() first
        if (Reflection::methodExists($throwable, 'getStatusCode') && ! Reflection::implements($throwable, ValidationException::class)) {
            // Custom exceptions that implement getStatusCode()
            // This includes all Pixielity\Foundation\Exceptions\* exceptions
            /** 
 * @var Exception $throwable 
 */
            $statusCode = $throwable->getStatusCode();
            $message = $throwable->getMessage();
        } elseif (Reflection::implements($throwable, ValidationException::class)) {
            // 422 Unprocessable Entity: Request validation failed
            // Thrown by form request validation or manual validation
            $statusCode = HttpStatusCode::UNPROCESSABLE_ENTITY();
            $message = __('foundation::exceptions.validation_error');
            $errors = $throwable->errors();  // Include field-specific validation errors
        } elseif (Reflection::implements($throwable, HttpException::class)) {
            // HTTP exceptions with specific status codes
            // Thrown by abort() helper or HTTP exception classes
            $statusCode = $throwable->getStatusCode();
            $message = $throwable->getMessage() ?: __('foundation::exceptions.http_exception');
        } elseif (Reflection::implements($throwable, AuthenticationException::class)) {
            // 401 Unauthorized: User is not authenticated
            // Thrown when accessing protected routes without authentication
            $statusCode = HttpStatusCode::UNAUTHORIZED();
            $message = __('foundation::exceptions.unauthenticated');
        } elseif (Reflection::implements($throwable, AuthorizationException::class)) {
            // 403 Forbidden: User is authenticated but lacks permission
            // Thrown by authorization gates and policies
            $statusCode = HttpStatusCode::FORBIDDEN();
            $message = $throwable->getMessage() ?: __('foundation::exceptions.forbidden');
        } elseif (Reflection::implements($throwable, QueryException::class)) {
            // Database query exception
            $statusCode = HttpStatusCode::INTERNAL_SERVER_ERROR();
            $message = $this->debugMode ? $throwable->getMessage() : __('foundation::exceptions.internal_server_error');
        } else {
            // Generic exception handling
            // In debug mode, show the actual exception message
            // In production, show a generic error message for security
            $message = $this->debugMode ? $throwable->getMessage() : __('foundation::exceptions.internal_server_error');
        }

        // Build the base response structure
        $response = [
            'success' => false,
            'message' => $message,
        ];

        // Add validation errors if present
        if ($errors) {
            $response['errors'] = $errors;
        }

        // Add debug information in development mode
        // This helps developers troubleshoot issues but should never be shown in production
        if ($this->debugMode) {
            $response['debug'] = [
                'exception' => $throwable::class,
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => collect($throwable->getTrace())->take(5)->all(),
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Set custom fingerprint for better error grouping in Sentry.
     *
     * Fingerprinting helps Sentry group similar errors together.
     * By default, Sentry groups by exception type and stack trace,
     * but custom fingerprinting allows more intelligent grouping.
     *
     * ## Examples:
     * - Group all validation errors together
     * - Group errors by HTTP status code
     * - Group errors by custom error codes
     * - Group errors by affected resource
     *
     * @param  Scope  $scope  Sentry scope
     * @param  Throwable  $throwable  Exception
     */
    protected function setCustomFingerprint(Scope $scope, Throwable $throwable): void
    {
        // Validation exceptions: Group by validation type
        if (Reflection::implements($throwable, ValidationException::class)) {
            $scope->setFingerprint([
                'validation-error',
                request()->path(),
            ]);

            return;
        }

        // HTTP exceptions: Group by status code and route
        if (Reflection::implements($throwable, HttpException::class)) {
            $scope->setFingerprint([
                'http-exception',
                (string) $throwable->getStatusCode(),
                request()->path(),
            ]);

            return;
        }

        // Authentication exceptions: Group together
        if (Reflection::implements($throwable, AuthenticationException::class)) {
            $scope->setFingerprint([
                'authentication-error',
                request()->path(),
            ]);

            return;
        }

        // Authorization exceptions: Group by permission
        if (Reflection::implements($throwable, AuthorizationException::class)) {
            $scope->setFingerprint([
                'authorization-error',
                $throwable->getMessage(),
            ]);

            return;
        }

        // Database exceptions: Group by query type
        if (Reflection::implements($throwable, QueryException::class)) {
            // Extract SQL operation (SELECT, INSERT, UPDATE, DELETE)
            $sql = $throwable->getSql();
            preg_match('/^(\w+)/', $sql, $matches);
            $operation = $matches[1] ?? 'unknown';

            $scope->setFingerprint([
                'database-error',
                Str::lower($operation),
                $throwable->getCode(),
            ]);

            return;
        }

        // Custom exceptions with getCode() and getType() methods
        if (Reflection::methodExists($throwable, 'getCode') && Reflection::methodExists($throwable, 'getType')) {
            /* @var Exception $throwable */
            $scope->setFingerprint([
                'custom-exception',
                $throwable->getType(),
                (string) $throwable->getCode(),
            ]);

            return;
        }

        // Default: Use exception class and message
        $scope->setFingerprint([
            $throwable::class,
            $throwable->getMessage(),
        ]);
    }

    /**
     * Prepare a response for the given exception.
     *
     * This method is called by Laravel's exception handler to convert an exception
     * into an HTTP response. We override it to apply our custom error view rendering
     * for web requests and to handle custom exceptions with getStatusCode().
     *
     * ## Process Flow:
     * 1. Check if exception has getStatusCode() method (custom exceptions)
     * 2. Convert to HttpException if needed for proper status code handling
     * 3. Call parent to get the default Laravel response
     * 4. Apply custom error view rendering via RenderCustomErrorViews
     * 5. Return the customized response
     *
     * ## Custom Error Views:
     * The RenderCustomErrorViews class checks if we have a custom view for the
     * status code (401, 402, 404, 419, 429, 500, 503) and renders it if available.
     * Otherwise, it returns the original Laravel response.
     *
     * @param  Request  $request  The incoming HTTP request
     * @param  Throwable  $e  The exception that was thrown
     * @return Response The HTTP response
     *
     * @see RenderCustomErrorViews
     */
    protected function prepareResponse($request, Throwable $e)
    {
        // Check if this is a custom exception with getStatusCode() method
        // Convert it to HttpException so Laravel's parent handler can process it correctly
        if (Reflection::methodExists($e, 'getStatusCode') && ! Reflection::implements($e, HttpException::class) && ! Reflection::implements($e, ValidationException::class)) {
            /** 
 * @var Exception $e 
 */
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage();

            // Convert to HttpException for proper handling by parent
            $e = new HttpException($statusCode, $message, $e);
        }

        // Override parent's prepareResponse to safely handle config access
        // Laravel's parent tries to call config('app.debug') which may not be available
        // during early bootstrap or when handling config-related exceptions
        try {
            $isDebug = app()->bound('config') ? config('app.debug', false) : false;
        } catch (Throwable) {
            $isDebug = false;
        }

        if (! $this->isHttpException($e) && $isDebug) {
            return $this->toIlluminateResponse($this->convertExceptionToResponse($e), $e)->prepare($request);
        }

        if (! $this->isHttpException($e)) {
            $e = new HttpException(500, $e->getMessage(), $e);
        }

        return $this->toIlluminateResponse(
            $this->renderHttpException($e),
            $e
        )->prepare($request);
    }

    /**
     * Render the given HttpException.
     *
     * Override to safely handle config access when config is not bound.
     *
     * @param  HttpException  $e
     * @return Response
     */
    protected function renderHttpException($e)
    {
        // Only register error view paths if config is available
        // This prevents errors when handling exceptions during early bootstrap
        if (app()->bound('config')) {
            try {
                $this->registerErrorViewPaths();
            } catch (Throwable) {
                // Silently fail if we can't register error view paths
            }
        }

        // Only try to render view if view service is available
        if (app()->bound('view') && ($view = $this->getHttpExceptionView($e))) {
            try {
                return response()->view($view, [
                    'errors' => new ViewErrorBag(),
                    'exception' => $e,
                ], $e->getStatusCode(), $e->getHeaders());
            } catch (Throwable $t) {
                $isDebug = app()->bound('config') ? config('app.debug', false) : false;
                throw_if($isDebug, $t);

                $this->report($t);
            }
        }

        return $this->convertExceptionToResponse($e);
    }
}
