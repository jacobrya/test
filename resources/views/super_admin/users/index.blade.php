<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Manage Users') }}</h2>
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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-glow-primary uppercase">Role</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-glow-primary uppercase">Change Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            @php
                                $roleColors = ['client' => 'bg-blue-100 text-blue-800', 'specialist' => 'bg-purple-100 text-purple-800', 'salon_owner' => 'bg-green-100 text-green-800', 'super_admin' => 'bg-red-100 text-red-800'];
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4"><span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">{{ str_replace('_', ' ', ucfirst($user->role)) }}</span></td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('super-admin.users.updateRole', $user) }}" class="inline-flex gap-1">
                                        @csrf @method('PATCH')
                                        <select name="role" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent">
                                            @foreach(['client','specialist','salon_owner','super_admin'] as $r)
                                                <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($r)) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-2 py-1 bg-glow-primary text-white text-xs rounded-md hover:bg-glow-accent transition">Save</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
