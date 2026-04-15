<?php

namespace Pixielity\Foundation\Exceptions;

use Illuminate\Container\Attributes\Config;
use Pixielity\Foundation\Enums\HttpStatusCode;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Render Custom Error Views Exception Handler.
 *
 * This class is responsible for rendering custom error views from the Common module
 * instead of Laravel's default error pages. It checks if a custom view exists for
 * the HTTP status code and renders it with proper styling and translations.
 *
 * ## Supported Status Codes:
 * - 400 Bad Request
 * - 401 Unauthorized
 * - 402 Payment Required
 * - 403 Forbidden
 * - 404 Not Found
 * - 405 Method Not Allowed
 * - 408 Request Timeout
 * - 419 Page Expired
 * - 422 Unprocessable Entity
 * - 429 Too Many Requests
 * - 500 Internal Server Error
 * - 502 Bad Gateway
 * - 503 Service Unavailable
 * - 504 Gateway Timeout
 *
 * ## Usage:
 * This class is registered in bootstrap/app.php:
 * ```php
 * $exceptions->respond(function (Response $response) {
 *     return (new RenderCustomErrorViews())($response);
 * });
 * ```
 */
class RenderCustomErrorViews
{
    /**
     * HTTP status codes that have custom error views.
     *
     * @var array<int>
     */
    protected array $supportedStatusCodes = [];

    /**
     * Create a new instance.
     */
    public function __construct(
        #[Config('theme.default', 'auto')]
        private readonly string $themeDefault = 'auto',
    ) {
        $this->supportedStatusCodes = [
            HttpStatusCode::BAD_REQUEST(),  // 400
            HttpStatusCode::UNAUTHORIZED(),  // 401
            HttpStatusCode::PAYMENT_REQUIRED(),  // 402
            HttpStatusCode::FORBIDDEN(),  // 403
            HttpStatusCode::NOT_FOUND(),  // 404
            HttpStatusCode::METHOD_NOT_ALLOWED(),  // 405
            HttpStatusCode::REQUEST_TIMEOUT(),  // 408
            HttpStatusCode::PAGE_EXPIRED->value,  // 419 - Page Expired (Laravel-specific)
            HttpStatusCode::UNPROCESSABLE_ENTITY(),  // 422
            HttpStatusCode::TOO_MANY_REQUESTS(),  // 429
            HttpStatusCode::INTERNAL_SERVER_ERROR(),  // 500
            HttpStatusCode::BAD_GATEWAY(),  // 502
            HttpStatusCode::SERVICE_UNAVAILABLE(),  // 503
            HttpStatusCode::GATEWAY_TIMEOUT(),  // 504
        ];
    }

    /**
     * Handle the response and render custom error view if available.
     *
     * @param  Response  $response  The HTTP response
     * @return Response The modified or original response
     */
    public function __invoke(Response $response, ?Throwable $throwable = null): Response
    {
        $statusCode = $response->getStatusCode();

        // Check if we have a custom error view for this status code
        if (! in_array($statusCode, $this->supportedStatusCodes)) {
            return $response;
        }

        // Check if the custom view exists
        $viewName = 'foundation::errors.' . $statusCode;
        if (! view()->exists($viewName)) {
            return $response;
        }

        // Render the custom error view with configured theme preference
        return response()->view(
            $viewName,
            [
                'exception' => $throwable,
                'theme' => $this->themeDefault,
            ],
            $statusCode
        );
    }
}
