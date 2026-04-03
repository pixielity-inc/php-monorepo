{{--
/**
* 503 Service Unavailable Error Page
*
* Displays when the application is temporarily unavailable due to maintenance or overload.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - Application is in maintenance mode (bin/laravel down)
* - Server is overloaded or at capacity
* - Scheduled maintenance window
* - Deployment in progress
* - Database maintenance
*
* ## HTTP 503 Status:
* Indicates temporary unavailability. Server should include Retry-After header.
* Unlike 500, this is expected and planned downtime.
*
* ## Design Features:
* - Centered layout with responsive padding
* - Settings/gear icon in warning color (amber)
* - Clear error code (503) and message
* - Maintenance-focused messaging
* - Try Again button with refresh icon
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
* - Wait for maintenance to complete
* - Try again after some time
* - Check status page if available
*
* ## Technical Notes:
* - Enable maintenance mode: bin/laravel down
* - Disable maintenance mode: bin/laravel up
* - Can allow specific IPs during maintenance
* - Custom maintenance message: bin/laravel down --message="..."
* - Retry-After header suggests when to retry
*
* @extends foundation::layouts.app
* @section title - Page title for browser tab
* @section content - Main error page content
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.503_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.503_title') }}
        </p>

        {{-- Creative Illustration: 503 with Clock/Wrench --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(5) }}</span>

                {{-- Integrated Maintenance Icon --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Clock Face --}}
                        <circle cx="50" cy="50" r="40" stroke-linecap="round" />
                        {{-- Clock Hands --}}
                        <path d="M50 25 V50 H75" stroke-linecap="round" stroke-linejoin="round" />
                        {{-- Wrench Shape in background --}}
                        <path d="M80 80 L90 90" stroke-width="4" stroke-linecap="round" opacity="0.5" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(3) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.503_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ $exception && $exception->getMessage() ? $exception->getMessage() : __('foundation::errors.503_message') }}
        </p>

        {{-- Action Button --}}
        <div class="flex justify-center">
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
    </div>
@endsection