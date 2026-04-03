{{--
/**
* HeroUI Color System with OKLCH
*
* HeroUI uses semantic color tokens that adapt to light/dark themes.
* Colors are defined using OKLCH color space for better perceptual uniformity.
*
* This file is dynamic and processes Laravel configuration.
*/
--}}
<style>
    :root {
        --radius: 0.75rem;

        /* Base colors */
        --background:
            {{ config('theme.themes.light.colors.background', 'oklch(1 0 0)') }}
        ;
        --foreground:
            {{ config('theme.themes.light.colors.text', 'oklch(0.15 0.005 250)') }}
        ;

        /* Default/Neutral colors */
        --default:
            {{ config('theme.themes.light.colors.background', 'oklch(0.96 0.002 250)') }}
        ;
        --default-foreground:
            {{ config('theme.themes.light.colors.text', 'oklch(0.15 0.005 250)') }}
        ;

        /* Primary brand color */
        --primary:
            {{ config('theme.themes.light.colors.primary', 'oklch(0.55 0.22 260)') }}
        ;
        --primary-foreground:
            {{ config('theme.themes.light.colors.primaryForeground', 'oklch(1 0 0)') }}
        ;

        /* Secondary color */
        --secondary:
            {{ config('theme.themes.light.colors.secondary', 'oklch(0.92 0.01 250)') }}
        ;
        --secondary-foreground:
            {{ config('theme.themes.light.colors.text', 'oklch(0.15 0.005 250)') }}
        ;

        /* Success state */
        --success:
            {{ config('theme.themes.light.colors.success', 'oklch(0.65 0.18 150)') }}
        ;
        --success-foreground:
            {{ config('theme.themes.light.colors.background', 'oklch(1 0 0)') }}
        ;

        /* Warning state */
        --warning:
            {{ config('theme.themes.light.colors.warning', 'oklch(0.75 0.15 85)') }}
        ;
        --warning-foreground:
            {{ config('theme.themes.light.colors.text', 'oklch(0.15 0.005 250)') }}
        ;

        /* Danger/Error state */
        --danger:
            {{ config('theme.themes.light.colors.error', 'oklch(0.60 0.22 25)') }}
        ;
        --danger-foreground:
            {{ config('theme.themes.light.colors.background', 'oklch(1 0 0)') }}
        ;

        /* Muted/Subtle elements */
        --muted:
            {{ config('theme.themes.light.colors.background', 'oklch(0.96 0.002 250)') }}
        ;
        --muted-foreground:
            {{ config('theme.themes.light.colors.textSecondary', 'oklch(0.50 0.01 250)') }}
        ;

        /* Borders and inputs */
        --border:
            {{ config('theme.themes.light.colors.border', 'oklch(0.90 0.005 250)') }}
        ;
        --input:
            {{ config('theme.themes.light.colors.border', 'oklch(0.90 0.005 250)') }}
        ;
        --ring:
            {{ config('theme.themes.light.colors.primary', 'oklch(0.55 0.22 260)') }}
        ;

        /* Focus state */
        --focus:
            {{ config('theme.themes.light.colors.primary', 'oklch(0.55 0.22 260)') }}
        ;
    }

    .dark {
        --background:
            {{ config('theme.themes.dark.colors.background', 'oklch(0.15 0.005 250)') }}
        ;
        --foreground:
            {{ config('theme.themes.dark.colors.text', 'oklch(0.98 0.002 250)') }}
        ;

        /* Default/Neutral colors */
        --default:
            {{ config('theme.themes.dark.colors.background', 'oklch(0.25 0.01 250)') }}
        ;
        --default-foreground:
            {{ config('theme.themes.dark.colors.text', 'oklch(0.98 0.002 250)') }}
        ;

        /* Primary brand color */
        --primary:
            {{ config('theme.themes.dark.colors.primary', 'oklch(0.65 0.25 260)') }}
        ;
        --primary-foreground:
            {{ config('theme.themes.dark.colors.primaryForeground', 'oklch(1 0 0)') }}
        ;

        /* Secondary color */
        --secondary:
            {{ config('theme.themes.dark.colors.secondary', 'oklch(0.30 0.015 250)') }}
        ;
        --secondary-foreground:
            {{ config('theme.themes.dark.colors.text', 'oklch(0.98 0.002 250)') }}
        ;

        /* Success state */
        --success:
            {{ config('theme.themes.dark.colors.success', 'oklch(0.70 0.20 150)') }}
        ;
        --success-foreground:
            {{ config('theme.themes.dark.colors.background', 'oklch(0.15 0.005 250)') }}
        ;

        /* Warning state */
        --warning:
            {{ config('theme.themes.dark.colors.warning', 'oklch(0.80 0.18 85)') }}
        ;
        --warning-foreground:
            {{ config('theme.themes.dark.colors.text', 'oklch(0.15 0.005 250)') }}
        ;

        /* Danger/Error state */
        --danger:
            {{ config('theme.themes.dark.colors.error', 'oklch(0.65 0.25 25)') }}
        ;
        --danger-foreground:
            {{ config('theme.themes.dark.colors.text', 'oklch(0.98 0.002 250)') }}
        ;

        /* Muted/Subtle elements */
        --muted:
            {{ config('theme.themes.dark.colors.background', 'oklch(0.25 0.01 250)') }}
        ;
        --muted-foreground:
            {{ config('theme.themes.dark.colors.textSecondary', 'oklch(0.60 0.015 250)') }}
        ;

        /* Borders and inputs */
        --border:
            {{ config('theme.themes.dark.colors.border', 'oklch(0.30 0.015 250)') }}
        ;
        --input:
            {{ config('theme.themes.dark.colors.border', 'oklch(0.35 0.02 250)') }}
        ;
        --ring:
            {{ config('theme.themes.dark.colors.primary', 'oklch(0.65 0.25 260)') }}
        ;

        /* Focus state */
        --focus:
            {{ config('theme.themes.dark.colors.primary', 'oklch(0.65 0.25 260)') }}
        ;
    }

    * {
        border-color: var(--border);
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background-color: var(--background);
        color: var(--foreground);
    }
</style>