<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-glow-accent">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-glow-primary mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-glow-accent">Ready to book your next beauty appointment?</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold text-glow-primary mb-1">Book Appointment</h4>
                    <p class="text-sm text-gray-600">Browse services and book a new appointment.</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold text-glow-primary mb-1">My Bookings</h4>
                    <p class="text-sm text-gray-600">View and manage your upcoming bookings.</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold text-glow-primary mb-1">Favourites</h4>
                    <p class="text-sm text-gray-600">Your favourite specialists and services.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
