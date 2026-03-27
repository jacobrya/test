<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Specialists') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6"><x-alert type="success">{{ session('success') }}</x-alert></div>
            @endif
            @if(session('error'))
                <div class="mb-6"><x-alert type="error">{{ session('error') }}</x-alert></div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <h3 class="font-semibold text-glow-primary mb-3">Invite Specialist</h3>
                <form method="POST" action="{{ route('salon-owner.specialists.invite') }}" class="flex gap-2">
                    @csrf
                    <input type="email" name="email" placeholder="Specialist's email address" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent text-sm">
                    <button type="submit" class="px-4 py-2 bg-glow-primary text-white rounded-md hover:bg-glow-accent transition text-sm">Invite</button>
                </form>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            @if($pendingSpecialists->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
                    <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-100">
                        <h3 class="font-semibold text-yellow-800">Pending Approval ({{ $pendingSpecialists->count() }})</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-glow-bg">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Email</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-glow-primary uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pendingSpecialists as $specialist)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $specialist->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $specialist->user->email }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <form method="POST" action="{{ route('salon-owner.specialists.approve', $specialist) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button class="text-green-600 hover:text-green-800 text-sm font-medium">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('salon-owner.specialists.reject', $specialist) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button onclick="return confirm('Reject this specialist?')" class="text-red-600 hover:text-red-800 text-sm font-medium">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-green-50 border-b border-green-100">
                    <h3 class="font-semibold text-green-800">Active Specialists ({{ $activeSpecialists->count() }})</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-glow-bg">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Experience</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-glow-primary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($activeSpecialists as $specialist)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $specialist->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $specialist->user->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $specialist->experience_years }} {{ Str::plural('year', $specialist->experience_years) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('salon-owner.specialists.remove', $specialist) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Remove this specialist?')" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No active specialists. Approve pending ones or invite new specialists above.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
