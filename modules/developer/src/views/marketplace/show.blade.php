@extends('foundation::layouts.app')

@section('title', $app->name[app()->getLocale()] ?? $app->name['en'] ?? 'App Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- App Header --}}
    <div class="flex items-start gap-6 mb-8">
        @if($app->logo)
            <img src="{{ $app->logo }}" alt="" class="w-20 h-20 rounded-xl">
        @else
            <div class="w-20 h-20 rounded-xl bg-blue-100 flex items-center justify-center">
                <span class="text-3xl">📦</span>
            </div>
        @endif
        <div class="flex-1">
            <h1 class="text-2xl font-bold">{{ $app->name[app()->getLocale()] ?? $app->name['en'] ?? '' }}</h1>
            <p class="text-gray-500">by {{ $app->developer_name }}</p>
            <div class="flex items-center gap-4 mt-2">
                <div class="flex items-center gap-1">
                    <span class="text-yellow-500">★</span>
                    <span>{{ number_format($app->rating, 1) }}</span>
                    <span class="text-gray-400">({{ $app->reviews_count }} reviews)</span>
                </div>
                <span class="text-gray-400">{{ $app->install_count }} installs</span>
            </div>
        </div>
        <a href="{{ route('marketplace.consent', $app->id) }}"
           class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
            Install App
        </a>
    </div>

    {{-- Description --}}
    <div class="prose max-w-none mb-8">
        {!! $app->description[app()->getLocale()] ?? $app->description['en'] ?? '' !!}
    </div>

    {{-- Plans --}}
    @if($app->plans->isNotEmpty())
    <h2 class="text-xl font-bold mb-4">Pricing Plans</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        @foreach($app->plans as $plan)
            <div class="border rounded-lg p-6 {{ $plan->recommended ? 'border-blue-500 ring-2 ring-blue-200' : '' }}">
                @if($plan->recommended)
                    <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded-full">Recommended</span>
                @endif
                <h3 class="font-semibold mt-2">{{ $plan->name[app()->getLocale()] ?? $plan->name['en'] ?? '' }}</h3>
                <div class="mt-2">
                    <span class="text-2xl font-bold">${{ number_format($plan->price, 2) }}</span>
                    <span class="text-gray-500">/{{ $plan->recurring }}</span>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- Categories --}}
    <div class="flex flex-wrap gap-2">
        @foreach($app->categories as $category)
            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">
                {{ $category->name[app()->getLocale()] ?? $category->name['en'] ?? $category->slug }}
            </span>
        @endforeach
    </div>
</div>
@endsection
