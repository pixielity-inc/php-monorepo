{{--
/**
* 422 Unprocessable Entity Error Page
*
* Displays when the request is well-formed but contains semantic errors.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - Validation errors in form submission
* - Invalid data format (correct syntax, wrong semantics)
* - Business logic validation failures
* - Required fields missing or invalid
*
* @extends foundation::layouts.app
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.422_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.422_title') }}
        </p>

        {{-- Creative Illustration: 422 with Document Error Icon --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(4) }}</span>

                {{-- Integrated Document Error Icon --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Document Rectangle --}}
                        <path d="M25 10 L65 10 L85 30 L85 90 L25 90 Z" stroke-linecap="round" stroke-linejoin="round" />

                        {{-- Folded Corner --}}
                        <path d="M65 10 L65 30 L85 30" stroke-linecap="round" stroke-linejoin="round" />

                        {{-- Error Lines (crossed out) --}}
                        <line x1="35" y1="45" x2="75" y2="45" stroke-linecap="round" stroke-width="4" />
                        <line x1="35" y1="55" x2="65" y2="55" stroke-linecap="round" stroke-width="4" />
                        <line x1="35" y1="65" x2="70" y2="65" stroke-linecap="round" stroke-width="4" />

                        {{-- X Mark Over Document --}}
                        <line x1="40" y1="70" x2="70" y2="80" stroke-linecap="round" stroke-width="6" />
                        <line x1="70" y1="70" x2="40" y2="80" stroke-linecap="round" stroke-width="6" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(2) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.422_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ __('foundation::errors.422_message') }}
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
                {{ __('foundation::errors.go_back') }}
            </a>
        @endif
    </div>
@endsection