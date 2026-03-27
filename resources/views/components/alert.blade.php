@props(['type' => 'success'])

@php
    $classes = match ($type) {
        'success' => 'bg-green-50 border-green-400 text-green-800',
        'error' => 'bg-red-50 border-red-400 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
        default => 'bg-blue-50 border-blue-400 text-blue-800',
    };
@endphp

<div {{ $attributes->merge(['class' => "border-l-4 p-4 rounded-md $classes"]) }}
     x-data="{ show: true }" x-show="show" x-transition>
    <div class="flex justify-between items-center">
        <p class="text-sm font-medium">{{ $slot }}</p>
        <button @click="show = false" class="ml-4 opacity-50 hover:opacity-100">&times;</button>
    </div>
</div>
