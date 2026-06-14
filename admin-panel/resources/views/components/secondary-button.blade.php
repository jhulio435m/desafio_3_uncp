<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-uncp-gold/60 rounded-lg font-semibold text-xs text-uncp-green uppercase tracking-widest shadow-sm hover:bg-uncp-bg focus:outline-none focus:ring-2 focus:ring-uncp-gold focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
