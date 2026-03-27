<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Services') }}</h2>
            <a href="{{ route('salon-owner.services.create') }}" class="px-4 py-2 bg-glow-primary text-white text-sm rounded-md hover:bg-glow-accent transition">+ Add Service</a>
        </div>
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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-glow-primary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($services as $service)
                            <tr class="{{ !$service->is_active ? 'opacity-50' : '' }}">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $service->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $service->duration_minutes }} min</td>
                                <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($service->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($service->is_active)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm space-x-2">
                                    <a href="{{ route('salon-owner.services.edit', $service) }}" class="text-glow-accent hover:text-glow-primary font-medium">Edit</a>
                                    @if($service->is_active)
                                        <form method="POST" action="{{ route('salon-owner.services.destroy', $service) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Deactivate?')" class="text-red-600 hover:text-red-800 font-medium">Deactivate</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No services yet. <a href="{{ route('salon-owner.services.create') }}" class="text-glow-accent hover:underline">Create one</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $services->links() }}</div>
        </div>
    </div>
</x-app-layout>
