@props(['disabled' => false, 'value' => null, 'label' => null])

@if($label)
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
@endif

<input {{ $disabled ? 'disabled' : '' }} value="{{ $value }}" {{ $attributes->merge(['class' => 'w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary']) }}>

