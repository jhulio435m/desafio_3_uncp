<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-uncp-gold-web border border-transparent rounded-lg font-semibold text-xs text-uncp-black uppercase tracking-widest hover:bg-uncp-gold-dark focus:outline-none focus:ring-2 focus:ring-uncp-gold focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm']) }}>
    {{ $slot }}
</button>
