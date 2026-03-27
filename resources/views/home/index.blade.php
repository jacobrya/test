<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-glow-primary mb-4">Welcome to GlowBook</h1>
                <p class="text-lg text-gray-600 mb-8">Find and book beauty services at the best salons near you</p>
                <form action="{{ route('salons.index') }}" method="GET" class="max-w-xl mx-auto flex gap-2">
                    <input type="text" name="search" placeholder="Search salons by name or location..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent">
                    <button type="submit" class="px-6 py-2 bg-glow-primary text-white rounded-md hover:bg-glow-accent transition">Search</button>
                </form>
            </div>

            @if($salons->isNotEmpty())
                <h2 class="text-2xl font-semibold text-glow-primary mb-6">Featured Salons</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($salons as $salon)
                        <a href="{{ route('salons.show', $salon) }}" class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow-md transition-shadow duration-200 block">
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
                <div class="mt-8 text-center">
                    <a href="{{ route('salons.index') }}" class="text-glow-accent hover:text-glow-primary font-medium transition">View all salons &rarr;</a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <p class="text-gray-500 text-lg">No salons available yet. Check back soon!</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
