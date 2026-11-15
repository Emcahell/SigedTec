import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // En Docker, vendor/ está en .dockerignore: sin esto, las clases
        // default de Blade Icons (w-5 h-5) se purgan y los SVG salen gigantes.
        './config/blade-icons.php',
    ],

    // Tamaños de iconos usados vía config/componentes (no siempre visibles en Blade).
    safelist: [
        'w-4', 'h-4',
        'w-5', 'h-5',
        'w-6', 'h-6',
        'w-8', 'h-8',
        'shrink-0',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
