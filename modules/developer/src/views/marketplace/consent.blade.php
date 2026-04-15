@extends('foundation::layouts.app')

@section('title', 'Install ' . ($app->name[app()->getLocale()] ?? $app->name['en'] ?? 'App'))

@section('content')
<div class="container mx-auto px-4 py-8 max-w-lg">
    <div class="border rounded-xl p-8">
        <h1 class="text-xl font-bold mb-6 text-center">Install</h1>

        {{-- App + Store Connection --}}
        <div class="flex items-center justify-center gap-4 mb-8">
            <div class="text-center">
                <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-2">
                    <span class="text-2xl">🏪</span>
                </div>
                <span class="text-sm">My Store</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
                <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
                <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
            </div>
            <div class="text-center">
                @if($app->logo)
                    <img src="{{ $app->logo }}" alt="" class="w-16 h-16 rounded-xl mx-auto mb-2">
                @else
                    <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-2">
                        <span class="text-2xl">📦</span>
                    </div>
                @endif
                <span class="text-sm">{{ $app->name[app()->getLocale()] ?? $app->name['en'] ?? '' }}</span>
            </div>
        </div>

        <p class="text-sm text-gray-500 text-center mb-2">by {{ $app->developer_name }}</p>

        {{-- Permissions --}}
        @if(!empty($scopes))
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-center mb-3">This app needs to</h3>
            <div class="grid grid-cols-2 gap-2">
                @foreach($scopes as $scope)
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-green-500">✓</span>
                        <span>{{ is_array($scope) ? ($scope['description'] ?? $scope['key'] ?? $scope) : $scope }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Privacy --}}
        <p class="text-xs text-gray-400 text-center mb-6">
            You're agreeing to share data with this app.
            @if($app->privacy_policy_url)
                View the <a href="{{ $app->privacy_policy_url }}" class="text-blue-500 underline" target="_blank">developer's privacy policy</a>.
            @endif
        </p>

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('marketplace.show', $app->id) }}"
               class="flex-1 px-4 py-3 border rounded-lg text-center hover:bg-gray-50">
                Cancel
            </a>
            <form action="{{ url('/api/marketplace/apps/' . $app->id . '/install') }}" method="POST" class="flex-1">
                @csrf
                @foreach($scopes as $key => $scope)
                    <input type="hidden" name="granted_scopes[]" value="{{ is_string($key) ? $key : (is_array($scope) ? ($scope['key'] ?? $scope) : $scope) }}">
                @endforeach
                <button type="submit"
                        class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Install app
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
