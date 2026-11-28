<div id="global-loading-screen"
     class="fixed inset-0 z-[99999] flex flex-col items-center justify-center backdrop-blur-lg bg-white/60 dark:bg-slate-950/65 transition-opacity duration-300 ease-out opacity-0 pointer-events-none"
     style="display: none;"
     role="status"
     aria-live="polite"
     aria-label="Memuat Data">
    <div class="flex flex-col items-center justify-center select-none pointer-events-none">
        <svg class="w-14 h-14 sm:w-16 sm:h-16 animate-spin" style="animation-duration: 0.95s;" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <g transform="rotate(-90 32 32)">
                <circle
                    cx="32"
                    cy="32"
                    r="24"
                    class="stroke-[#0B2A5B] dark:stroke-sky-300"
                    stroke-width="5.5"
                    stroke-linecap="round"
                    stroke-dasharray="58 151"
                    stroke-dashoffset="-93"
                />
                <circle
                    cx="32"
                    cy="32"
                    r="24"
                    stroke="#F5B700"
                    stroke-width="5.5"
                    stroke-linecap="round"
                    stroke-dasharray="73 151"
                    stroke-dashoffset="0"
                />
            </g>
        </svg>
        <p class="mt-4 text-base sm:text-lg font-bold tracking-tight text-[#0B2A5B] dark:text-slate-100">
            Memuat Data...
        </p>
    </div>
</div>

<script>
    (function() {
        const THRESHOLD_MS = 2000;
        const SAFETY_HIDE_MS = 15000;
        const STORAGE_KEY = 'kinpro_loader_start';
        const loader = document.getElementById('global-loading-screen');
        if (!loader) return;

        let delayTimer = null;
        let safetyTimer = null;
        let activeRequests = 0;

        function clearDelayTimer() {
            if (delayTimer) {
                clearTimeout(delayTimer);
                delayTimer = null;
            }
        }

        function clearSafetyTimer() {
            if (safetyTimer) {
                clearTimeout(safetyTimer);
                safetyTimer = null;
            }
        }

        function showNow() {
            clearDelayTimer();
            loader.style.display = 'flex';
            void loader.offsetWidth;
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100', 'pointer-events-auto');

            clearSafetyTimer();
            safetyTimer = setTimeout(hideLoader, SAFETY_HIDE_MS);
        }

        function hideLoader() {
            clearDelayTimer();
            clearSafetyTimer();
            try {
                sessionStorage.removeItem(STORAGE_KEY);
            } catch (e) {}
            loader.classList.add('opacity-0', 'pointer-events-none');
            loader.classList.remove('opacity-100', 'pointer-events-auto');
            setTimeout(function() {
                if (loader.classList.contains('opacity-0')) {
                    loader.style.display = 'none';
                }
            }, 300);
        }

        function scheduleDelayedShow(delayMs, persistStart) {
            if (persistStart) {
                try {
                    sessionStorage.setItem(STORAGE_KEY, String(Date.now()));
                } catch (e) {}
            }
            if (loader.classList.contains('opacity-100')) return;
            clearDelayTimer();
            const wait = Math.max(0, delayMs);
            if (wait === 0) {
                showNow();
            } else {
                delayTimer = setTimeout(function() {
                    delayTimer = null;
                    showNow();
                }, wait);
            }
        }

        // Handle ongoing page load (only reveal if total load exceeds 2000ms)
        if (document.readyState !== 'complete') {
            let startTs = null;
            try {
                const stored = sessionStorage.getItem(STORAGE_KEY);
                if (stored) {
                    const parsed = Number(stored);
                    if (!Number.isNaN(parsed) && parsed > 0) {
                        startTs = parsed;
                    }
                }
            } catch (e) {}

            if (!startTs) {
                startTs = (window.performance && typeof performance.timeOrigin === 'number')
                    ? performance.timeOrigin
                    : Date.now();
            }

            const elapsed = Date.now() - startTs;
            scheduleDelayedShow(THRESHOLD_MS - elapsed, false);
        } else {
            hideLoader();
        }

        // Cancel & hide once initial page load completes
        window.addEventListener('load', function() {
            if (activeRequests === 0) {
                hideLoader();
            }
        });

        // BFCache restore (Back/Forward browser buttons)
        window.addEventListener('pageshow', function() {
            activeRequests = 0;
            hideLoader();
        });

        // Navigation clicks (sidebar links) — show only if navigation exceeds 2000ms
        document.addEventListener('click', function(e) {
            const link = e.target.closest('aside a, [data-sidebar-link]');
            if (!link) return;

            const href = link.getAttribute('href');
            if (!href) return;

            if (href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
            if (link.target === '_blank' || link.hasAttribute('download') || link.dataset.noLoader !== undefined) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;

            try {
                const targetUrl = new URL(link.href, window.location.origin);
                if (targetUrl.origin === window.location.origin) {
                    if (targetUrl.pathname === window.location.pathname && targetUrl.search === window.location.search && targetUrl.hash) {
                        return;
                    }
                    scheduleDelayedShow(THRESHOLD_MS, true);
                }
            } catch (err) {}
        });

        // Login form submission — show only if submission exceeds 2000ms
        document.addEventListener('submit', function(e) {
            if (e.defaultPrevented) return;
            const form = e.target;
            const isLoginForm = form.id === 'loginForm' || (form.action && form.action.includes('/login'));
            if (!isLoginForm) return;
            if (form.target === '_blank' || form.dataset.noLoader !== undefined) return;

            scheduleDelayedShow(THRESHOLD_MS, true);
        });

        // In-page async data loading (fetch & XMLHttpRequest) — show only if request > 2000ms
        function beginAsyncRequest() {
            activeRequests++;
            if (activeRequests === 1) {
                scheduleDelayedShow(THRESHOLD_MS, false);
            }
        }

        function endAsyncRequest() {
            activeRequests = Math.max(0, activeRequests - 1);
            if (activeRequests === 0) {
                hideLoader();
            }
        }

        if (typeof window.fetch === 'function') {
            const origFetch = window.fetch.bind(window);
            window.fetch = function(input, init) {
                const skipLoader = init && init.headers && (
                    (typeof init.headers.get === 'function' && init.headers.get('X-No-Loader')) ||
                    init.headers['X-No-Loader']
                );
                if (skipLoader) {
                    return origFetch(input, init);
                }
                beginAsyncRequest();
                return origFetch(input, init).finally(endAsyncRequest);
            };
        }

        if (typeof window.XMLHttpRequest === 'function') {
            const origOpen = XMLHttpRequest.prototype.open;
            const origSend = XMLHttpRequest.prototype.send;
            XMLHttpRequest.prototype.open = function() {
                this._kinproTrackLoader = true;
                return origOpen.apply(this, arguments);
            };
            XMLHttpRequest.prototype.send = function() {
                if (this._kinproTrackLoader) {
                    beginAsyncRequest();
                    this.addEventListener('loadend', endAsyncRequest, { once: true });
                }
                return origSend.apply(this, arguments);
            };
        }

        window.KinProLoader = {
            show: showNow,
            hide: hideLoader,
            startDelayed: function(ms) {
                scheduleDelayedShow(typeof ms === 'number' ? ms : THRESHOLD_MS, false);
            }
        };
    })();
</script>
