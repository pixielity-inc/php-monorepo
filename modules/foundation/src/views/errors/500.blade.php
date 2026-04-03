{{--
/**
* 500 Internal Server Error Page
*
* Displays when an unexpected server error occurs during request processing.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - Unhandled exception in application code
* - Database connection failures
* - Fatal PHP errors
* - Configuration errors
* - Third-party service failures
*
* ## HTTP 500 Status:
* Generic server error indicating something went wrong on the server side.
* Should be logged and investigated by developers.
*
* ## Design Features:
* - Centered layout with responsive padding
* - Document/file icon in destructive color (red)
* - Clear error code (500) and message
* - Reassuring message that team is working on it
* - Call-to-action button to return home
* - Theme-aware colors using CSS variables
*
* ## Layout:
* Extends: foundation::layouts.app
* Section: content
*
* ## Styling:
* - Uses Tailwind CSS utility classes
* - OKLCH color: var(--danger) - Destructive/Error red
* - Responsive design with mobile-first approach
* - Consistent with shadcn/ui component library
*
* ## User Actions:
* - Return to home page
* - Wait for issue to be resolved
* - Contact support if persistent
*
* ## Technical Notes:
* - Errors should be logged to storage/logs/laravel.log
* - Enable debug mode in development to see stack traces
* - In production, never expose sensitive error details
* - Consider error monitoring services (Sentry, Bugsnag)
*
* @extends foundation::layouts.app
* @section title - Page title for browser tab
* @section content - Main error page content
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.500_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.500_title') }}
        </p>

        {{-- Creative Illustration: 500 with Robot --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(5) }}</span>

                {{-- Integrated Robot Icon --}}
                <div
                    class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-2 flex items-center justify-center">
                    {{-- Robot Head SVG --}}
                    <svg viewBox="0 0 100 100" class="w-[80%] h-[80%] fill-none stroke-current stroke-[6]"
                        style="color: var(--primary);">
                        {{-- Head Body --}}
                        <rect x="20" y="30" width="60" height="50" rx="8" />
                        {{-- Antennas --}}
                        <path d="M50 30 V15" stroke-linecap="round" />
                        <circle cx="50" cy="12" r="4" fill="currentColor" stroke="none" />

                        {{-- Eyes (Sad) --}}
                        <path d="M35 50 Q40 45 45 50" stroke-linecap="round" />
                        <path d="M55 50 Q60 45 65 50" stroke-linecap="round" />

                        {{-- Mouth (Sad) --}}
                        <path d="M40 70 Q50 60 60 70" stroke-linecap="round" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(0) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.500_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ $exception && $exception->getMessage() ? $exception->getMessage() : __('foundation::errors.500_message') }}
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