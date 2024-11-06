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
            width: {
                '144': '36rem'
            },
            borderWidth: {
                '5': '5px'
            },
            gridTemplateColumns: {
                'admin': '24rem minmax(0, 1fr)',
                'seats': 'repeat(3, 2.5rem) 1rem repeat(4, 2.5rem) 1rem repeat(3, 2.5rem)',
            },
            gridTemplateRows: {
                'min': 'min-content',
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};

