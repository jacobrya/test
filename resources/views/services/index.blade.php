<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">
            {{ __('Our Services') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($services->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <p class="text-glow-accent text-lg">No services available at the moment.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-glow-primary mb-2">
                                    {{ $service->name }}
                                </h3>
                                @if($service->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                        {{ $service->description }}
                                    </p>
                                @endif
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-glow-accent font-medium">
                                        ${{ number_format($service->price, 2) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $service->duration_minutes }} min
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('services.show', $service) }}"
                                       class="flex-1 text-center px-4 py-2 text-sm border border-glow-accent text-glow-accent rounded-md hover:bg-glow-bg transition">
                                        Details
                                    </a>
                                    <a href="#"
                                       class="flex-1 text-center px-4 py-2 text-sm bg-glow-primary text-white rounded-md hover:bg-glow-accent transition">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
