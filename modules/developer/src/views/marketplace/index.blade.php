@extends('foundation::layouts.app')

@section('title', 'App Marketplace')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">App Marketplace</h1>

    {{-- Categories --}}
    <div class="flex flex-wrap gap-3 mb-8">
        @foreach($categories as $category)
            <a href="?category={{ $category->slug }}"
               class="px-4 py-2 rounded-full text-sm font-medium
                      {{ request('category') === $category->slug ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $category->name[app()->getLocale()] ?? $category->name['en'] ?? $category->slug }}
            </a>
        @endforeach
    </div>

    {{-- App Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($apps as $app)
            <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    @if($app->logo)
                        <img src="{{ $app->logo }}" alt="{{ $app->name['en'] ?? '' }}" class="w-12 h-12 rounded-lg">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-{{ $app->color ?? 'blue' }}-100 flex items-center justify-center">
                            <span class="text-xl">📦</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold">{{ $app->name[app()->getLocale()] ?? $app->name['en'] ?? '' }}</h3>
                        <p class="text-sm text-gray-500">{{ $app->developer_name }}</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                    {{ $app->short_description[app()->getLocale()] ?? $app->short_description['en'] ?? '' }}
                </p>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <span class="text-yellow-500">★</span>
                        <span class="text-sm">{{ number_format($app->rating, 1) }}</span>
                        <span class="text-xs text-gray-400">({{ $app->reviews_count }})</span>
                    </div>
                    <a href="{{ route('marketplace.show', $app->id) }}"
                       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        View
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                No apps found.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $apps->links() }}
    </div>
</div>
@endsection
