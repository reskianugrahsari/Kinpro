<div id="global-loading-screen" 
     class="fixed inset-0 z-[99999] flex items-center justify-center backdrop-blur-md bg-white/40 dark:bg-slate-950/40 transition-opacity duration-300 ease-out opacity-0 pointer-events-none"
     style="display: none;">
    <picture>
        <source srcset="{{ asset('images/loading-screen.webp') }}" type="image/webp">
        <img src="{{ asset('images/loading-screen.gif') }}" 
             alt="Memuat..." 
             class="w-80 sm:w-96 max-w-sm h-auto object-contain select-none pointer-events-none drop-shadow-md" />
    </picture>
</div>

<script>
    // Synchronously reveal loader if navigating from sidebar or login submit
    try {
        if (sessionStorage.getItem('kinpro_loader') === '1') {
            const _loaderEl = document.getElementById('global-loading-screen');
            if (_loaderEl) {
                _loaderEl.style.display = 'flex';
                _loaderEl.classList.remove('opacity-0', 'pointer-events-none');
                _loaderEl.classList.add('opacity-100', 'pointer-events-auto');
            }
        }
    } catch (e) {}

    (function() {
        const loader = document.getElementById('global-loading-screen');
        if (!loader) return;

        function hideLoader() {
            try {
                sessionStorage.removeItem('kinpro_loader');
            } catch (e) {}
            loader.classList.add('opacity-0', 'pointer-events-none');
            loader.classList.remove('opacity-100', 'pointer-events-auto');
            setTimeout(function() {
                if (loader.classList.contains('opacity-0')) {
                    loader.style.display = 'none';
                }
            }, 300);
        }

        function showLoader() {
            try {
                sessionStorage.setItem('kinpro_loader', '1');
            } catch (e) {}
            loader.style.display = 'flex';
            void loader.offsetWidth;
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100', 'pointer-events-auto');
        }

        // Hide on initial load complete
        if (document.readyState === 'complete') {
            hideLoader();
        } else {
            window.addEventListener('load', hideLoader);
        }

        // Safety fallback: ensure loader hides within 4 seconds even on slow assets
        setTimeout(hideLoader, 4000);

        // BFCache restore (Back/Forward browser buttons)
        window.addEventListener('pageshow', function() {
            hideLoader();
        });

        // Trigger loader ONLY on sidebar links
        document.addEventListener('click', function(e) {
            const link = e.target.closest('aside a, [data-sidebar-link]');
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

        // Trigger loader ONLY on login form submission
        document.addEventListener('submit', function(e) {
            if (e.defaultPrevented) return;
            const form = e.target;
            const isLoginForm = form.id === 'loginForm' || (form.action && form.action.includes('/login'));
            if (!isLoginForm) return;
            if (form.target === '_blank' || form.dataset.noLoader !== undefined) return;

            showLoader();
        });
    })();
</script>
