<?php

declare(strict_types=1);

/**
 * Hidden Context — Example.
 *
 * Shows how to store sensitive data in context that is available to
 * code but excluded from logs, exception reports, and queue job
 * serialization.
 *
 * ## Regular vs Hidden Context:
 *
 *   Regular context (set/get):
 *     - Appears in every log entry via Log::shareContext()
 *     - Serialized into queue job payloads
 *     - Included in exception reports
 *     - Visible in debug tools
 *
 *   Hidden context (setHidden/getHidden):
 *     - Available to code via getHidden()
 *     - NOT in log entries
 *     - NOT in queue job payloads
 *     - NOT in exception reports
 *     - NOT visible in debug tools
 *
 * ## When to use hidden context:
 *
 *   - API keys and tokens
 *   - OAuth access tokens
 *   - Encryption keys
 *   - Any PII that shouldn't be logged
 *   - Temporary secrets needed during request processing
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\ScopedAndHiddenContext;

use Pixielity\Context\Facades\AppContext;

/**
 * Demonstrates hidden context for sensitive data.
 */
class HiddenContextExample
{
    /**
     * Store API credentials in hidden context.
     *
     * The payment gateway API key is needed by multiple services during
     * the request, but it must NEVER appear in logs or error reports.
     *
     * @param  string  $apiKey  The payment gateway API key.
     * @param  string  $merchantId  The merchant identifier (not sensitive — regular context).
     * @return void
     */
    public function setupPaymentContext(string $apiKey, string $merchantId): void
    {
        // Hidden: available to code, excluded from logs and serialization
        AppContext::setHidden('payment.api_key', $apiKey);
        AppContext::setHidden('payment.api_secret', 'sk_live_...');

        // Regular: appears in logs for debugging (not sensitive)
        AppContext::set('payment.merchant_id', $merchantId);
        AppContext::set('payment.gateway', 'stripe');
    }

    /**
     * Use hidden context in a service method.
     *
     * The payment service reads the API key from hidden context
     * instead of receiving it as a parameter. This keeps the key
     * out of method signatures, stack traces, and log entries.
     *
     * @param  float   $amount    The charge amount.
     * @param  string  $currency  The currency code.
     * @return array The charge result.
     */
    public function chargeCustomer(float $amount, string $currency): array
    {
        // Read the API key from hidden context — NOT from a parameter
        $apiKey = AppContext::getHidden('payment.api_key');

        // This log entry includes payment.merchant_id and payment.gateway
        // but NOT payment.api_key (it's hidden)
        // Log::info('Charging customer', ['amount' => $amount]);
        // → {"payment.merchant_id": "merch_123", "payment.gateway": "stripe", "amount": 99.99}
        // → NO api_key in the log entry

        // Use the API key to make the charge
        return [
            'status' => 'succeeded',
            'amount' => $amount,
            'currency' => $currency,
            'gateway' => AppContext::get('payment.gateway'),
        ];
    }

    /**
     * Store OAuth tokens in hidden context.
     *
     * When processing an OAuth callback, the access and refresh tokens
     * are stored in hidden context so they're available during the
     * request but never leaked to logs.
     *
     * @param  string  $accessToken   The OAuth access token.
     * @param  string  $refreshToken  The OAuth refresh token.
     * @return void
     */
    public function storeOAuthTokens(string $accessToken, string $refreshToken): void
    {
        // Both tokens are hidden — they must never appear in logs
        AppContext::setHidden('oauth.access_token', $accessToken);
        AppContext::setHidden('oauth.refresh_token', $refreshToken);

        // The provider name is regular context — safe to log
        AppContext::set('oauth.provider', 'google');
    }
}
