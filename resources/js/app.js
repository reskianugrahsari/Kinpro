

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Real-time automatic filter handlers across all pages
document.addEventListener('DOMContentLoaded', () => {
    // 1. Auto-submit on change for any select/input inside GET filter forms (if not already handled)
    document.addEventListener('change', (e) => {
        const target = e.target;
        if (!target) return;
        const form = target.closest('form');
        if (!form) return;
        if (form.getAttribute('method')?.toUpperCase() !== 'GET') return;
        
        // Skip pagination or modals
        if (target.dataset.noAutoSubmit !== undefined) return;
        
        // If it's a select or radio/checkbox filter inside a GET form, submit immediately
        if (target.matches('select, input[type="radio"], input[type="checkbox"]')) {
            form.submit();
        }
    });

    // 2. Debounced auto-submit on text search inputs inside GET filter forms
    const searchDebounceTimers = new Map();
    document.addEventListener('input', (e) => {
        const target = e.target;
        if (!target) return;
        if (target.matches('input[type="text"], input[type="search"], input:not([type])')) {
            const form = target.closest('form');
            if (!form) return;
            if (form.getAttribute('method')?.toUpperCase() !== 'GET') return;
            if (target.dataset.noAutoSubmit !== undefined) return;

            // Clear previous timer for this input
            if (searchDebounceTimers.has(target)) {
                clearTimeout(searchDebounceTimers.get(target));
            }

            // Set debounce timer (500ms)
            const timer = setTimeout(() => {
                form.submit();
            }, 500);

            searchDebounceTimers.set(target, timer);
        }
    });
});

