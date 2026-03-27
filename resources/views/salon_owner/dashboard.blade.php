<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Salon Dashboard') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(! $salon)
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <h3 class="text-lg font-semibold text-glow-primary mb-2">Welcome!</h3>
                    <p class="text-gray-600 mb-4">Create your salon to get started.</p>
                    <a href="{{ route('salon-owner.salon.create') }}" class="px-6 py-2 bg-glow-primary text-white rounded-md hover:bg-glow-accent transition">Create Salon</a>
                </div>
            @else
                @if(! $salon->is_active)
                    <div class="mb-6"><x-alert type="warning">Your salon is pending approval by the platform admin.</x-alert></div>
                @endif
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                        <p class="text-sm text-gray-500 uppercase">Specialists</p>
                        <p class="text-2xl font-bold text-glow-primary">{{ $stats['specialists'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                        <p class="text-sm text-gray-500 uppercase">Services</p>
                        <p class="text-2xl font-bold text-glow-primary">{{ $stats['services'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                        <p class="text-sm text-gray-500 uppercase">Today</p>
                        <p class="text-2xl font-bold text-glow-primary">{{ $stats['today_appointments'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                        <p class="text-sm text-gray-500 uppercase">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_appointments'] }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-glow-primary mb-2">{{ $salon->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $salon->address }}</p>
                    <a href="{{ route('salon-owner.salon.edit') }}" class="inline-block mt-3 text-glow-accent hover:text-glow-primary text-sm font-medium transition">Edit Salon &rarr;</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
