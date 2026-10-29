import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    sky: '#0284C7',
                    emerald: '#10B981',
                    teal: '#14B8A6',
                    navy: '#0F172A',
                    slate: '#64748B',
                    amber: '#F59E0B',
                    red: '#EF4444',
                    ice: '#E0F2FE',
                    mint: '#ECFDF5',
                    blueteal: '#0891B2',
                    deepteal: '#0F766E',
                },
            },
            backgroundImage: {
                'brand-gradient': 'linear-gradient(135deg, #0284C7 0%, #14B8A6 50%, #10B981 100%)',
                'brand-ocean': 'linear-gradient(135deg, #0284C7 0%, #0891B2 50%, #14B8A6 100%)',
                'soft-mint-sky': 'linear-gradient(135deg, #F0F9FF 0%, #ECFDF5 100%)',
            },
        },
    },
    plugins: [],
};
