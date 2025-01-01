/** @type {import('tailwindcss').Config} */
export default {
    purge: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
    content: [],
    theme: {
        extend: {
            backgroundImage: {
                'hero': 'url(\'/src/assets/images/hero-background.jpg\')',
                'copenhagen': 'url(\'/src/assets/images/cities/copenhagen-bg.jpg\')',
                'world': 'url(\'/src/assets/images/world_map.png\')',

                'vertical-dashed': 'linear-gradient(rgb(148 163 184 / var(--tw-bg-opacity)) 66%, rgba(255,255,255,0) 0%)'
            },
            backgroundPosition: {
                'top-96': 'center top -24rem',
            },
            height: {
                '160': '40rem',
                '128': '32rem',
                '100': '25rem',
                '68': '17rem',
            },
            width: {
                '144': '36rem'
            },
            margin: {
                '18': '4.5rem',
                '50': '12.5rem'
            },
            borderWidth: {
                '3': '3px',
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
        import('@tailwindcss/forms'),
    ],
};

