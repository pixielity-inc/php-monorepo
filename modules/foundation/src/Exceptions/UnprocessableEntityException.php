<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Unprocessable Entity Exception (HTTP 422).
 *
 * Thrown when the server understands the request but cannot process it due to
 * semantic errors. This is commonly used for validation failures where the
 * request syntax is correct but the data doesn't meet business rules.
 *
 * ## HTTP Status Code: 422 Unprocessable Entity
 *
 * ## Use Cases:
 * - Form validation failures
 * - Business rule violations
 * - Invalid data combinations
 * - Constraint violations
 * - Semantic errors in request data
 * - Data integrity issues
 *
 * ## Difference from 400 Bad Request:
 * - **400**: Syntax error (malformed JSON, missing required fields)
 * - **422**: Semantic error (valid syntax but invalid data)
 *
 * ## Usage Examples:
 *
 * ### Basic Usage:
 * ```php
 * throw new UnprocessableEntityException();
 * // Returns: 422 with "Unprocessable entity" message
 * ```
 *
 * ### Form Validation Failure:
 * ```php
 * $validator = Validator::make($request->all(), [
 *     'email' => 'required|email|unique:users',
 *     'age' => 'required|integer|min:18',
 * ]);
 *
 * if ($validator->fails()) {
 *     throw new UnprocessableEntityException(
 *         'Validation failed',
 *         $validator->errors()->toArray()
 *     );
 * }
 * ```
 *
 * ### Business Rule Violation:
 * ```php
 * if ($order->total < 10) {
 *     throw new UnprocessableEntityException(
 *         'Minimum order amount is $10',
 *         ['total' => ['Order total must be at least $10']]
 *     );
 * }
 * ```
 *
 * ### Invalid Data Combination:
 * ```php
 * if ($user->age < 18 && $product->isAgeRestricted()) {
 *     throw new UnprocessableEntityException(
 *         'Age restriction violation',
 *         ['age' => ['You must be 18 or older to purchase this product']]
 *     );
 * }
 * ```
 *
 * ### Duplicate Entry:
 * ```php
 * if (User::where('email', $email)->exists()) {
 *     throw new UnprocessableEntityException(
 *         'Email already exists',
 *         ['email' => ['This email is already registered']]
 *     );
 * }
 * ```
 *
 * ### Date Range Validation:
 * ```php
 * if ($endDate <= $startDate) {
 *     throw new UnprocessableEntityException(
 *         'Invalid date range',
 *         [
 *             'end_date' => ['End date must be after start date'],
 *             'start_date' => ['Start date must be before end date'],
 *         ]
 *     );
 * }
 * ```
 *
 * ### Stock Availability:
 * ```php
 * if ($requestedQuantity > $product->stock) {
 *     throw new UnprocessableEntityException(
 *         'Insufficient stock',
 *         [
 *             'quantity' => [
 *                 "Only {$product->stock} items available in stock"
 *             ]
 *         ]
 *     );
 * }
 * ```
 *
 * ### Multiple Validation Errors:
 * ```php
 * $errors = [];
 *
 * if (empty($data['name'])) {
 *     $errors['name'] = ['Name is required'];
 * }
 *
 * if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
 *     $errors['email'] = ['Invalid email format'];
 * }
 *
 * if (!empty($errors)) {
 *     throw new UnprocessableEntityException(
 *         'Validation failed',
 *         $errors
 *     );
 * }
 * ```
 *
 * ## Response Format:
 * ```json
 * {
 *   "success": false,
 *   "error": {
 *     "code": "VALIDATION_ERROR",
 *     "message": "Validation failed",
 *     "errors": {
 *       "email": [
 *         "The email field is required.",
 *         "The email must be a valid email address."
 *       ],
 *       "age": [
 *         "The age must be at least 18."
 *       ]
 *     }
 *   }
 * }
 * ```
 *
 * ## Client Handling:
 * Clients should:
 * 1. Parse the errors object
 * 2. Display field-specific error messages
 * 3. Highlight invalid fields in the UI
 * 4. Allow user to correct and resubmit
 * 5. Preserve valid field values
 *
 * ## Best Practices:
 * - Provide specific, actionable error messages
 * - Group errors by field name
 * - Include all validation errors (not just the first one)
 * - Use consistent error message format
 * - Validate on both client and server side
 * - Return errors in the same language as the request
 * - Include error codes for programmatic handling
 *
 * ## Laravel Validation Integration:
 * ```php
 * // In FormRequest
 * protected function failedValidation(Validator $validator)
 * {
 *     throw new UnprocessableEntityException(
 *         'Validation failed',
 *         $validator->errors()->toArray()
 *     );
 * }
 * ```
 *
 * ## Common Validation Scenarios:
 * - Required fields missing
 * - Invalid format (email, phone, URL)
 * - Out of range values
 * - Duplicate entries
 * - Invalid relationships
 * - Business rule violations
 * - Data type mismatches
 * - Length constraints
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422
 * @see https://tools.ietf.org/html/rfc4918#section-11.2
 * @since 1.0.0
 */
class UnprocessableEntityException extends HttpException
{
    /**
     * Create a new Unprocessable Entity exception.
     *
     * @param  string  $message  The error message to display
     * @param  array<string, array<string>>  $errors  Validation errors grouped by field
     * @param  array  $headers  Additional HTTP headers to include in response
     */
    public function __construct(
        string $message = 'The given data was invalid.',
        /**
         * Validation errors grouped by field.
         */
        protected array $errors = [],
        array $headers = []
    ) {
        // Call parent HttpException constructor with 422 status code
        parent::__construct(422, $message, null, $headers);
    }

    /**
     * Get the validation errors.
     *
     * @return array<string, array<string>> Validation errors grouped by field
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if there are any validation errors.
     *
     * @return bool True if there are errors, false otherwise
     */
    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    /**
     * Get errors for a specific field.
     *
     * @param  string  $field  The field name
     * @return array<string> Array of error messages for the field
     */
    public function getFieldErrors(string $field): array
    {
        return $this->errors[$field] ?? [];
    }
}
