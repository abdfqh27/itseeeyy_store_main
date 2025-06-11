@props(['variant' => 'primary'])

@php
    $classes = match($variant) {
        'primary' => 'bg-primary hover:bg-primary/90 text-white',
        'secondary' => 'bg-secondary hover:bg-secondary/90 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'success' => 'bg-accent hover:bg-accent/90 text-white',
        'outline' => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
        default => 'bg-gray-600 hover:bg-gray-700 text-white',
    };
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => $classes . ' py-2 px-4 rounded-md transition-all inline-flex items-center']) }}>
    {{ $slot }}
</button>
