<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-glow-primary">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-glow-primary mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-glow-accent">You are logged in as an <span class="font-bold">Administrator</span>.</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold text-glow-primary mb-1">Manage Users</h4>
                    <p class="text-sm text-gray-600">View and manage all registered users.</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold text-glow-primary mb-1">Manage Services</h4>
                    <p class="text-sm text-gray-600">Add, edit, or remove salon services.</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold text-glow-primary mb-1">View Bookings</h4>
                    <p class="text-sm text-gray-600">Overview of all salon bookings.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
