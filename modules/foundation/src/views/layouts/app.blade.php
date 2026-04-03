@php
    use Pixielity\Foundation\Enums\Theme;
@endphp
{{--
Application Layout

Shared layout with HeroUI design system and theme support.
Used across all pixielity for consistent UI.

## Theme Support:
- Auto-detection: Automatically detects system/device theme preference
- Override: Pass $theme variable to override auto-detection
- Values: 'dark', 'light', or 'auto' (default)

## Usage:
```php
// Auto-detect system theme (default)
return view('foundation::layouts.app');

// Force dark theme
return view('foundation::layouts.app', ['theme' => 'dark']);

// Force light theme
return view('foundation::layouts.app', ['theme' => 'light']);

// Explicitly use auto-detection
return view('foundation::layouts.app', ['theme' => 'auto']);
```

@var string $title Page title
@var string $theme Theme preference ('dark', 'light', or 'auto')
--}}
<!DOCTYPE html>
<html lang="en"
    class="{{ isset($theme) && $theme === Theme::DARK() ? Theme::DARK() : (isset($theme) && $theme === Theme::LIGHT() ? Theme::LIGHT() : Theme::SYSTEM()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@hasSection('title') @yield('title') - @elseif(isset($title)) {{ $title }} - @endif
        {{ Str::headline(config('app.name', 'Laravel')) }}
    </title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @include('foundation::layouts.partials.theme-styles')
    @include('foundation::layouts.partials.theme-scripts')

    @stack('styles')
</head>

<body class="antialiased min-h-screen">
    @yield('content')

    @stack('scripts')
</body>

</html>