/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                'navy': '#101756',
                'navy-light': '#1a2070',
                'navy-dark': '#0c1040',
                'red-brand': '#D61A23',
                'red-brand-light': '#e8323a',
                'red-brand-dark': '#b5151d',
            },
            fontFamily: {
                'tajawal': ['Tajawal', 'sans-serif'],
                'roboto': ['Roboto', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
