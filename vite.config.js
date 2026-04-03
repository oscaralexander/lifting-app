import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

const port = 5173;
const origin = `${process.env.DDEV_PRIMARY_URL}:${port}`;

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/style.scss', 'resources/js/index.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@css': path.resolve(__dirname, 'resources/css'),
            '@js': path.resolve(__dirname, 'resources/js'),
            '@npm': path.resolve(__dirname, 'node_modules'),
            '@vendor': path.resolve(__dirname, 'vendor'),
        },
    },
    server: {
        // respond to all network requests
        host: '0.0.0.0',
        port: port,
        strictPort: true,
        // Defines the origin of the generated asset URLs during development,
        // this will also be used for the public/hot file (Vite devserver URL)
        origin: origin,
    },
});
