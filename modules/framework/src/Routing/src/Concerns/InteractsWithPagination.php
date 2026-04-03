<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Pixielity\Foundation\Constants\Paginator as PaginatorConstants;
use Pixielity\Foundation\Exceptions\BadRequestException;
use Pixielity\Response\Builders\Response;

/**
 * Interacts With Pagination Trait.
 *
 * Provides convenient methods for handling pagination in API responses.
 *
 * ## Usage:
 * ```php
 * class UserController extends BaseController
 * {
 *     use InteractsWithPagination;
 *
 *     public function index()
 *     {
 *         $users = User::query();
 *
 *         return $this->paginate($users);
 *     }
 *
 *     public function search()
 *     {
 *         $page = $this->getPage();
 *         $perPage = $this->getPerPage();
 *
 *         $users = User::paginate($perPage, ['*'], PaginatorConstants::PAGE, $page);
 *
 *         return $this->paginatedResponse($users);
 *     }
 * }
 * ```
 *
 * @method Response response() Get the Response facade for advanced chaining`
 * @method int getPage() Get page number from request
 * @method int getPerPage() Get per page value from request
 * @method void validatePagination(int $page, int $perPage) Validate pagination parameters
 * @method Response paginate(Builder $query, ?int $perPage = null) Paginate query and return Response builder
 * @method Response paginatedResponse((LengthAwarePaginator|CursorPaginator|Paginator) $paginator, ?string $message = null) Return paginated Response builder
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithPagination
{
    /**
     * Get page number from request.
     *
     * Extracts the page number from the query string with a default fallback.
     * Automatically casts to integer to ensure type safety.
     *
     * ## Usage:
     * ```php
     * // Get page with default (1)
     * $page = $this->getPage();
     *
     * // Get page with custom default
     * $page = $this->getPage(5);
     * ```
     *
     * ## Query Parameter:
     * - Parameter name: `page`
     * - Example: `/api/users?page=2`
     * - Default: 1
     *
     * @param  int  $default  Default page number (defaults to 1)
     * @return int Page number from request or default
     */
    protected function getPage(int $default = PaginatorConstants::DEFAULT_PAGE): int
    {
        return (int) $this->query(PaginatorConstants::PAGE, $default);
    }

    /**
     * Get per page value from request.
     *
     * Extracts the items per page from the query string with a default fallback.
     * Automatically casts to integer to ensure type safety.
     *
     * ## Usage:
     * ```php
     * // Get per page with default (50)
     * $perPage = $this->getPerPage();
     *
     * // Get per page with custom default
     * $perPage = $this->getPerPage(25);
     * ```
     *
     * ## Query Parameter:
     * - Parameter name: `per_page`
     * - Example: `/api/users?per_page=25`
     * - Default: 50
     * - Maximum: 100 (enforced by validatePagination)
     *
     * @param  int  $default  Default per page value (defaults to 50)
     * @return int Items per page from request or default
     */
    protected function getPerPage(int $default = PaginatorConstants::DEFAULT_PER_PAGE): int
    {
        return (int) $this->query(PaginatorConstants::PER_PAGE, $default);
    }

    /**
     * Validate pagination parameters.
     *
     * Ensures pagination parameters are within acceptable ranges to prevent
     * performance issues and invalid requests.
     *
     * ## Usage:
     * ```php
     * $page = $this->getPage();
     * $perPage = $this->getPerPage();
     * $this->validatePagination($page, $perPage);
     * ```
     *
     * ## Validation Rules:
     * - Page must be >= 1
     * - Per page must be >= 1
     * - Per page must be <= MAX_PER_PAGE (default: 100)
     *
     * ## Why Validate:
     * - Prevents negative page numbers
     * - Prevents excessive page sizes (performance)
     * - Provides clear error messages
     * - Protects against malicious requests
     *
     * @param  int  $page  Page number (must be >= 1)
     * @param  int  $perPage  Items per page (must be 1-100)
     *
     * @throws BadRequestException If validation fails
     */
    protected function validatePagination(int $page, int $perPage): void
    {
        // Validate page number (must be at least 1)
        if ($page < PaginatorConstants::DEFAULT_PAGE) {
            throw BadRequestException::make(__(
                "Invalid page number ':page'. Page number must be at least :min.",
                [PaginatorConstants::PAGE => $page, 'min' => PaginatorConstants::DEFAULT_PAGE]
            ));
        }

        // Validate per page maximum (prevent performance issues)
        if ($perPage > PaginatorConstants::MAX_PER_PAGE) {
            throw BadRequestException::make(__(
                "Invalid page size ':size'. Page size cannot exceed the maximum allowed limit of :max.",
                [PaginatorConstants::PER_PAGE => $perPage, 'max' => PaginatorConstants::MAX_PER_PAGE]
            ));
        }

        // Validate per page minimum (must be at least 1)
        if ($perPage < 1) {
            throw BadRequestException::make(__(
                "Invalid page size ':size'. Page size must be at least 1.",
                [PaginatorConstants::PER_PAGE => $perPage]
            ));
        }
    }

    /**
     * Paginate a query and return Response builder.
     *
     * @param  Builder  $builder  Eloquent query builder
     * @param  int|null  $perPage  Items per page (null = use request value)
     * @return Response Response builder
     */
    protected function paginate(Builder $builder, ?int $perPage = null): Response
    {
        $page = $this->getPage();
        $perPage ??= $this->getPerPage();

        $this->validatePagination($page, $perPage);

        /** @var LengthAwarePaginator $lengthAwarePaginator */
        $lengthAwarePaginator = $builder->paginate($perPage, ['*'], PaginatorConstants::PAGE, $page);

        return $this->paginatedResponse($lengthAwarePaginator);
    }

    /**
     * Return a paginated Response builder.
     *
     * @param  LengthAwarePaginator|CursorPaginator|Paginator  $paginator  Paginator instance
     * @param  string|null  $message  Optional success message
     * @return Response Response builder
     */
    protected function paginatedResponse(
        LengthAwarePaginator|CursorPaginator|Paginator $paginator,
        ?string $message = null
    ): Response {
        $response = $this->response()->paginate($paginator);

        if ($message) {
            return $response->message($message);
        }

        return $response;
    }
}
