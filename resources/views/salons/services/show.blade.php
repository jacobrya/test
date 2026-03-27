<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ $service->name }} &mdash; {{ $salon->name }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
                <h1 class="text-2xl font-bold text-glow-primary mb-2">{{ $service->name }}</h1>
                <div class="flex gap-4 mb-4">
                    <span class="text-glow-accent font-semibold text-lg">${{ number_format($service->price, 2) }}</span>
                    <span class="text-gray-500">{{ $service->duration_minutes }} minutes</span>
                </div>
                @if($service->description)<p class="text-gray-700 leading-relaxed">{{ $service->description }}</p>@endif
            </div>

            <div class="bg-white rounded-xl shadow-sm p-8">
                <h3 class="text-lg font-semibold text-glow-primary mb-4">Specialists offering this service</h3>
                @forelse($service->specialists->where('is_active', true) as $specialist)
                    <div class="flex items-center justify-between p-4 rounded-lg bg-glow-bg mb-3 last:mb-0">
                        <div class="flex items-center gap-3">
                            @if($specialist->photo)
                                <img src="{{ asset('storage/' . $specialist->photo) }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-glow-accent/20 flex items-center justify-center">
                                    <span class="text-glow-primary font-bold">{{ strtoupper(substr($specialist->user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div>
                                <span class="font-medium text-gray-900">{{ $specialist->user->name }}</span>
                                @if($specialist->average_rating)
                                    <p class="text-xs text-yellow-600">{{ str_repeat('★', round($specialist->average_rating)) }}{{ str_repeat('☆', 5 - round($specialist->average_rating)) }} {{ $specialist->average_rating }}</p>
                                @endif
                            </div>
                        </div>
                        @auth
                            @if(Auth::user()->isClient())
                                <a href="{{ route('booking.show', [$salon, $specialist, $service]) }}" class="px-4 py-2 text-sm bg-glow-primary text-white rounded-md hover:bg-glow-accent transition">Book</a>
                            @endif
                        @endauth
                    </div>
                @empty
                    <p class="text-gray-500 italic">No specialists offer this service yet.</p>
                @endforelse
            </div>

            <div class="mt-6">
                <a href="{{ route('salons.show', $salon) }}" class="text-glow-accent hover:text-glow-primary font-medium transition">&larr; Back to {{ $salon->name }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
