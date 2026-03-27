<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">Book Appointment</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-glow-primary mb-2">{{ $service->name }} with {{ $specialist->user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $salon->name }} &middot; {{ $service->duration_minutes }} min &middot; ${{ number_format($service->price, 2) }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="GET" action="{{ route('booking.show', [$salon, $specialist, $service]) }}" class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                    <div class="flex gap-2">
                        <input type="date" name="date" value="{{ $date }}" min="{{ now()->format('Y-m-d') }}" max="{{ now()->addDays(30)->format('Y-m-d') }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent">
                        <button type="submit" class="px-4 py-2 bg-glow-primary text-white rounded-md hover:bg-glow-accent transition text-sm">Show Slots</button>
                    </div>
                </form>

                <h4 class="text-sm font-medium text-gray-700 mb-3">Available Time Slots for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h4>

                @if(empty($availableSlots))
                    <p class="text-gray-500 italic">No available slots for this date.</p>
                @else
                    <form method="POST" action="{{ route('booking.store', [$salon, $specialist, $service]) }}">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 mb-6">
                            @foreach($availableSlots as $slot)
                                <label class="relative">
                                    <input type="radio" name="time" value="{{ $slot }}" class="peer sr-only" {{ old('time') === $slot ? 'checked' : '' }}>
                                    <div class="text-center px-2 py-2 text-sm border rounded-md cursor-pointer transition peer-checked:bg-glow-primary peer-checked:text-white peer-checked:border-glow-primary hover:border-glow-accent">
                                        {{ $slot }}
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <x-input-error :messages="$errors->get('time')" class="mb-4" />

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                            <textarea name="notes" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent" placeholder="Any special requests...">{{ old('notes') }}</textarea>
                        </div>

                        <x-primary-button class="w-full justify-center">Book Appointment</x-primary-button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
