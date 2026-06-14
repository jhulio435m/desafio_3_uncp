@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-uncp-gray-dark']) }}>
    {{ $value ?? $slot }}
</label>
