/** @type {import('tailwindcss').Config} */
export default {
    purge: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
    content: [],
    theme: {
        extend: {
            backgroundImage: {
                'hero': "url('/src/assets/images/hero-background.jpg')",
                'copenhagen': "url('/src/assets/images/cities/copenhagen-bg.jpg')"
            },
            backgroundPosition: {
                'top-96': 'center top -24rem',
            },
            height: {
                '160': '40rem',
                '68': '17rem',
            },
        },
    },
    plugins: [],
};

