@props(['disabled' => false, 'label' => null])

@if($label)
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
@endif

<select {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary']) }}>
    {{ $slot }}
</select>
