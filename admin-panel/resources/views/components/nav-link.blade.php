@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-uncp-gold text-sm font-medium leading-5 text-uncp-green focus:outline-none focus:border-uncp-gold-web transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-uncp-gray hover:text-uncp-green hover:border-uncp-gold/70 focus:outline-none focus:text-uncp-green focus:border-uncp-gold transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
