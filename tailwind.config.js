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

    safelist: [
        'hidden',
        'block',
        'md:block',
        'md:hidden',
        'lg:block',
        'lg:hidden',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50:  '#fef9ec',
                    100: '#fdf0cc',
                    200: '#fbe099',
                    300: '#f8cc55',
                    400: '#f5b822',
                    500: '#e89b2d',
                    600: '#c47a18',
                    700: '#a05c12',
                    800: '#7c450d',
                    900: '#582f08',
                    950: '#331a04',
                },
                brand: {
                    50:  '#fef9ec',
                    100: '#fdf0cc',
                    200: '#fbe099',
                    300: '#f8cc55',
                    400: '#f5b822',
                    500: '#e89b2d',
                    600: '#c47a18',
                    700: '#a05c12',
                    800: '#7c450d',
                    900: '#582f08',
                    950: '#331a04',
                },
                secondary: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1f2937',
                    900: '#111827',
                    950: '#030712',
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
            },
            animation: {
                'mega-enter':     'mega-enter 0.2s ease-out',
                'drawer-in':      'drawer-in 0.3s ease-out',
                'drawer-out':     'drawer-out 0.3s ease-in',
                'badge-bounce':   'badge-bounce 0.3s ease-in-out',
                'search-expand':  'search-expand 0.3s ease-out',
                'fade-down':      'fade-down 0.2s ease-out',
            },
            maxHeight: {
                'hero': '650px',
            },
            borderRadius: {
                'card': '0.75rem',
                'btn': '0.5rem',
                'input': '0.5rem',
            },
        },
    },

    plugins: [forms],
};
