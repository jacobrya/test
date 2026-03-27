<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-glow-primary leading-tight">{{ __('Edit Profile') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6"><x-alert type="success">{{ session('success') }}</x-alert></div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('specialist.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <x-input-label for="photo" value="Profile Photo" />
                        <div class="mt-2 flex items-center gap-4">
                            @if($specialist->photo)
                                <img src="{{ asset('storage/' . $specialist->photo) }}" class="w-20 h-20 rounded-full object-cover">
                            @else
                                <div class="w-20 h-20 rounded-full bg-glow-accent/20 flex items-center justify-center">
                                    <span class="text-glow-primary font-bold text-2xl">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <input type="file" name="photo" accept="image/jpeg,image/png" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-glow-bg file:text-glow-primary hover:file:bg-glow-accent/20">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">JPG or PNG, max 2MB</p>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="bio" value="Bio" />
                        <textarea name="bio" id="bio" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-glow-accent focus:ring-glow-accent">{{ old('bio', $specialist->bio) }}</textarea>
                        <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="experience_years" value="Years of Experience" />
                        <x-text-input id="experience_years" name="experience_years" type="number" min="0" max="50" class="mt-1 block w-32" :value="old('experience_years', $specialist->experience_years)" />
                        <x-input-error :messages="$errors->get('experience_years')" class="mt-2" />
                    </div>

                    @if($salonServices->isNotEmpty())
                        <div class="mb-6">
                            <x-input-label value="My Services" />
                            <div class="mt-2 space-y-2">
                                @foreach($salonServices as $service)
                                    <label class="flex items-center gap-3 p-2 rounded-md hover:bg-glow-bg transition cursor-pointer">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" {{ $specialist->services->contains($service->id) ? 'checked' : '' }} class="rounded border-gray-300 text-glow-primary focus:ring-glow-accent">
                                        <span class="text-sm text-gray-900">{{ $service->name }}</span>
                                        <span class="text-xs text-gray-500">${{ number_format($service->price, 2) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <x-primary-button>Save Profile</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
