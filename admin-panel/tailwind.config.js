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
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                uncp: {
                    'green-logo': '#487A48',
                    'green': '#005E44',
                    'green-light': '#3D7A5F',
                    'gold': '#D2AE6D',
                    'gold-web': '#E3A51E',
                    'gold-dark': '#E4A704',
                    'black': '#000000',
                    'bg': '#F4F6F7',
                    'gray-light': '#C5CBD0',
                    'gray-blue': '#778394',
                    'gray': '#8B9393',
                    'gray-mid': '#4B494A',
                    'gray-dark': '#474747',
                    'blue-pale': '#DCEFFF',
                    'blue-light': '#66B7E8',
                    'blue-bright': '#006BFF',
                    'blue': '#00498B',
                    'purple': '#902B8B',
                    'purple-dark': '#620066',
                    'wine': '#3A0A3C',
                    'yellow': '#FFD200',
                },
            },
        },
    },

    plugins: [forms],
};
