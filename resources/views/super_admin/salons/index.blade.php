<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Manage Salons') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6"><x-alert type="success">{{ session('success') }}</x-alert></div>
            @endif
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-glow-bg">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Salon</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-glow-primary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($salons as $salon)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $salon->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $salon->owner->name }}<br><span class="text-xs text-gray-400">{{ $salon->owner->email }}</span></td>
                                <td class="px-6 py-4">
                                    @if($salon->is_active)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $salon->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @if(! $salon->is_active)
                                        <form method="POST" action="{{ route('super-admin.salons.approve', $salon) }}" class="inline">@csrf @method('PATCH')
                                            <button class="text-green-600 hover:text-green-800 font-medium">Approve</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('super-admin.salons.deactivate', $salon) }}" class="inline">@csrf @method('PATCH')
                                            <button class="text-red-600 hover:text-red-800 font-medium">Deactivate</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No salons registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $salons->links() }}</div>
        </div>
    </div>
</x-app-layout>
