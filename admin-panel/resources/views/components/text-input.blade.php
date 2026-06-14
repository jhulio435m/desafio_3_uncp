@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-uncp-green focus:ring-uncp-green rounded-lg shadow-sm']) }}>
