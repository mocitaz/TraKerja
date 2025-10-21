import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        port: 3000,
        host: '0.0.0.0',
    },
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/\.(css)$/.test(assetInfo.name)) {
                        return `assets/styles.[hash].${ext}`;
                    }
                    if (/\.(js)$/.test(assetInfo.name)) {
                        return `assets/scripts.[hash].${ext}`;
                    }
                    return `assets/[name].[hash].${ext}`;
                },
                chunkFileNames: 'assets/chunks.[hash].js',
                entryFileNames: 'assets/entry.[hash].js',
            },
        },
    },
});
