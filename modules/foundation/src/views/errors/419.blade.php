{{--
/**
* 419 Page Expired Error Page
*
* Displays when a CSRF token has expired or a form submission is stale.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - CSRF token has expired (Laravel's default session timeout)
* - User left form open too long before submitting
* - Session was cleared/invalidated
* - Form was submitted after browser back button
*
* ## HTTP 419 Status:
* Laravel-specific status code for CSRF token mismatch.
* Not part of official HTTP specification but widely recognized.
*
* ## Design Features:
* - Centered layout with responsive padding
* - Clock icon in warning color (orange/amber)
* - Clear error code (419) and message
* - Refresh action button with circular arrow icon
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
* - Refresh the page to get new CSRF token
* - Return to home page
* - Resubmit form with fresh token
*
* ## Technical Notes:
* - CSRF tokens expire based on session lifetime
* - Default Laravel session timeout: 120 minutes
* - Refreshing page generates new token
*
* @extends foundation::layouts.app
* @section title - Page title for browser tab
* @section content - Main error page content
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.419_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.419_title') }}
        </p>

        {{-- Creative Illustration: 419 with Expired Clock --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(4) }}</span>

                {{-- Integrated Expired Clock Icon --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Clock Circle --}}
                        <circle cx="50" cy="50" r="35" stroke-linecap="round" />

                        {{-- Clock Hands (showing expired time) --}}
                        <line x1="50" y1="50" x2="50" y2="25" stroke-linecap="round" />
                        <line x1="50" y1="50" x2="70" y2="50" stroke-linecap="round" />

                        {{-- X Mark Over Clock (expired) --}}
                        <line x1="30" y1="30" x2="70" y2="70" stroke-linecap="round" stroke-width="6" />
                        <line x1="70" y1="30" x2="30" y2="70" stroke-linecap="round" stroke-width="6" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(9) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.419_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ __('foundation::errors.419_message') }}
        </p>

        {{-- Action Button --}}
        @if(request()->path() !== '/')
            <a href="/"
                class="group inline-flex items-center justify-center min-w-[160px] h-12 px-8 rounded-lg bg-primary text-primary-foreground font-semibold transition-all hover:opacity-90 active:scale-95">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:rotate-[-360deg] group-hover:duration-700" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                {{ __('foundation::errors.refresh_page') }}
            </a>
        @endif
    </div>
@endsection