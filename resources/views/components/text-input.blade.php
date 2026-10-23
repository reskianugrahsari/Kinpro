@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-brand-sky focus:border-brand-teal focus:ring-brand-teal rounded-xl shadow-xs text-brand-navy bg-brand-white']) }}>
