<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('My Profile') }}</h2>
            <a href="{{ route('specialist.profile.edit') }}" class="px-4 py-2 bg-glow-primary text-white text-sm rounded-md hover:bg-glow-accent transition">Edit Profile</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-8">
                <div class="flex flex-col sm:flex-row gap-6 mb-6">
                    @if($specialist->photo)
                        <img src="{{ asset('storage/' . $specialist->photo) }}" class="w-32 h-32 rounded-full object-cover shadow-md">
                    @else
                        <div class="w-32 h-32 rounded-full bg-glow-accent/20 flex items-center justify-center shadow-md">
                            <span class="text-glow-primary font-bold text-4xl">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-glow-primary">{{ Auth::user()->name }}</h1>
                        <p class="text-gray-500">{{ $specialist->experience_years }} {{ Str::plural('year', $specialist->experience_years) }} of experience</p>
                        @if($specialist->salon)
                            <p class="text-sm text-glow-accent mt-1">{{ $specialist->salon->name }}</p>
                        @else
                            <p class="text-sm text-yellow-600 mt-1">Not assigned to a salon yet</p>
                        @endif
                        @if($specialist->average_rating)
                            <p class="text-yellow-600 mt-1">{{ str_repeat('★', round($specialist->average_rating)) }}{{ str_repeat('☆', 5 - round($specialist->average_rating)) }} {{ $specialist->average_rating }}/5</p>
                        @endif
                    </div>
                </div>
                @if($specialist->bio)
                    <div class="border-t pt-4 mb-4">
                        <h3 class="font-semibold text-glow-primary mb-2">Bio</h3>
                        <p class="text-gray-700">{{ $specialist->bio }}</p>
                    </div>
                @endif
                @if($specialist->services->isNotEmpty())
                    <div class="border-t pt-4">
                        <h3 class="font-semibold text-glow-primary mb-2">My Services</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($specialist->services as $service)
                                <span class="px-3 py-1 text-sm rounded-full bg-glow-bg text-glow-primary">{{ $service->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
