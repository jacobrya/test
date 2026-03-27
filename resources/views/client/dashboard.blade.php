<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Dashboard') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-glow-primary mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600">Browse salons and book your next beauty appointment.</p>
                <a href="{{ route('salons.index') }}" class="inline-block mt-4 px-4 py-2 bg-glow-primary text-white rounded-md hover:bg-glow-accent transition text-sm">Browse Salons</a>
            </div>

            <h3 class="text-lg font-semibold text-glow-primary mb-4">Upcoming Appointments</h3>
            @forelse($upcoming as $appointment)
                <div class="bg-white rounded-xl shadow-sm p-5 mb-3 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $appointment->service->name }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->specialist->user->name }} at {{ $appointment->salon->name }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}</p>
                    </div>
                    @php
                        $badgeClass = match($appointment->status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-blue-100 text-blue-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ ucfirst($appointment->status) }}</span>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <p class="text-gray-500">No upcoming appointments. <a href="{{ route('salons.index') }}" class="text-glow-accent hover:underline">Book one now!</a></p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
