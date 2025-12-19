import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        VitePWA({
            registerType: 'autoUpdate',
            devOptions: {
                enabled: true
            },
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg}']
            },
            includeAssets: ['favicon.ico', 'apple-touch-icon.png', 'masked-icon.svg'],
            manifest: {
                name: 'Palmtrack - PT Irfan Sawit Jaya',
                short_name: 'Palmtrack',
                description: 'Aplikasi Manajemen Perkebunan Sawit',
                theme_color: '#ffffff',
                background_color: '#ffffff',
                display: 'standalone',
                orientation: 'portrait',
                scope: '/',
                start_url: '/',
                icons: [
                    {
                        src: 'images/logo.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: 'images/logo.png',
                        sizes: '512x512',
                        type: 'image/png'
                    }
                ]
            },
            manifestFilename: 'manifest.json'
        })
    ],
});
