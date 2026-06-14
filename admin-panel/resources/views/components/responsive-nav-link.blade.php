@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-uncp-gold text-start text-base font-medium text-uncp-green bg-uncp-bg focus:outline-none focus:text-uncp-green focus:bg-uncp-bg focus:border-uncp-gold-web transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-uncp-gray-dark hover:text-uncp-green hover:bg-uncp-bg hover:border-uncp-gold/70 focus:outline-none focus:text-uncp-green focus:bg-uncp-bg focus:border-uncp-gold transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
