{{--
/**
* 404 Not Found Error Page
*
* Displays a user-friendly error page when a requested resource cannot be found.
* Uses HeroUI design system with OKLCH colors for consistent theming across the application.
*
* ## When This Page is Shown:
* - User navigates to a non-existent route or URL
* - Resource has been deleted or permanently removed
* - Incorrect URL is manually entered in the browser
* - Broken link is followed from another page
* - Typo in the URL path
* - Route is not registered in the application
*
* ## HTTP 404 Status:
* Standard HTTP status code indicating the requested resource could not be found.
* Most common HTTP error encountered by users.
*
* ## Design Features:
* - Centered layout with responsive padding for all screen sizes
* - Sad face emoji icon in primary blue color for friendly appearance
* - Large, bold error code (404) for immediate recognition
* - Clear, empathetic error message
* - Call-to-action button to return home
* - Theme-aware colors using CSS variables for light/dark mode support
* - Smooth transitions and hover effects
*
* ## Layout:
* Extends: foundation::layouts.app
* Section: content
*
* ## Styling:
* - Uses Tailwind CSS utility classes for responsive design
* - OKLCH color: var(--primary) - HeroUI primary brand color
* - Mobile-first responsive approach
* - Consistent with HeroUI design system
* - CSS variables for theme compatibility (--foreground, --muted-foreground, --primary)
*
* ## Accessibility:
* - Semantic HTML structure (h1, h2, p, a)
* - Clear visual hierarchy with proper heading levels
* - Descriptive error messages in plain language
* - Keyboard-accessible navigation button
* - Sufficient color contrast for readability
* - SVG icons with proper viewBox for scaling
*
* ## User Experience:
* - Friendly, non-technical language
* - Empathetic tone (acknowledges user frustration)
* - Clear explanation of the issue
* - Actionable next step (return home)
* - Consistent with application design language
* - Reduces user anxiety with friendly icon
*
* ## SEO Considerations:
* - Proper 404 status code sent to search engines
* - Helps search engines understand page doesn't exist
* - Prevents indexing of broken pages
* - Maintains site quality in search rankings
*
* ## Technical Notes:
* - Triggered by Laravel's routing system
* - Can be customized per route or globally
* - Should be logged for monitoring broken links
* - Consider implementing search or suggestions
* - May indicate broken internal links to fix
*
* ## Related Files:
* @see src/pixielity/laravel-Common/resources/views/errors/404.blade.php - Published/customizable version
*
* @extends foundation::layouts.app
* @section title - Page title for browser tab
* @section content - Main error page content
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.404_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.404_title') }}
        </p>

        {{-- Creative Illustration: 404 with Arrows --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(4) }}</span>

                {{-- Integrated Zero Icon --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Top Arrow Circle --}}
                        <path d="M50 10 A40 40 0 0 1 90 50" stroke-linecap="round" />
                        <path d="M82 42 L90 50 L98 42" stroke-linecap="round" stroke-linejoin="round" />

                        {{-- Bottom Arrow Circle --}}
                        <path d="M50 90 A40 40 0 0 1 10 50" stroke-linecap="round" />
                        <path d="M18 58 L10 50 L2 58" stroke-linecap="round" stroke-linejoin="round" />

                        {{-- Middle Dot --}}
                        <circle cx="50" cy="50" r="4" fill="currentColor" stroke="none" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(4) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.404_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ $exception && $exception->getMessage() ? $exception->getMessage() : __('foundation::errors.404_message') }}
        </p>

        {{-- Action Button --}}
        @if(request()->path() !== '/')
            <a href="/"
                class="group inline-flex items-center justify-center min-w-[160px] h-12 px-8 rounded-lg bg-primary text-primary-foreground font-semibold transition-all hover:opacity-90 active:scale-95">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                {{ __('foundation::errors.go_back_home') }}
            </a>
        @endif
    </div>
@endsection