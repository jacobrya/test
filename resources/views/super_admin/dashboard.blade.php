<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Admin Dashboard') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                    <p class="text-sm text-gray-500 uppercase">Total Salons</p>
                    <p class="text-2xl font-bold text-glow-primary">{{ $stats['total_salons'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                    <p class="text-sm text-gray-500 uppercase">Active Salons</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active_salons'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                    <p class="text-sm text-gray-500 uppercase">Total Users</p>
                    <p class="text-2xl font-bold text-glow-primary">{{ $stats['total_users'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                    <p class="text-sm text-gray-500 uppercase">Appointments</p>
                    <p class="text-2xl font-bold text-glow-primary">{{ $stats['total_appointments'] }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
