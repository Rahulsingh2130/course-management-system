/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#eef4ff',
                    100: '#dbe7ff',
                    200: '#bcd2ff',
                    400: '#5f8cf5',
                    500: '#3b6ee8',
                    600: '#2455d6',
                    700: '#1d44b0',
                    800: '#1c3a8c',
                    900: '#16295f',
                    950: '#0d1a40',
                },
                accent: {
                    500: '#f59e0b',
                    600: '#d98706',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
