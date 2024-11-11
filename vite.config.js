import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'public/sass/app.scss',
                'public/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {  
        outDir: 'public/build',
        emptyOutDir: true, 
    },
});
