<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ $salon->name }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex flex-col sm:flex-row gap-6">
                    <div class="w-full sm:w-48 h-48 bg-glow-accent/10 rounded-lg flex items-center justify-center overflow-hidden">
                        @if($salon->photo)
                            <img src="{{ asset('storage/' . $salon->photo) }}" alt="{{ $salon->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-5xl text-glow-accent/40">&#9733;</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-glow-primary mb-2">{{ $salon->name }}</h1>
                        @if($salon->address)<p class="text-gray-500 mb-1">{{ $salon->address }}</p>@endif
                        @if($salon->phone)<p class="text-gray-500 mb-3">{{ $salon->phone }}</p>@endif
                        @if($salon->description)<p class="text-gray-700">{{ $salon->description }}</p>@endif
                    </div>
                </div>
            </div>

            <div x-data="{ tab: 'specialists' }" class="mb-8">
                <div class="flex gap-4 mb-6 border-b">
                    <button @click="tab = 'specialists'" :class="tab === 'specialists' ? 'border-glow-accent text-glow-primary' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-medium transition">
                        Specialists ({{ $salon->specialists->where('is_active', true)->where('is_approved', true)->count() }})
                    </button>
                    <button @click="tab = 'services'" :class="tab === 'services' ? 'border-glow-accent text-glow-primary' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-medium transition">
                        Services ({{ $salon->services->where('is_active', true)->count() }})
                    </button>
                </div>

                <div x-show="tab === 'specialists'">
                    @if($salon->specialists->where('is_active', true)->where('is_approved', true)->isEmpty())
                        <p class="text-gray-500">No specialists in this salon yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($salon->specialists->where('is_active', true)->where('is_approved', true) as $specialist)
                                <div class="bg-white rounded-xl shadow-sm p-5">
                                    <div class="flex items-center mb-3">
                                        @if($specialist->photo)
                                            <img src="{{ asset('storage/' . $specialist->photo) }}" class="w-14 h-14 rounded-full object-cover">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-glow-accent/20 flex items-center justify-center">
                                                <span class="text-glow-primary font-bold text-lg">{{ strtoupper(substr($specialist->user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div class="ml-3">
                                            <h3 class="font-semibold text-glow-primary">{{ $specialist->user->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $specialist->experience_years }} {{ Str::plural('year', $specialist->experience_years) }} exp.</p>
                                            @if($specialist->average_rating)
                                                <p class="text-xs text-yellow-600">{{ str_repeat('★', round($specialist->average_rating)) }}{{ str_repeat('☆', 5 - round($specialist->average_rating)) }} {{ $specialist->average_rating }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('salons.specialist', [$salon, $specialist]) }}" class="block text-center px-4 py-2 text-sm border border-glow-accent text-glow-accent rounded-md hover:bg-glow-bg transition">View Profile</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div x-show="tab === 'services'" x-cloak>
                    @if($salon->services->where('is_active', true)->isEmpty())
                        <p class="text-gray-500">No services available yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($salon->services->where('is_active', true) as $service)
                                <div class="bg-white rounded-xl shadow-sm p-5">
                                    <h3 class="font-semibold text-glow-primary mb-2">{{ $service->name }}</h3>
                                    @if($service->description)<p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $service->description }}</p>@endif
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-glow-accent font-medium">${{ number_format($service->price, 2) }}</span>
                                        <span class="text-sm text-gray-500">{{ $service->duration_minutes }} min</span>
                                    </div>
                                    <a href="{{ route('salons.service', [$salon, $service]) }}" class="block text-center px-4 py-2 text-sm border border-glow-accent text-glow-accent rounded-md hover:bg-glow-bg transition">Details</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <a href="{{ route('salons.index') }}" class="text-glow-accent hover:text-glow-primary font-medium transition">&larr; Back to Salons</a>
        </div>
    </div>
</x-app-layout>
