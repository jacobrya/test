<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Service Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
            :value="old('name', $service->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" rows="4"
            class="mt-1 block w-full border-gray-300 focus:border-glow-accent focus:ring-glow-accent rounded-md shadow-sm"
        >{{ old('description', $service->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="duration_minutes" :value="__('Duration (minutes)')" />
        <x-text-input id="duration_minutes" name="duration_minutes" type="number"
            class="mt-1 block w-full" min="15" max="480" step="15"
            :value="old('duration_minutes', $service->duration_minutes ?? 60)" required />
        <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="price" :value="__('Price ($)')" />
        <x-text-input id="price" name="price" type="number"
            class="mt-1 block w-full" min="0" step="0.01"
            :value="old('price', $service->price ?? '')" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>
</div>
