import { defineConfig } from 'vite';
import fs from 'fs';
import tailwindcss from '@tailwindcss/vite';
import symfony from 'vite-plugin-symfony';

export default defineConfig({
    plugins: [
        symfony({
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
        assetsInlineLimit: 0,
        rollupOptions: {
            input: {
                app: './assets/app.js',
                theme: './assets/styles/app.css',
            },
        },
    },
    resolve: {
        alias: {
            '@': '/assets',
        },
    },
    server: {
        https: {
            key: fs.readFileSync('/etc/ssl/dev.local+4-key.pem'),
            cert: fs.readFileSync('/etc/ssl/dev.local+4.pem'),
        },
        cors: true,
    },
    test: {
        globals: true,
        testMatch: ['tests/*.test.js'],
        environment: 'jsdom',
    },
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true,
                silenceDeprecations: ['import'],
            },
        },
    },
});
