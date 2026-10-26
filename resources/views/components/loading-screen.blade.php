<div id="global-loading-screen" 
     class="fixed inset-0 z-[99999] flex items-center justify-center backdrop-blur-md bg-white/40 dark:bg-slate-950/40 transition-opacity duration-300 ease-out pointer-events-auto">
    <picture>
        <source srcset="{{ asset('images/loading-screen.webp') }}" type="image/webp">
        <img src="{{ asset('images/loading-screen.gif') }}" 
             alt="Memuat..." 
             class="w-80 sm:w-96 max-w-sm h-auto object-contain select-none pointer-events-none drop-shadow-md" />
    </picture>
</div>

<script>
    (function() {
        const loader = document.getElementById('global-loading-screen');
        if (!loader) return;

        function hideLoader() {
            loader.classList.add('opacity-0', 'pointer-events-none');
            loader.classList.remove('opacity-100', 'pointer-events-auto');
        }

        function showLoader() {
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100', 'pointer-events-auto');
        }

        // Hide on initial load
        if (document.readyState === 'complete') {
            hideLoader();
        } else {
            window.addEventListener('load', hideLoader);
        }

        // Safety fallback: ensure loader hides within 4 seconds even on slow assets
        setTimeout(hideLoader, 4000);

        // BFCache restore (Back/Forward browser buttons)
        window.addEventListener('pageshow', function(event) {
            hideLoader();
        });

        // Trigger loader on link click (internal navigation / pindah menu)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            if (!href) return;

            // Ignore anchor, javascript, protocols, target _blank, download, or data-no-loader
            if (href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
            if (link.target === '_blank' || link.hasAttribute('download') || link.dataset.noLoader !== undefined) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;

            try {
                const targetUrl = new URL(link.href, window.location.origin);
                if (targetUrl.origin === window.location.origin) {
                    if (targetUrl.pathname === window.location.pathname && targetUrl.search === window.location.search && targetUrl.hash) {
                        return;
                    }
                    showLoader();
                }
            } catch (err) {}
        });

        // Trigger loader on form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.target === '_blank' || form.dataset.noLoader !== undefined) return;
            showLoader();
        });

        // Trigger on refresh / beforeunload
        window.addEventListener('beforeunload', function() {
            showLoader();
        });
    })();
</script>
