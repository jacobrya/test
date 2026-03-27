<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ $specialist->user->name }} &mdash; {{ $salon->name }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
                <div class="flex flex-col sm:flex-row gap-6 mb-6">
                    @if($specialist->photo)
                        <img src="{{ asset('storage/' . $specialist->photo) }}" class="w-32 h-32 rounded-full object-cover shadow-md">
                    @else
                        <div class="w-32 h-32 rounded-full bg-glow-accent/20 flex items-center justify-center shadow-md">
                            <span class="text-glow-primary font-bold text-4xl">{{ strtoupper(substr($specialist->user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-2xl font-bold text-glow-primary">{{ $specialist->user->name }}</h1>
                            @if($specialist->average_rating)
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-xl {{ $i <= round($specialist->average_rating) ? 'text-[#8B6343]' : 'text-[#D1C5B8]' }}">&starf;</span>
                                    @endfor
                                    <span class="text-sm text-gray-600 ml-1">{{ $specialist->average_rating }} ({{ $specialist->reviews()->count() }} {{ Str::plural('review', $specialist->reviews()->count()) }})</span>
                                </div>
                            @endif
                        </div>
                        <p class="text-gray-500 mb-2">{{ $specialist->experience_years }} {{ Str::plural('year', $specialist->experience_years) }} of experience</p>
                        @if($specialist->bio)<p class="text-gray-700 leading-relaxed">{{ $specialist->bio }}</p>@endif
                    </div>
                </div>

                @if($specialist->services->isNotEmpty())
                    <div class="border-t pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-glow-primary mb-4">Services</h3>
                        <div class="space-y-3">
                            @foreach($specialist->services as $service)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-glow-bg">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $service->name }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $service->duration_minutes }} min</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-glow-accent font-medium">${{ number_format($service->price, 2) }}</span>
                                        @auth
                                            @if(Auth::user()->isClient())
                                                <a href="{{ route('booking.show', [$salon, $specialist, $service]) }}" class="px-3 py-1 text-xs bg-glow-primary text-white rounded-md hover:bg-glow-accent transition">Book</a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm p-8">
                <h3 class="text-lg font-semibold text-glow-primary mb-4">Reviews</h3>
                @forelse($reviews as $review)
                    <div class="border-b last:border-0 pb-4 mb-4 last:mb-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-900">{{ Str::limit($review->client->name, 1, '.') }} {{ Str::afterLast($review->client->name, ' ') ? Str::limit(Str::afterLast($review->client->name, ' '), 1, '.') : '' }}</span>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-sm {{ $i <= $review->rating ? 'text-[#8B6343]' : 'text-[#D1C5B8]' }}">&starf;</span>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)<p class="text-gray-600 text-sm">{{ $review->comment }}</p>@endif
                        <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->format('F d, Y') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 italic">No reviews yet.</p>
                @endforelse
                @if($reviews->hasPages())
                    <div class="mt-6">{{ $reviews->links() }}</div>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('salons.show', $salon) }}" class="text-glow-accent hover:text-glow-primary font-medium transition">&larr; Back to {{ $salon->name }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
