<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Abstracts;

use Illuminate\Bus\Queueable;
use Illuminate\Container\Attributes\Config;
use Illuminate\Queue\SerializesModels;
use Maantje\ReactEmail\ReactMailable;
use Override;
use Pixielity\Foundation\Enums\Direction;
use Pixielity\Foundation\Enums\Theme;
use Pixielity\Support\Reflection;
use Pixielity\Support\Str;

/**
 * Abstract Base Email Class.
 *
 * Base class for all email mailables in the application.
 * Provides common functionality for localized, themed emails with RTL/LTR support.
 *
 * Features:
 * - Automatic locale detection from authenticated user or app locale
 * - RTL/LTR direction support based on locale
 * - Theme support (light/dark)
 * - Consistent base data structure for all emails
 * - Extensible buildViewData() method for custom data
 */
abstract class BaseEmail extends ReactMailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Email theme (light, dark, or system).
     * Must be public with no type to match parent Mailable class.
     */
    public $theme = 'light';

    /**
     * Email locale (e.g., 'en', 'ar', 'fr').
     * Must be public with no type to match parent Mailable class.
     *
     * @var string
     */
    public $locale;

    /**
     * Text direction (ltr or rtl) as string.
     */
    protected ?string $direction = null;

    /**
     * Create a new email instance.
     *
     * @param  string  $appName  Application name from config
     * @param  string  $appUrl  Application URL from config
     * @param  array<int, string>  $rtlLocales  RTL locales configuration
     * @param  array<string, mixed>  $themeConfigs  Theme configurations from config
     * @param  array<string, mixed>  $themeAssets  Theme assets configuration from config
     */
    public function __construct(
        #[Config('app.name', 'Application')]
        protected string $appName,
        #[Config('app.url', 'http://localhost')]
        protected string $appUrl,

        /**
         * RTL Locale configuration.
         */
        #[Config('localization.rtl_locales', ['ar'])]
        protected array $rtlLocales,

        /**
         * Theme configurations array.
         */
        #[Config('theme.themes')]
        protected array $themeConfigs = [],

        /**
         * Theme assets configuration.
         */
        #[Config('theme.assets')]
        protected array $themeAssets = [],
    ) {
        // Set locale from authenticated user or app default
        $this->locale = $this->getLocale();

        // Set direction based on locale
        $this->direction = $this->getDirection();
    }

    /**
     * Set the email theme.
     *
     * @param  Theme|string  $theme  Theme enum or string (light, dark, system)
     */
    public function theme(Theme|string $theme): self
    {
        $this->theme = Reflection::implements($theme, Theme::class) ? $theme() : $theme;

        return $this;
    }

    /**
     * Set the email locale.
     *
     * @param  string  $locale  Locale code (e.g., 'en', 'ar', 'fr')
     */
    public function locale($locale): self
    {
        $this->locale = $locale;
        $this->direction = null;  // Reset direction so it's recalculated
        $this->direction = $this->getDirection();

        return $this;
    }

    /**
     * Set the text direction.
     *
     * @param  Direction|string  $direction  Direction enum or string (ltr, rtl)
     */
    public function direction(Direction|string $direction): self
    {
        $this->direction = Reflection::implements($direction, Direction::class) ? $direction() : $direction;

        return $this;
    }

    /**
     * Build the view data for the email.
     *
     * This method provides base data that all emails need (locale, theme, app info, etc.)
     * and merges it with the parent Mailable's view data (viewData, public properties, etc.)
     * and the child class's custom ViewData().
     *
     * Child classes should override ViewData() method to provide custom data.
     *
     * @return array<string, mixed>
     */
    public function buildViewData(): array
    {
        // Get theme value (convert enum to string if needed)
        $themeValue = $this->theme;
        if (! is_string($themeValue)) {
            $themeValue = 'light';
        }

        // Get theme configuration from injected theme configs
        // Use light theme as fallback if current theme is not found
        $themeConfig = $this->themeConfigs[$themeValue] ?? ($this->themeConfigs['light'] ?? []);

        // Base data for all emails
        $baseData = [
            // Localization
            'locale' => $this->locale,
            'direction' => $this->direction,

            // Theme
            'theme' => $themeValue,

            // Theme configuration (colors, fonts, spacing, etc.)
            'themeConfig' => $themeConfig,

            // Application info
            'appName' => $this->appName,
            'appUrl' => $this->appUrl,

            // Common URLs
            'homeUrl' => $this->appUrl,
            'supportUrl' => $this->appUrl . '/support',
            'unsubscribeUrl' => $this->appUrl . '/unsubscribe',

            // Brand assets
            'logo' => $this->themeAssets['logo'] ?? null,
            'logoDark' => $this->themeAssets['logo_dark'] ?? null,

            // Year for copyright
            'year' => date('Y'),
        ];

        // Merge: base data -> parent's view data -> child's custom data
        return [...$baseData, ...parent::buildViewData(), ...$this->ViewData()];
    }

    /**
     * Get the locale for this email.
     *
     * Priority:
     * 1. Explicitly set locale on the email instance
     * 2. Authenticated user's locale
     * 3. Application default locale
     */
    protected function getLocale(): string
    {
        if ($this->locale) {
            return $this->locale;
        }

        // Try to get from authenticated user
        $auth = auth();
        if (Reflection::methodExists($auth, 'check') && Reflection::methodExists($auth, 'user') && $auth->check()) {
            $user = $auth->user();
            if ($user && Reflection::methodExists($user, 'getLocale')) {
                return $user->getLocale();
            }
        }

        return app()->getLocale();
    }

    /**
     * Get text direction based on locale.
     *
     * RTL languages: Arabic (ar), Hebrew (he), Persian (fa), Urdu (ur)
     */
    protected function getDirection(): string
    {
        if ($this->direction) {
            return $this->direction;
        }

        $locale = $this->getLocale();

        // Check if locale starts with RTL language code
        foreach ($this->rtlLocales as $rtlLocale) {
            if (Str::startsWith($locale, $rtlLocale)) {
                return Direction::RTL->value;
            }
        }

        return Direction::LTR->value;
    }

    /**
     * Get custom view data for the email template.
     *
     * Override this method in child classes to provide custom data.
     * This is called automatically by buildViewData() and merged with base data.
     *
     * @return array<string, mixed>
     *
     * @example
     * ```php
     * protected function ViewData(): array
     * {
     *     return [
     *         'userName' => $this->user->name,
     *         'actionUrl' => route('verify', ['token' => $this->token]),
     *     ];
     * }
     * ```
     */
    protected function ViewData(): array
    {
        return [];
    }
}
