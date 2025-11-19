/**
 * Vite Configuration
 *
 * Build configuration for CSS and JavaScript assets.
 *
 * @package Poti\MosaicGallery
 */

import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        // CSS entry points
        frontend: path.resolve(__dirname, 'assets/css/main.scss'),
        editor: path.resolve(__dirname, 'assets/css/editor/_editor-styles.scss'),

        // JS entry points
        'widget-handler': path.resolve(__dirname, 'assets/js/widget-handler.js'),
        'editor-js': path.resolve(__dirname, 'assets/js/editor.js'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].[hash].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) {
            return 'css/[name][extname]';
          }
          return 'assets/[name][extname]';
        },
      },
    },
    minify: 'esbuild',
    sourcemap: true,
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@import "./assets/css/layers/_base.scss";`,
      },
    },
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets'),
      '@css': path.resolve(__dirname, 'assets/css'),
      '@js': path.resolve(__dirname, 'assets/js'),
    },
  },
});
