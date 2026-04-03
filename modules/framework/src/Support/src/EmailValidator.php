<?php

declare(strict_types=1);

namespace Pixielity\Support;

use Beeyev\DisposableEmailFilter\Adapters\Laravel\Facades\DisposableEmail;
use Beeyev\DisposableEmailFilter\DisposableEmailFilter;
use Exception;
use Illuminate\Container\Attributes\Config;

/**
 * Email Validator.
 *
 * Provides comprehensive email validation similar to Magento's EmailAddress validator.
 * This class validates email addresses using multiple strategies:
 * - Basic format validation (RFC 5322)
 * - DNS MX record validation (optional)
 * - Disposable email detection
 * - Domain validation
 * - Length validation
 *
 * ## Features:
 * - RFC 5322 compliant email validation
 * - DNS MX record checking
 * - Disposable email domain filtering
 * - Configurable validation options
 * - Detailed error messages
 *
 * ## Usage:
 *
 * ### Basic Validation:
 * ```php
 * $validator = new EmailValidator();
 *
 * if ($validator->isValid('user@example.com')) {
 *     // Email is valid
 * }
 *
 * // Get validation errors
 * $errors = $validator->getMessages();
 * ```
 *
 * ### With Options:
 * ```php
 * $validator = new EmailValidator([
 *     'checkMx' => true,              // Check DNS MX records
 *     'checkDisposable' => true,      // Check for disposable emails
 *     'allowLocal' => false,          // Disallow local addresses (no domain)
 *     'maxLength' => 254,             // Maximum email length
 * ]);
 *
 * $isValid = $validator->isValid('user@example.com');
 * ```
 *
 * ### Static Validation:
 * ```php
 * // Quick validation
 * if (EmailValidator::validate('user@example.com')) {
 *     // Email is valid
 * }
 * ```
 *
 * @category   Support
 *
 * @since      1.0.0
 */
class EmailValidator
{
    /**
     * Validation error constants.
     *
     * @var string
     */
    public const ERROR_INVALID_FORMAT = 'invalid_format';

    /**
     * Error when email is too long.
     *
     * @var string
     */
    public const ERROR_TOO_LONG = 'too_long';

    /**
     * Error when no MX record is found.
     *
     * @var string
     */
    public const ERROR_NO_MX_RECORD = 'no_mx_record';

    /**
     * Error when email is from a disposable domain.
     *
     * @var string
     */
    public const ERROR_DISPOSABLE = 'disposable';

    /**
     * Error when local email addresses are not allowed.
     *
     * @var string
     */
    public const ERROR_LOCAL_NOT_ALLOWED = 'local_not_allowed';

    /**
     * Error when IP addresses are not allowed.
     *
     * @var string
     */
    public const ERROR_IP_NOT_ALLOWED = 'ip_not_allowed';

    /**
     * Validation error messages.
     *
     * @var array<string, string>
     */
    protected array $messages = [];

    /**
     * Validation options.
     *
     * @var array<string, mixed>
     */
    protected array $options = [
        'checkMx' => false,  // Check DNS MX records
        'checkDisposable' => true,  // Check for disposable email domains
        'allowLocal' => false,  // Allow local addresses (no domain)
        'maxLength' => 254,  // Maximum email length (RFC 5321)
        'allowIp' => false,  // Allow IP addresses as domain
    ];

    /**
     * Error message templates.
     *
     * @var array<string, string>
     */
    protected array $messageTemplates = [
        self::ERROR_INVALID_FORMAT => 'Invalid email address format',
        self::ERROR_TOO_LONG => 'Email address is too long (maximum %d characters)',
        self::ERROR_NO_MX_RECORD => 'No MX record found for domain',
        self::ERROR_DISPOSABLE => 'Disposable email addresses are not allowed',
        self::ERROR_LOCAL_NOT_ALLOWED => 'Local email addresses are not allowed',
        self::ERROR_IP_NOT_ALLOWED => 'IP addresses are not allowed as email domain',
    ];

    /**
     * Constructor.
     *
     * @param  array<string, mixed>  $options  Validation options
     * @param  string  $appDomain  Application domain
     */
    public function __construct(
        array $options = [],
        #[Config('APP_DOMAIN')]
        string $appDomain = '',
    ) {
        $this->options = Arr::merge($this->options, $options);

        // Add common disposable email domains to the blacklist.
        // These domains belong to throwaway email services that should not be accepted.
        DisposableEmail::blacklistedDomains()->addMultiple([
            'test.com',  // Common throwaway domain used for testing.
            'example.com',  // Reserved domain often used for demonstrations.
            'maildrop.cc',  // Disposable email service for temporary addresses.
            'mailinator.com',  // Popular disposable email service.
            '10minute-mail.org',  // A service that provides temporary emails for 10 minutes.
        ]);

        // Optionally, add the application's domain to the whitelist.
        // This ensures that users can register with their own email addresses from the app's domain.
        if ($appDomain !== '' && $appDomain !== '0') {
            DisposableEmail::whitelistedDomains()->add($appDomain);
        }
    }

    /**
     * Static validation helper.
     *
     * Quick validation without creating an instance.
     *
     * @param  string  $email  Email address to validate
     * @param  array<string, mixed>  $options  Validation options
     * @return bool True if valid, false otherwise
     */
    public static function validate(string $email, array $options = []): bool
    {
        $validator = new self($options);

        return $validator->isValid($email);
    }

    /**
     * Validate email with MX record check.
     *
     * @param  string  $email  Email address to validate
     * @return bool True if valid with MX record, false otherwise
     */
    public static function validateWithMx(string $email): bool
    {
        return self::validate($email, ['checkMx' => true]);
    }

    /**
     * Validate email and check for disposable domains.
     *
     * @param  string  $email  Email address to validate
     * @return bool True if valid and not disposable, false otherwise
     */
    public static function validateNotDisposable(string $email): bool
    {
        return self::validate($email, ['checkDisposable' => true]);
    }

    /**
     * Validate email with all checks enabled.
     *
     * @param  string  $email  Email address to validate
     * @return bool True if valid with all checks, false otherwise
     */
    public static function validateStrict(string $email): bool
    {
        return self::validate($email, [
            'checkMx' => true,
            'checkDisposable' => true,
            'allowLocal' => false,
            'allowIp' => false,
        ]);
    }

    /**
     * Validate an email address.
     *
     * @param  string  $email  Email address to validate
     * @return bool True if valid, false otherwise
     */
    public function isValid(string $email): bool
    {
        // Reset messages
        $this->messages = [];

        // Trim whitespace
        $email = Str::trim($email);

        // Check length
        if (Str::length($email) > $this->options['maxLength']) {
            $this->addMessage(
                self::ERROR_TOO_LONG,
                Str::format($this->messageTemplates[self::ERROR_TOO_LONG], $this->options['maxLength'])
            );

            return false;
        }

        // Basic format validation using PHP's filter
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addMessage(self::ERROR_INVALID_FORMAT, $this->messageTemplates[self::ERROR_INVALID_FORMAT]);

            return false;
        }

        // Split email into local and domain parts
        $parts = Str::explode('@', $email);
        if (count($parts) !== 2) {
            $this->addMessage(self::ERROR_INVALID_FORMAT, $this->messageTemplates[self::ERROR_INVALID_FORMAT]);

            return false;
        }

        [$local, $domain] = $parts;

        // Check if local address (no domain)
        if ($domain === '' || $domain === '0') {
            if (! $this->options['allowLocal']) {
                $this->addMessage(self::ERROR_LOCAL_NOT_ALLOWED, $this->messageTemplates[self::ERROR_LOCAL_NOT_ALLOWED]);

                return false;
            }

            return true;
        }

        // Check if domain is an IP address
        if ($this->isIpAddress($domain)) {
            if (! $this->options['allowIp']) {
                $this->addMessage(self::ERROR_IP_NOT_ALLOWED, $this->messageTemplates[self::ERROR_IP_NOT_ALLOWED]);

                return false;
            }

            return true;
        }

        // Check for disposable email domains
        if ($this->options['checkDisposable'] && $this->isDisposable($email)) {
            $this->addMessage(self::ERROR_DISPOSABLE, $this->messageTemplates[self::ERROR_DISPOSABLE]);

            return false;
        }

        // Check DNS MX records
        if ($this->options['checkMx'] && ! $this->hasMxRecord($domain)) {
            $this->addMessage(self::ERROR_NO_MX_RECORD, $this->messageTemplates[self::ERROR_NO_MX_RECORD]);

            return false;
        }

        return true;
    }

    /**
     * Get validation error messages.
     *
     * @return array<string, string> Error messages
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Get the first validation error message.
     *
     * @return string|null First error message or null if no errors
     */
    public function getMessage(): ?string
    {
        return $this->messages === [] ? null : reset($this->messages);
    }

    /**
     * Set validation options.
     *
     * @param  array<string, mixed>  $options  Validation options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = Arr::merge($this->options, $options);

        return $this;
    }

    /**
     * Get validation options.
     *
     * @return array<string, mixed> Validation options
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Check if email is from a disposable domain.
     *
     * @param  string  $email  Email address
     * @return bool True if disposable, false otherwise
     */
    protected function isDisposable(string $email): bool
    {
        try {
            $disposableEmailFilter = new DisposableEmailFilter();

            return $disposableEmailFilter->isDisposableEmailAddress($email);
        } catch (Exception) {
            // If the disposable email filter fails, assume it's not disposable
            return false;
        }
    }

    /**
     * Check if domain has MX records.
     *
     * @param  string  $domain  Domain name
     * @return bool True if MX records exist, false otherwise
     */
    protected function hasMxRecord(string $domain): bool
    {
        // Remove brackets from IPv6 addresses
        $domain = Str::trim($domain, '[]');

        // Check for MX records
        return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A') || checkdnsrr($domain, 'AAAA');
    }

    /**
     * Check if domain is an IP address.
     *
     * @param  string  $domain  Domain name
     * @return bool True if IP address, false otherwise
     */
    protected function isIpAddress(string $domain): bool
    {
        // Remove brackets from IPv6 addresses
        $domain = Str::trim($domain, '[]');

        return filter_var($domain, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Add a validation error message.
     *
     * @param  string  $key  Message key
     * @param  string  $message  Error message
     */
    protected function addMessage(string $key, string $message): void
    {
        $this->messages[$key] = $message;
    }
}
