<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-navy border border-transparent rounded-xl font-semibold text-xs text-brand-white uppercase tracking-widest hover:bg-[#202C3B] focus:bg-[#202C3B] active:bg-[#19222E] focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
