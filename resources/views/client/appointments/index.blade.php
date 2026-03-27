<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('My Appointments') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6"><x-alert type="success">{{ session('success') }}</x-alert></div>
            @endif
            @if(session('error'))
                <div class="mb-6"><x-alert type="error">{{ session('error') }}</x-alert></div>
            @endif

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-glow-bg">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Specialist</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-glow-primary uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($appointments as $appt)
                                @php
                                    $badgeClass = match($appt->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $appt->service->name }}<br><span class="text-xs text-gray-500">{{ $appt->salon->name }}</span></td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $appt->specialist->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $appt->appointment_date->format('M d, Y') }}<br>{{ \Carbon\Carbon::parse($appt->start_time)->format('g:i A') }}</td>
                                    <td class="px-6 py-4"><span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span></td>
                                    <td class="px-6 py-4 text-right text-sm space-x-2">
                                        @if(in_array($appt->status, ['pending', 'confirmed']))
                                            <form method="POST" action="{{ route('client.appointments.cancel', $appt) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" onclick="return confirm('Cancel this appointment?')" class="text-red-600 hover:text-red-800 font-medium">Cancel</button>
                                            </form>
                                        @endif
                                        @if($appt->status === 'completed' && !$appt->review)
                                            <a href="{{ route('client.reviews.create', $appt) }}" class="text-glow-accent hover:text-glow-primary font-medium">Review</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No appointments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $appointments->links() }}</div>
        </div>
    </div>
</x-app-layout>
