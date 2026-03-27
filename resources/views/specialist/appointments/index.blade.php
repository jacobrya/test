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

            <form method="GET" class="mb-6 flex gap-2">
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent text-sm">
                    <option value="">All Statuses</option>
                    @foreach(['pending','confirmed','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-glow-primary text-white rounded-md hover:bg-glow-accent transition text-sm">Filter</button>
            </form>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-glow-bg">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-glow-primary uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($appointments as $appt)
                                @php
                                    $badgeClass = match($appt->status) { 'pending' => 'bg-yellow-100 text-yellow-800', 'confirmed' => 'bg-blue-100 text-blue-800', 'completed' => 'bg-green-100 text-green-800', 'cancelled' => 'bg-red-100 text-red-800', default => 'bg-gray-100 text-gray-800' };
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $appt->appointment_date->format('M d, Y') }}<br>{{ \Carbon\Carbon::parse($appt->start_time)->format('g:i A') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $appt->client->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $appt->service->name }}</td>
                                    <td class="px-6 py-4"><span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span></td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        @if($appt->status === 'confirmed' && ($appt->appointment_date->isToday() || $appt->appointment_date->isPast()))
                                            <form method="POST" action="{{ route('specialist.appointments.complete', $appt) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Complete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No appointments found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($appointments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-6">{{ $appointments->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
