import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'hidden',
        'block',
        'md:block',
        'md:hidden',
        'lg:block',
        'lg:hidden',
        'object-cover',
        'object-contain',
        'object-fill',
        'object-none',
        'object-scale-down',
        'object-center',
        'object-top',
        'object-bottom',
        'object-left',
        'object-right',
        'object-left-top',
        'object-right-top',
        'object-left-bottom',
        'object-right-bottom',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['var(--font-sans)'],
                display: ['var(--font-display)'],
            },
            colors: {
                primary: {
                    50:  'rgb(var(--color-primary-50) / <alpha-value>)',
                    100: 'rgb(var(--color-primary-100) / <alpha-value>)',
                    200: 'rgb(var(--color-primary-200) / <alpha-value>)',
                    300: 'rgb(var(--color-primary-300) / <alpha-value>)',
                    400: 'rgb(var(--color-primary-400) / <alpha-value>)',
                    500: 'rgb(var(--color-primary-500) / <alpha-value>)',
                    600: 'rgb(var(--color-primary-600) / <alpha-value>)',
                    700: 'rgb(var(--color-primary-700) / <alpha-value>)',
                    800: 'rgb(var(--color-primary-800) / <alpha-value>)',
                    900: 'rgb(var(--color-primary-900) / <alpha-value>)',
                    950: 'rgb(var(--color-primary-950) / <alpha-value>)',
                },
                brand: {
                    50:  'rgb(var(--color-primary-50) / <alpha-value>)',
                    100: 'rgb(var(--color-primary-100) / <alpha-value>)',
                    200: 'rgb(var(--color-primary-200) / <alpha-value>)',
                    300: 'rgb(var(--color-primary-300) / <alpha-value>)',
                    400: 'rgb(var(--color-primary-400) / <alpha-value>)',
                    500: 'rgb(var(--color-primary-500) / <alpha-value>)',
                    600: 'rgb(var(--color-primary-600) / <alpha-value>)',
                    700: 'rgb(var(--color-primary-700) / <alpha-value>)',
                    800: 'rgb(var(--color-primary-800) / <alpha-value>)',
                    900: 'rgb(var(--color-primary-900) / <alpha-value>)',
                    950: 'rgb(var(--color-primary-950) / <alpha-value>)',
                },
                secondary: {
                    50:  'rgb(var(--color-secondary-50) / <alpha-value>)',
                    100: 'rgb(var(--color-secondary-100) / <alpha-value>)',
                    200: 'rgb(var(--color-secondary-200) / <alpha-value>)',
                    300: 'rgb(var(--color-secondary-300) / <alpha-value>)',
                    400: 'rgb(var(--color-secondary-400) / <alpha-value>)',
                    500: 'rgb(var(--color-secondary-500) / <alpha-value>)',
                    600: 'rgb(var(--color-secondary-600) / <alpha-value>)',
                    700: 'rgb(var(--color-secondary-700) / <alpha-value>)',
                    800: 'rgb(var(--color-secondary-800) / <alpha-value>)',
                    900: 'rgb(var(--color-secondary-900) / <alpha-value>)',
                    950: 'rgb(var(--color-secondary-950) / <alpha-value>)',
                },
            },
            boxShadow: {
                'card':       '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
                'card-hover': '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
                'dropdown':   '0 10px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
                'header':     '0 1px 3px 0 rgb(0 0 0 / 0.05)',
                'mega':       '0 20px 50px -12px rgb(0 0 0 / 0.15)',
                'nav-icon':   '0 2px 8px -2px rgb(0 0 0 / 0.08)',
            },
            keyframes: {
                'mega-enter': {
                    '0%':   { opacity: '0', transform: 'translateY(-4px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'drawer-in': {
                    '0%':   { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                'drawer-out': {
                    '0%':   { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-100%)' },
                },
                'badge-bounce': {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%':      { transform: 'scale(1.25)' },
                },
                'search-expand': {
                    '0%':   { width: '0%', opacity: '0' },
                    '100%': { width: '100%', opacity: '1' },
                },
                'fade-down': {
                    '0%':   { opacity: '0', transform: 'translateY(-8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'float': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%':      { transform: 'translateY(-10px)' },
                },
                'countdown-pulse': {
                    '0%, 100%': { transform: 'scale(1)', opacity: '1' },
                    '50%':      { transform: 'scale(1.08)', opacity: '0.85' },
                },
            },
            animation: {
                'mega-enter':     'mega-enter 0.2s ease-out',
                'drawer-in':      'drawer-in 0.3s ease-out',
                'drawer-out':     'drawer-out 0.3s ease-in',
                'badge-bounce':   'badge-bounce 0.3s ease-in-out',
                'search-expand':  'search-expand 0.3s ease-out',
                'fade-down':      'fade-down 0.2s ease-out',
                'float':          'float 4s ease-in-out infinite',
                'countdown-pulse': 'countdown-pulse 1s ease-in-out infinite',
            },
            maxHeight: {
                'hero': '650px',
            },
            borderRadius: {
                'card': 'var(--radius-card)',
                'btn': 'var(--radius-btn)',
                'input': 'var(--radius-input)',
            },
        },
    },

    plugins: [forms],
};
