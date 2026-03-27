<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Pending Approval') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <div class="mb-6">
                    <svg class="mx-auto h-16 w-16 text-[#8B6343]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-glow-primary mb-3">Your account is pending approval</h3>
                <p class="text-gray-600 mb-8">Your account is pending approval from salon owner. You will be notified once your account has been approved.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-glow-primary text-white rounded-md hover:bg-glow-accent transition font-medium">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
