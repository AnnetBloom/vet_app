import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                'primary-dark-blue': '#002A48',
                'neutral': {
                    200: '#EBEEEF',
                },
                'mon-yellow': {
                    100: '#FCEED5',
                    200: '#F1D092',
                    300: '#F7DBA7',
                },
                'dark-blue': {
                    100: '#0078CD',
                    200: '#00528C',
                    300: '#003459',
                },
                'green-light': '#34C759',
            },
            borderRadius: {
                'largest': '41.244px',
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};
