<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Application Locale
    |--------------------------------------------------------------------------
    |
    | This option controls the default locale that will be used by the
    | localization service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'default' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Auto-Detect Locale
    |--------------------------------------------------------------------------
    |
    | When enabled, the application will automatically detect the user's
    | preferred locale from request headers and query parameters.
    |
    */

    'auto_detect' => env('LOCALE_AUTO_DETECT', true),

    /*
    |--------------------------------------------------------------------------
    | Locale Detection Headers
    |--------------------------------------------------------------------------
    |
    | Headers to check for locale detection, in priority order.
    | The first matching header with a valid locale will be used.
    |
    */

    'headers' => [
        'x-language',
        'x-locale',
        'locale',
        'accept-language',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale Detection Query Parameters
    |--------------------------------------------------------------------------
    |
    | Query parameters to check for locale detection, in priority order.
    | The first matching parameter with a valid locale will be used.
    |
    */

    'query_params' => [
        'lang',
        'locale',
        'language',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | List of available locales with their configuration.
    | Only enabled locales will be accepted by the application.
    |
    | Supported locales:
    | - en: English
    | - ar: Arabic
    | - fr: French
    | - es: Spanish
    | - de: German
    | - it: Italian
    | - pt: Portuguese
    | - ru: Russian
    | - zh: Chinese
    | - ja: Japanese
    | - ko: Korean
    |
    */

    'locales' => [
        'en' => [
            'enabled' => true,
            'name' => 'English',
            'native' => 'English',
            'script' => 'Latn',
            'dir' => 'ltr',
            'flag' => '🇺🇸',
        ],
        'ar' => [
            'enabled' => true,
            'name' => 'Arabic',
            'native' => 'العربية',
            'script' => 'Arab',
            'dir' => 'rtl',
            'flag' => '🇸🇦',
        ],
        'ar-EG' => [
            'enabled' => true,
            'name' => 'Arabic (Egypt)',
            'native' => 'العربية (مصر)',
            'script' => 'Arab',
            'dir' => 'rtl',
            'flag' => '🇪🇬',
        ],
        'ar-SA' => [
            'enabled' => true,
            'name' => 'Arabic (Saudi Arabia)',
            'native' => 'العربية (السعودية)',
            'script' => 'Arab',
            'dir' => 'rtl',
            'flag' => '🇸🇦',
        ],
        'fr' => [
            'enabled' => true,
            'name' => 'French',
            'native' => 'Français',
            'script' => 'Latn',
            'dir' => 'ltr',
            'flag' => '🇫🇷',
        ],
        'es' => [
            'enabled' => true,
            'name' => 'Spanish',
            'native' => 'Español',
            'script' => 'Latn',
            'dir' => 'ltr',
            'flag' => '🇪🇸',
        ],
        'de' => [
            'enabled' => true,
            'name' => 'German',
            'native' => 'Deutsch',
            'script' => 'Latn',
            'dir' => 'ltr',
            'flag' => '🇩🇪',
        ],
        'it' => [
            'enabled' => false,
            'name' => 'Italian',
            'native' => 'Italiano',
            'script' => 'Latn',
            'dir' => 'ltr',
            'flag' => '🇮🇹',
        ],
        'pt' => [
            'enabled' => false,
            'name' => 'Portuguese',
            'native' => 'Português',
            'script' => 'Latn',
            'dir' => 'ltr',
            'flag' => '🇵🇹',
        ],
        'ru' => [
            'enabled' => false,
            'name' => 'Russian',
            'native' => 'Русский',
            'script' => 'Cyrl',
            'dir' => 'ltr',
            'flag' => '🇷🇺',
        ],
        'zh' => [
            'enabled' => false,
            'name' => 'Chinese',
            'native' => '中文',
            'script' => 'Hans',
            'dir' => 'ltr',
            'flag' => '🇨🇳',
        ],
        'ja' => [
            'enabled' => false,
            'name' => 'Japanese',
            'native' => '日本語',
            'script' => 'Jpan',
            'dir' => 'ltr',
            'flag' => '🇯🇵',
        ],
        'ko' => [
            'enabled' => false,
            'name' => 'Korean',
            'native' => '한국어',
            'script' => 'Kore',
            'dir' => 'ltr',
            'flag' => '🇰🇷',
        ],
    ],
];
