<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('My Schedule') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Today's Appointments</p>
                    <p class="text-3xl font-bold text-glow-primary mt-1">{{ $todayAppointments->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Clients This Week</p>
                    <p class="text-3xl font-bold text-glow-primary mt-1">{{ $weekClients }}</p>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-glow-primary mb-4">Today's Schedule</h3>
            @forelse($todayAppointments as $appt)
                <div class="bg-white rounded-xl shadow-sm p-5 mb-3 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($appt->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appt->end_time)->format('g:i A') }}</p>
                        <p class="text-sm text-gray-500">{{ $appt->service->name }} &middot; {{ $appt->client->name }}</p>
                    </div>
                    @php
                        $badgeClass = match($appt->status) { 'pending' => 'bg-yellow-100 text-yellow-800', 'confirmed' => 'bg-blue-100 text-blue-800', default => 'bg-gray-100 text-gray-800' };
                    @endphp
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <p class="text-gray-500">No appointments scheduled for today.</p>
                </div>
            @endforelse

            <div class="mt-6">
                <a href="{{ route('specialist.appointments.index') }}" class="text-glow-accent hover:text-glow-primary font-medium transition">View all appointments &rarr;</a>
            </div>
        </div>
    </div>
</x-app-layout>
