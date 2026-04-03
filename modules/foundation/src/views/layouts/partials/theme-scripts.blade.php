@php
    use Pixielity\Foundation\Enums\Theme;
@endphp
{{--
/**
* Theme Auto-Detection and Management Scripts
*
* Automatically detects and applies the user's system theme preference
* when theme is set to 'auto' (default). Respects explicit theme overrides.
*/
--}}
<script>
    /**
     * Tailwind CSS configuration
     * This is only needed for the CDN version of Tailwind.
     */
    if (typeof tailwind !== 'undefined') {
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    borderRadius: {
                        lg: 'var(--radius)',
                        md: 'calc(var(--radius) - 2px)',
                        sm: 'calc(var(--radius) - 4px)'
                    }
                }
            }
        };
    }

    /**
     * Theme Auto-Detection and Management
     */
    (function () {
        const html = document.documentElement;

        // Use Enum values from PHP
        const THEME_SYSTEM = '{{ Theme::SYSTEM() }}';
        const THEME_DARK = '{{ Theme::DARK() }}';
        const THEME_LIGHT = '{{ Theme::LIGHT() }}';

        // Check if theme is explicitly set to auto/system
        // We check for both 'system' (legacy) and the Enum value
        const isAuto = html.classList.contains(THEME_SYSTEM) ||
            html.classList.contains(THEME_SYSTEM);

        // If theme is not set to auto, don't auto-detect
        if (!isAuto) {
            return;
        }

        /**
         * Apply theme based on system preference
         */
        function applySystemTheme() {
            // Check if user prefers dark mode
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            // Apply appropriate theme class
            if (prefersDark) {
                html.classList.add(THEME_DARK);
                html.classList.remove(THEME_LIGHT);
            } else {
                html.classList.add(THEME_LIGHT);
                html.classList.remove(THEME_DARK);
            }
        }

        // Apply theme immediately to prevent flash
        applySystemTheme();

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applySystemTheme);
    })();
</script>