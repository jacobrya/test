<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Edit Salon') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6"><x-alert type="success">{{ session('success') }}</x-alert></div>
            @endif
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('salon-owner.salon.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <x-input-label for="name" value="Salon Name" />
                        <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name', $salon->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="description" value="Description" />
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent">{{ old('description', $salon->description) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <x-input-label for="address" value="Address" />
                        <x-text-input id="address" name="address" class="mt-1 block w-full" :value="old('address', $salon->address)" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="phone" value="Phone" />
                        <x-text-input id="phone" name="phone" class="mt-1 block w-full" :value="old('phone', $salon->phone)" />
                    </div>
                    <div class="mb-6">
                        <x-input-label for="photo" value="Photo" />
                        @if($salon->photo)
                            <img src="{{ asset('storage/' . $salon->photo) }}" class="w-24 h-24 rounded-lg object-cover mb-2">
                        @endif
                        <input type="file" name="photo" accept="image/jpeg,image/png" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-glow-bg file:text-glow-primary">
                    </div>
                    <x-primary-button>Update Salon</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
