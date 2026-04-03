{{--
/**
* 502 Bad Gateway Error Page
*
* Displays when the server received an invalid response from an upstream server.
* Uses shadcn/ui design system with OKLCH colors for consistent theming.
*
* ## When This Page is Shown:
* - Upstream server is down or unreachable
* - Invalid response from upstream server
* - Gateway/proxy configuration error
* - Network connectivity issues between servers
*
* @extends foundation::layouts.app
* @package Pixielity\Foundation
*/
--}}
@extends('foundation::layouts.app')

@section('title', __('foundation::errors.502_title'))

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen px-6 py-12 text-center">
        {{-- Header Label --}}
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-[--muted-foreground] mb-4">
            {{ __('foundation::errors.502_title') }}
        </p>

        {{-- Creative Illustration: 502 with Server/Network Icon --}}
        <div class="relative mb-8 select-none">
            <div class="flex items-center justify-center font-black leading-none" style="color: var(--primary);">
                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(5) }}</span>

                {{-- Integrated Server/Network Icon --}}
                <div class="relative w-[clamp(6rem,15vw,12rem)] h-[clamp(6rem,15vw,12rem)] mx-1">
                    <svg viewBox="0 0 100 100" class="w-full h-full fill-none stroke-current stroke-[8]"
                        style="color: var(--primary);">
                        {{-- Top Server --}}
                        <rect x="20" y="25" width="60" height="20" rx="4" stroke-linecap="round" />
                        <circle cx="30" cy="35" r="2" fill="currentColor" stroke="none" />
                        <circle cx="38" cy="35" r="2" fill="currentColor" stroke="none" />

                        {{-- Bottom Server --}}
                        <rect x="20" y="55" width="60" height="20" rx="4" stroke-linecap="round" />
                        <circle cx="30" cy="65" r="2" fill="currentColor" stroke="none" />
                        <circle cx="38" cy="65" r="2" fill="currentColor" stroke="none" />

                        {{-- Broken Connection Line --}}
                        <line x1="50" y1="45" x2="50" y2="50" stroke-linecap="round" stroke-dasharray="4 4" />

                        {{-- Warning Symbol --}}
                        <circle cx="70" cy="35" r="8" stroke-width="4" />
                        <line x1="70" y1="30" x2="70" y2="35" stroke-width="4" stroke-linecap="round" />
                        <circle cx="70" cy="38" r="1" fill="currentColor" stroke="none" />
                    </svg>
                </div>

                <span class="text-[clamp(8rem,20vw,15rem)]">{{ localize_number(2) }}</span>
            </div>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[--foreground]">
            {{ __('foundation::errors.502_title') }}
        </h2>
        <p class="text-lg text-[--muted-foreground] max-w-md mx-auto mb-10 leading-relaxed">
            {{ __('foundation::errors.502_message') }}
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
                {{ __('foundation::errors.try_again') }}
            </a>
        @endif
    </div>
@endsection