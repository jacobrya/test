<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('My Reviews') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($specialist && $specialist->average_rating)
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8 flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-2xl {{ $i <= round($specialist->average_rating) ? 'text-[#8B6343]' : 'text-[#D1C5B8]' }}">&starf;</span>
                        @endfor
                    </div>
                    <div>
                        <span class="text-2xl font-bold text-glow-primary">{{ $specialist->average_rating }}</span>
                        <span class="text-gray-500 ml-1">/ 5</span>
                    </div>
                    <span class="text-gray-500">({{ $specialist->reviews()->count() }} {{ Str::plural('review', $specialist->reviews()->count()) }})</span>
                </div>
            @endif

            @forelse($reviews as $review)
                <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium text-gray-900">{{ Str::limit($review->client->name, 1, '.') }} {{ Str::afterLast($review->client->name, ' ') ? Str::limit(Str::afterLast($review->client->name, ' '), 1, '.') : '' }}</span>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-sm {{ $i <= $review->rating ? 'text-[#8B6343]' : 'text-[#D1C5B8]' }}">&starf;</span>
                            @endfor
                        </div>
                    </div>
                    @if($review->appointment && $review->appointment->service)
                        <p class="text-xs text-gray-500 mb-2">Service: {{ $review->appointment->service->name }}</p>
                    @endif
                    @if($review->comment)
                        <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->format('F d, Y') }}</p>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <p class="text-gray-500">No reviews yet.</p>
                </div>
            @endforelse
            @if($reviews instanceof \Illuminate\Pagination\LengthAwarePaginator && $reviews->hasPages())
                <div class="mt-6">{{ $reviews->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
