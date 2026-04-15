<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Payment Required Exception (HTTP 402).
 *
 * Thrown when payment is required to access a resource or feature. This status
 * code is reserved for future use in digital payment systems, but is commonly
 * used in modern APIs to indicate that a subscription, upgrade, or payment is
 * needed to access premium features.
 *
 * ## HTTP Status Code: 402 Payment Required
 *
 * ## Use Cases:
 * - Subscription expired or inactive
 * - Free tier limit exceeded
 * - Premium feature access attempt
 * - Insufficient credits/balance
 * - Trial period ended
 * - Payment method required
 * - Upgrade required for feature
 *
 * ## Usage Examples:
 *
 * ### Basic Usage:
 * ```php
 * throw new PaymentRequiredException();
 * // Returns: 402 with "Payment required" message
 * ```
 *
 * ### Subscription Required:
 * ```php
 * if (!$user->hasActiveSubscription()) {
 *     throw new PaymentRequiredException(
 *         'An active subscription is required to access this feature.'
 *     );
 * }
 * ```
 *
 * ### Free Tier Limit Exceeded:
 * ```php
 * if ($user->hasExceededFreeLimit()) {
 *     throw new PaymentRequiredException(
 *         'You have exceeded your free tier limit. Please upgrade to continue.',
 *         ['X-Upgrade-URL' => 'https://example.com/upgrade']
 *     );
 * }
 * ```
 *
 * ### Premium Feature Access:
 * ```php
 * if (!$user->isPremium() && $feature->isPremium()) {
 *     throw new PaymentRequiredException(
 *         'This is a premium feature. Please upgrade your account to access it.',
 *         ['X-Feature-Tier' => 'premium']
 *     );
 * }
 * ```
 *
 * ### Insufficient Credits:
 * ```php
 * if ($user->credits < $requiredCredits) {
 *     throw new PaymentRequiredException(
 *         "Insufficient credits. You need {$requiredCredits} credits but have {$user->credits}.",
 *         [
 *             'X-Required-Credits' => (string) $requiredCredits,
 *             'X-Current-Credits' => (string) $user->credits,
 *         ]
 *     );
 * }
 * ```
 *
 * ### Trial Expired:
 * ```php
 * if ($user->isTrialExpired()) {
 *     throw new PaymentRequiredException(
 *         'Your trial period has ended. Please subscribe to continue using the service.',
 *         ['X-Trial-Ended' => $user->trial_ends_at->toIso8601String()]
 *     );
 * }
 * ```
 *
 * ### API Rate Limit (Paid Tier):
 * ```php
 * if ($user->isFreeUser() && $user->hasExceededFreeApiLimit()) {
 *     throw new PaymentRequiredException(
 *         'Free tier API limit exceeded. Upgrade to premium for higher limits.',
 *         [
 *             'X-Current-Limit' => '100',
 *             'X-Premium-Limit' => '10000',
 *         ]
 *     );
 * }
 * ```
 *
 * ### Payment Method Required:
 * ```php
 * if (!$user->hasPaymentMethod()) {
 *     throw new PaymentRequiredException(
 *         'Please add a payment method to access this feature.',
 *         ['X-Payment-Setup-URL' => route('payment.setup')]
 *     );
 * }
 * ```
 *
 * ## Response Format:
 * ```json
 * {
 *   "success": false,
 *   "error": {
 *     "code": "PAYMENT_REQUIRED",
 *     "message": "Payment required to access this resource.",
 *     "upgrade_url": "https://example.com/upgrade",
 *     "current_plan": "free",
 *     "required_plan": "premium"
 *   }
 * }
 * ```
 *
 * ## Response Headers:
 * - **X-Upgrade-URL**: URL to upgrade/subscribe
 * - **X-Feature-Tier**: Required subscription tier
 * - **X-Current-Plan**: User's current plan
 * - **X-Required-Credits**: Credits needed
 * - **X-Current-Credits**: User's current credits
 *
 * ## Client Handling:
 * Clients should:
 * 1. Display upgrade/payment prompt to user
 * 2. Redirect to payment/upgrade page
 * 3. Show pricing information
 * 4. Highlight benefits of upgrading
 * 5. Provide clear call-to-action
 *
 * ## Best Practices:
 * - Provide clear upgrade path in error message
 * - Include pricing information when relevant
 * - Show what features are available in paid tiers
 * - Make upgrade process seamless
 * - Track conversion from 402 errors
 * - A/B test error messages for better conversion
 * - Offer trial extensions or discounts
 *
 * ## Monetization Strategies:
 * - Soft limits with upgrade prompts
 * - Feature gating (premium features)
 * - Usage-based pricing (API calls, storage)
 * - Time-based access (subscriptions)
 * - Credit-based systems
 *
 * ## Difference from 403 Forbidden:
 * - **402 Payment Required**: User can gain access by paying
 * - **403 Forbidden**: User cannot access regardless of payment
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/402
 * @see https://tools.ietf.org/html/rfc7231#section-6.5.2
 * @since 1.0.0
 */
class PaymentRequiredException extends HttpException
{
    /**
     * Create a new Payment Required exception.
     *
     * @param  string  $message  The error message to display
     * @param  array  $headers  Additional HTTP headers to include in response
     */
    public function __construct(
        string $message = 'Payment required to access this resource.',
        array $headers = []
    ) {
        // Call parent HttpException constructor with 402 status code
        parent::__construct(402, $message, null, $headers);
    }
}
