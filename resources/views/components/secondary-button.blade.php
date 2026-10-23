<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-white border border-brand-sky rounded-xl font-semibold text-xs text-brand-navy uppercase tracking-widest shadow-xs hover:bg-brand-beige focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
