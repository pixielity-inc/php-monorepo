{{--
/**
* 403 Forbidden Error Page
*
* Displays when a user attempts to access a resource they don't have permission for.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - User is authenticated but lacks required permissions
* - User tries to access admin-only resources
* - User attempts unauthorized actions (delete, edit, etc.)
* - IP address is blocked or restricted
*
* ## HTTP 401 vs 403:
* - 401: User needs to authenticate (not logged in)
* - 403: User is authenticated but lacks permission
*
* ## Design Features:
* - Centered layout with responsive padding
* - Shield icon in destructive color (red)
* - Clear error code (403) and message
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
* - Contact administrator for access
*
* @extends foundation::layouts.app
* @section title - Page title for browser tab
* @section content - Main error page content
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.403_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.403_title') }}
        </p>

        {{-- Creative Illustration: 403 with Forbidden Sign --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(4) }}</span>

                {{-- Integrated Forbidden Sign --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Outer Circle --}}
                        <circle cx="50" cy="50" r="40" stroke-linecap="round" />
                        {{-- Diagonal Line --}}
                        <line x1="22" y1="22" x2="78" y2="78" stroke-linecap="round" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(3) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.403_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ $exception && $exception->getMessage() ? $exception->getMessage() : __('foundation::errors.403_message') }}
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