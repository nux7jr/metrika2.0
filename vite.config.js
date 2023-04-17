import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        vue({
            template: {
                compilerOptions: {
                    isCustomElement: (tag) => ["md-linedivider"].includes(tag),
                },
            },
        }),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
// 'resources/js/app.js',
// 'resources/css/app.css',
