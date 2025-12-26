/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./components/**/*.{js,vue,ts}",
        "./layouts/**/*.vue",
        "./pages/**/*.vue",
        "./plugins/**/*.{js,ts}",
        "./app.vue",
        "./error.vue",
    ],
    theme: {
        extend: {
            colors: {
                'brand-pink': '#FF4D6D',
                'brand-red': '#FF3366',
                'brand-lime': '#C8F560',
                'brand-green': '#B6F09C',
                'brand-yellow': '#FFDD00',
                'brand-blue': '#A0C4FF',
                'brand-purple': '#D0BCFF',
                'brand-dark': '#1A1A1A',
                'brand-gray': '#F5F5F5',
            },
            fontFamily: {
                'heading': ['Recoleta', 'Georgia', 'serif'],
                'body': ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
            },
            boxShadow: {
                'organic': '4px 4px 0px 0px #1A1A1A',
                'organic-lg': '6px 6px 0px 0px #1A1A1A',
                'organic-hover': '2px 2px 0px 0px #1A1A1A',
            },
        },
    },
    plugins: [],
}
