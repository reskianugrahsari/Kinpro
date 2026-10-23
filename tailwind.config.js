import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    navy: 'var(--primary-color)',   // Primary text / Headings / Dark buttons
                    teal: 'var(--secondary-color)', // Secondary buttons / Links / Accents
                    sky: 'var(--accent-color)',     // Borders / Subtle highlights
                    beige: 'var(--bg-light)',       // Light background / Card base
                    white: 'var(--surface-white)',  // Pure white
                },
                // Harmonized semantic tokens
                "outline": "var(--accent-color)",
                "on-tertiary-container": "#60eeb1",
                "error-container": "#ffdad6",
                "on-primary": "var(--surface-white)",
                "surface-container": "var(--bg-light)",
                "on-tertiary-fixed": "#002113",
                "on-primary-fixed": "#111720",
                "secondary-container": "var(--accent-color)",
                "on-secondary-container": "var(--primary-color)",
                "error": "#ba1a1a",
                "on-secondary": "var(--surface-white)",
                "inverse-surface": "var(--primary-color)",
                "on-tertiary": "var(--surface-white)",
                "tertiary": "var(--secondary-color)",
                "primary": "var(--primary-color)",
                "on-secondary-fixed-variant": "#38515E",
                "on-primary-fixed-variant": "var(--primary-color)",
                "surface-container-lowest": "var(--surface-white)",
                "surface-tint": "var(--secondary-color)",
                "on-tertiary-fixed-variant": "#38515E",
                "surface-variant": "var(--accent-color)",
                "background": "var(--bg-light)",
                "surface-container-low": "#FAF6F4",
                "outline-variant": "var(--accent-color)",
                "on-secondary-fixed": "#1D2A31",
                "secondary-fixed": "var(--accent-color)",
                "on-error-container": "#93000a",
                "tertiary-container": "var(--accent-color)",
                "inverse-primary": "var(--accent-color)",
                "surface-container-highest": "#EBE0D7",
                "surface-container-high": "var(--bg-light)",
                "on-surface": "var(--primary-color)",
                "surface-bright": "var(--surface-white)",
                "secondary": "var(--secondary-color)",
                "inverse-on-surface": "var(--bg-light)",
                "primary-fixed": "var(--accent-color)",
                "secondary-fixed-dim": "#ABC5D8",
                "primary-fixed-dim": "var(--accent-color)",
                "surface-dim": "#EBE0D7",
                "on-background": "var(--primary-color)",
                "on-error": "var(--surface-white)",
                "tertiary-fixed": "var(--accent-color)",
                "on-primary-container": "var(--bg-light)",
                "primary-container": "var(--primary-color)",
                "surface": "var(--surface-white)",
                "tertiary-fixed-dim": "var(--secondary-color)",
                "on-surface-variant": "var(--secondary-color)",
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Figtree', ...defaultTheme.fontFamily.sans],
                'body-sm': ['"Plus Jakarta Sans"', 'sans-serif'],
                'code-mono': ['"JetBrains Mono"', 'monospace'],
                'label-badge': ['"Plus Jakarta Sans"', 'sans-serif'],
                'headline-md': ['"Plus Jakarta Sans"', 'sans-serif'],
                'body-lg': ['"Plus Jakarta Sans"', 'sans-serif'],
                'headline-lg': ['"Plus Jakarta Sans"', 'sans-serif'],
                'body-md': ['"Plus Jakarta Sans"', 'sans-serif'],
                'kpi-value': ['"Plus Jakarta Sans"', 'sans-serif'],
                'label-caps': ['"Plus Jakarta Sans"', 'sans-serif'],
                'headline-sm': ['"Plus Jakarta Sans"', 'sans-serif'],
                'display-hero': ['"Plus Jakarta Sans"', 'sans-serif'],
            },
            fontSize: {
                'body-sm': ['11px', { lineHeight: '15px', fontWeight: '400' }],
                'code-mono': ['11px', { lineHeight: '15px', fontWeight: '500' }],
                'label-badge': ['10px', { lineHeight: '13px', fontWeight: '600' }],
                'headline-md': ['18px', { lineHeight: '24px', letterSpacing: '-0.01em', fontWeight: '600' }],
                'body-lg': ['13px', { lineHeight: '19px', fontWeight: '400' }],
                'headline-lg': ['20px', { lineHeight: '26px', letterSpacing: '-0.015em', fontWeight: '700' }],
                'body-md': ['12px', { lineHeight: '16px', fontWeight: '400' }],
                'kpi-value': ['26px', { lineHeight: '32px', letterSpacing: '-0.03em', fontWeight: '800' }],
                'label-caps': ['10px', { lineHeight: '13px', letterSpacing: '0.06em', fontWeight: '700' }],
                'headline-sm': ['14px', { lineHeight: '20px', letterSpacing: '-0.005em', fontWeight: '600' }],
                'display-hero': ['24px', { lineHeight: '30px', letterSpacing: '-0.02em', fontWeight: '700' }],
            },
        },
    },

    plugins: [forms],
};
