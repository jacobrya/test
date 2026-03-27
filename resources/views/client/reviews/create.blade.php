<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">Leave a Review</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <p class="text-gray-700"><strong>{{ $appointment->service->name }}</strong> with <strong>{{ $appointment->specialist->user->name }}</strong></p>
                <p class="text-sm text-gray-500">{{ $appointment->appointment_date->format('M d, Y') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('client.reviews.store', $appointment) }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex flex-row-reverse justify-end gap-1" id="star-rating">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                       class="hidden peer/star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                <label for="star{{ $i }}"
                                       class="cursor-pointer text-3xl text-gray-300
                                              peer-checked/star{{ $i }}:text-[#8B6343]
                                              hover:text-[#8B6343] transition-colors">&starf;</label>
                            @endfor
                        </div>
                        <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Comment (optional)</label>
                        <textarea name="comment" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent" placeholder="Share your experience...">{{ old('comment') }}</textarea>
                        <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                    </div>
                    <x-primary-button>Submit Review</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
