import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  logLevel: 'error', // 👈 Thêm dòng này
  css: {
    preprocessorOptions: {
      scss: {
        // ẩn warnings của dependencies (bootstrap...)
// @ts-ignore
        quietDeps: true,
      },
    },
  },
  plugins: [
    laravel({
      input: ['resources/sass/app.scss', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
});
