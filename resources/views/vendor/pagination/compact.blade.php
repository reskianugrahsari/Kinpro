@if ($paginator->hasPages())
    <div class="flex items-center gap-1 text-[11px] font-mono">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-6 h-6 rounded border border-brand-sky dark:border-slate-800 text-slate-300 dark:text-slate-600 cursor-not-allowed select-none bg-brand-beige dark:bg-slate-900/60">
                <span class="material-symbols-outlined text-[13px]">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-6 h-6 rounded border border-brand-sky dark:border-slate-700 text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-800 transition">
                <span class="material-symbols-outlined text-[13px]">chevron_left</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-6 h-6 text-slate-400 dark:text-slate-600 text-[10px]">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-brand-navy text-white font-bold text-[11px] shadow-2xs">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-6 h-6 rounded border border-brand-sky dark:border-slate-700 bg-brand-white dark:bg-slate-800 text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-700 text-[11px] transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-6 h-6 rounded border border-brand-sky dark:border-slate-700 text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-800 transition">
                <span class="material-symbols-outlined text-[13px]">chevron_right</span>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-6 h-6 rounded border border-brand-sky dark:border-slate-800 text-slate-300 dark:text-slate-600 cursor-not-allowed select-none bg-brand-beige dark:bg-slate-900/60">
                <span class="material-symbols-outlined text-[13px]">chevron_right</span>
            </span>
        @endif
    </div>
@endif
