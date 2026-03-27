<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-glow-primary leading-tight">
                {{ $service->name }}
            </h2>
            <a href="{{ route('services.index') }}" class="text-sm text-glow-accent hover:text-glow-primary transition">
                &larr; Back to Services
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-glow-primary mb-4">{{ $service->name }}</h3>

                            @if($service->description)
                                <p class="text-gray-600 leading-relaxed mb-6">{{ $service->description }}</p>
                            @endif

                            <div class="flex items-center gap-6 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-glow-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-gray-700">{{ $service->duration_minutes }} minutes</span>
                                </div>
                            </div>
                        </div>

                        <div class="md:text-right">
                            <p class="text-3xl font-bold text-glow-primary mb-4">
                                ${{ number_format($service->price, 2) }}
                            </p>
                            <a href="#"
                               class="inline-block px-8 py-3 bg-glow-primary text-white font-semibold rounded-md hover:bg-glow-accent transition">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
