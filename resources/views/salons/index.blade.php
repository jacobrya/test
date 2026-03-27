<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Salons') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('salons.index') }}" method="GET" class="mb-8 flex gap-2 max-w-lg">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or location..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent">
                <button type="submit" class="px-4 py-2 bg-glow-primary text-white rounded-md hover:bg-glow-accent transition text-sm">Search</button>
            </form>
            @if($salons->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <p class="text-gray-500 text-lg">No salons found.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($salons as $salon)
                        <a href="{{ route('salons.show', $salon) }}" class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow-md transition-shadow block">
                            <div class="h-40 bg-glow-accent/10 flex items-center justify-center">
                                @if($salon->photo)
                                    <img src="{{ asset('storage/' . $salon->photo) }}" alt="{{ $salon->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl text-glow-accent/40">&#9733;</span>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-glow-primary mb-1">{{ $salon->name }}</h3>
                                @if($salon->address)
                                    <p class="text-sm text-gray-500 mb-2">{{ $salon->address }}</p>
                                @endif
                                @if($salon->description)
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $salon->description }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $salons->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
