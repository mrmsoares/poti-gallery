/**
 * Poti Gallery Editor Script
 *
 * Elementor editor enhancements and UX Premium features.
 *
 * @package Poti\MosaicGallery
 * @version 1.0.0
 */

(function ($) {
  'use strict';

  /**
   * Poti Gallery Editor Handler
   */
  class PotiGalleryEditor {
    constructor() {
      this.init();
    }

    /**
     * Initialize editor features
     */
    init() {
      this.bindEvents();
      this.initColumnAnimations();
      this.initPerformanceIndicators();
    }

    /**
     * Bind editor events
     */
    bindEvents() {
      // Listen to widget updates
      elementor.hooks.addAction(
        'panel/open_editor/widget/poti_mosaic_gallery',
        (panel, model, view) => {
          this.onWidgetEdit(panel, model, view);
        }
      );

      // Listen to column count changes
      elementor.channels.editor.on('change', (view) => {
        if (view.model.get('name') === 'poti_mosaic_gallery') {
          this.onWidgetChange(view);
        }
      });
    }

    /**
     * Widget edit opened
     */
    onWidgetEdit(panel, model, view) {
      console.log('Poti Gallery: Widget opened for editing');

      // Add custom UI enhancements
      this.addColumnHealthBars();
      this.addDeviceSimulator();
    }

    /**
     * Widget settings changed
     */
    onWidgetChange(view) {
      const settings = view.model.get('settings');
      const columnCount = settings.get('column_count')?.size || 4;

      // Animate column reorganization (FLIP animation)
      this.animateColumnReorganization(columnCount);
    }

    /**
     * Initialize FLIP animations for column changes
     */
    initColumnAnimations() {
      // Placeholder for FLIP animation logic
      // This would capture positions before/after and animate the difference
    }

    /**
     * Animate column reorganization
     */
    animateColumnReorganization(newColumnCount) {
      const $preview = $('.elementor-element-edit-mode .poti-gallery-container');

      if (!$preview.length) {
        return;
      }

      // Add animating class
      $preview.find('.poti-gallery__column').addClass('poti-gallery__column--animating');

      // Remove after animation completes
      setTimeout(() => {
        $preview.find('.poti-gallery__column').removeClass('poti-gallery__column--animating');
      }, 300);
    }

    /**
     * Add column health bars to repeater items
     */
    addColumnHealthBars() {
      // This would inject health bar UI into the Elementor panel
      // Showing capacity usage (e.g., 3/4 images used)
      console.log('Poti Gallery: Adding column health bars');
    }

    /**
     * Add device simulator toolbar
     */
    addDeviceSimulator() {
      // This would add a device preview toolbar
      // For testing Desktop/Tablet/Mobile breakpoints instantly
      console.log('Poti Gallery: Adding device simulator');
    }

    /**
     * Initialize performance indicators
     */
    initPerformanceIndicators() {
      // Add performance dots to image thumbnails in the gallery control
      elementor.hooks.addFilter(
        'editor/elements/section/render',
        (html, view) => {
          return this.addPerformanceDotsToImages(html, view);
        }
      );
    }

    /**
     * Add performance indicator dots to images
     */
    addPerformanceDotsToImages(html, view) {
      // This would analyze image file sizes and add colored dots
      // Green: < 100KB, Yellow: < 500KB, Red: > 500KB
      return html;
    }

    /**
     * Show contrast warning for caption colors
     */
    checkContrastRatio(bgColor, textColor) {
      // Calculate contrast ratio and show warning if < 4.5:1
      // WCAG AA compliance check
    }
  }

  /**
   * Initialize when Elementor editor is ready
   */
  $(window).on('elementor:init', function () {
    new PotiGalleryEditor();
  });
})(jQuery);
