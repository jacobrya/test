<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Add Service') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('salon-owner.services.store') }}">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="name" value="Service Name" />
                        <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="description" value="Description" />
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent">{{ old('description') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <x-input-label for="duration_minutes" value="Duration (minutes)" />
                            <x-text-input id="duration_minutes" name="duration_minutes" type="number" min="15" max="480" class="mt-1 block w-full" :value="old('duration_minutes')" required />
                            <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="price" value="Price ($)" />
                            <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                    </div>
                    <x-primary-button>Create Service</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
