<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-glow-primary leading-tight">
                {{ __('Edit Service') }}: {{ $service->name }}
            </h2>
            <a href="{{ route('admin.services.index') }}" class="text-sm text-glow-accent hover:text-glow-primary transition">
                &larr; Back to Services
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('admin.services.update', $service) }}">
                        @csrf
                        @method('PUT')

                        @include('admin.services._form', ['service' => $service])

                        <div class="mt-8 flex items-center justify-end gap-4">
                            <a href="{{ route('admin.services.index') }}"
                               class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition">
                                Cancel
                            </a>
                            <x-primary-button class="!bg-glow-primary hover:!bg-glow-accent">
                                {{ __('Update Service') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
