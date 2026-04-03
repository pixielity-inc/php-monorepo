{{--
/**
* 405 Method Not Allowed Error Page
*
* Displays when the HTTP method is not supported for the requested resource.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - Using GET when POST is required
* - Using POST when GET is required
* - Method not allowed by route definition
* - HTTP method not supported by endpoint
*
* @extends foundation::layouts.app
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.405_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.405_title') }}
        </p>

        {{-- Creative Illustration: 405 with Block/X Icon --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(4) }}</span>

                {{-- Integrated Block/X Icon --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Octagon Stop Sign --}}
                        <path d="M30 10 L70 10 L90 30 L90 70 L70 90 L30 90 L10 70 L10 30 Z" stroke-linecap="round"
                            stroke-linejoin="round" />

                        {{-- X Mark --}}
                        <line x1="35" y1="35" x2="65" y2="65" stroke-linecap="round" />
                        <line x1="65" y1="35" x2="35" y2="65" stroke-linecap="round" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(5) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.405_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ __('foundation::errors.405_message') }}
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