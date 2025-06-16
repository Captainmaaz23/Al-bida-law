import { defineConfig } from 'vite';
import { resolve } from 'path';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';


export default defineConfig({
    root: 'resources',
    plugins: [vue()],
    build: {
        outDir: '../assets',
        emptyOutDir: false,
        rollupOptions: {
            input: {
                oneuiCss: resolve(__dirname, 'resources/css/oneui.css'),
                themeAmethyst: resolve(__dirname, 'resources/css/themes/amethyst.css'),
                themeCity: resolve(__dirname, 'resources/css/themes/city.css'),
                themeFlat: resolve(__dirname, 'resources/css/themes/flat.css'),
                themeModern: resolve(__dirname, 'resources/css/themes/modern.css'),
                themeSmooth: resolve(__dirname, 'resources/css/themes/smooth.css'),
                app: resolve(__dirname, 'resources/js/app.js'),
                oneuiApp: resolve(__dirname, 'resources/js/oneui/app.js'),
                tablesDataTables: resolve(__dirname, 'resources/js/pages/tables_datatables.js'),
            },
            output: {
                entryFileNames: 'js/[name].js',
                chunkFileNames: 'js/[name].js',
                assetFileNames: assetInfo => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/[name][extname]';
                    }
                    return 'assets/[name][extname]';
                },
            },
        },
    },
    server: {
        proxy: {
            '/': 'http://localhost:8000',
        },
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            $: 'jquery',
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
            }
        }
    }
});