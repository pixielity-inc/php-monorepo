{{--
/**
* 429 Too Many Requests Error Page
*
* Displays when a user has exceeded the rate limit for requests.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - User has exceeded API rate limits
* - Too many requests in a short time period
* - Throttle middleware has blocked the request
* - DDoS protection has been triggered
*
* ## HTTP 429 Status:
* Standard HTTP status code for rate limiting.
* Often includes Retry-After header indicating when to retry.
*
* ## Design Features:
* - Centered layout with responsive padding
* - Warning triangle icon in warning color (amber)
* - Clear error code (429) and message
* - Call-to-action button to return home
* - Theme-aware colors using CSS variables
*
* ## Layout:
* Extends: foundation::layouts.app
* Section: content
*
* ## Styling:
* - Uses Tailwind CSS utility classes
* - OKLCH color: var(--warning) - Warning/Amber
* - Responsive design with mobile-first approach
* - Consistent with shadcn/ui component library
*
* ## User Actions:
* - Wait before retrying
* - Return to home page
* - Check rate limit documentation
*
* ## Technical Notes:
* - Laravel throttle middleware default: 60 requests/minute
* - Can be configured per route or globally
* - Rate limits reset after time window expires
*
* @extends foundation::layouts.app
* @section title - Page title for browser tab
* @section content - Main error page content
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.429_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.429_title') }}
        </p>

        {{-- Creative Illustration: 429 with Hourglass --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(4) }}</span>

                {{-- Integrated Hourglass Icon --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Top Part --}}
                        <path d="M30 20 H70 L50 50 Z" />
                        {{-- Bottom Part --}}
                        <path d="M30 80 H70 L50 50 Z" />
                        {{-- Sand falling --}}
                        <line x1="50" y1="45" x2="50" y2="55" stroke-dasharray="2 4" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(9) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.429_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ $exception && $exception->getMessage() ? $exception->getMessage() : __('foundation::errors.429_message') }}
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