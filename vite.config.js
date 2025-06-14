import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/scss/style.scss',  // SCSSファイルのエントリーポイント
        'resources/js/app.js',        // JavaScriptファイルのエントリーポイント
				'resources/js/slider.js',
				'resources/js/review-edit-modal.js',
				'resources/js/stripe-update.js',
				'resources/js/delete-modal.js',
				'resources/js/favorite-delete.js',
				'resources/js/favorite.js',
      ],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      '@img': '/public/img',
      '@': path.resolve(__dirname, 'resources'),
      '~slick-carousel': path.resolve(__dirname, 'node_modules/slick-carousel'),
      '~': path.resolve(__dirname, 'node_modules')
    }
  }
});
