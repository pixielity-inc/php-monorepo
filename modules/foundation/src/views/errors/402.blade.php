{{--
/**
* 402 Payment Required Error Page
*
* Displays when payment is required to access a resource or feature.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - User attempts to access premium/paid features
* - Subscription has expired or is inactive
* - Payment method is required but not provided
* - API endpoint requires payment/credits
*
* ## HTTP 402 Status:
* Originally reserved for future use in digital payment systems.
* Now commonly used for:
* - Subscription-based services
* - Pay-per-use APIs
* - Premium feature access
* - Credit/quota exhaustion
*
* ## Design Features:
* - Centered layout with responsive padding
* - Credit card icon in destructive color (red)
* - Clear error code (402) and message
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
* - Typically redirected to billing/subscription page
*
* @extends foundation::layouts.app
* @section title - Page title for browser tab
* @section content - Main error page content
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.402_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.402_title') }}
        </p>

        {{-- Creative Illustration: 402 with Credit Card --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(4) }}</span>

                {{-- Integrated Credit Card Icon --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Credit Card Rectangle --}}
                        <rect x="15" y="30" width="70" height="40" rx="8" stroke-linecap="round" />

                        {{-- Magnetic Stripe --}}
                        <line x1="15" y1="45" x2="85" y2="45" stroke-linecap="round" />

                        {{-- Card Chip --}}
                        <rect x="25" y="52" width="12" height="10" rx="2" stroke-width="4" />

                        {{-- Card Numbers (dots) --}}
                        <circle cx="50" cy="57" r="2" fill="currentColor" stroke="none" />
                        <circle cx="56" cy="57" r="2" fill="currentColor" stroke="none" />
                        <circle cx="62" cy="57" r="2" fill="currentColor" stroke="none" />
                        <circle cx="68" cy="57" r="2" fill="currentColor" stroke="none" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(2) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.402_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ __('foundation::errors.402_message') }}
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