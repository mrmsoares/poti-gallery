/**
 * Lightbox Configuration
 *
 * Fancybox integration and customization.
 *
 * @package Poti\MosaicGallery
 * @version 1.0.0
 */

export const LightboxConfig = {
  /**
   * Get default Fancybox options
   *
   * @returns {Object} Fancybox options
   */
  getDefaultOptions() {
    return {
      // Animation settings
      animated: true,
      showClass: 'fancybox-zoomIn',
      hideClass: 'fancybox-zoomOut',

      // UI settings
      hideScrollbar: true,
      compact: false,
      idle: false,
      dragToClose: true,

      // Keyboard navigation
      keyboard: {
        Escape: 'close',
        Delete: 'close',
        Backspace: 'close',
        ArrowLeft: 'prev',
        ArrowRight: 'next',
      },

      // Image settings
      Images: {
        zoom: true,
        protected: true,
        initialSize: 'fit',
      },

      // Thumbnails
      Thumbs: {
        autoStart: true,
        type: 'modern',
      },

      // Toolbar
      Toolbar: {
        display: {
          left: ['infobar'],
          middle: [],
          right: ['slideshow', 'thumbs', 'close'],
        },
      },

      // Touch gestures
      Touch: {
        vertical: true,
        momentum: true,
      },
    };
  },

  /**
   * Get hero transition options
   *
   * @returns {Object} Hero transition config
   */
  getHeroTransition() {
    return {
      showClass: 'poti-hero-enter',
      hideClass: 'poti-hero-exit',
      animated: 'hero',
    };
  },

  /**
   * Get mobile-specific options
   *
   * @returns {Object} Mobile options
   */
  getMobileOptions() {
    return {
      Touch: {
        vertical: true,
        momentum: true,
        // Rubber banding effect
        friction: 0.12,
      },
      dragToClose: true,
      compact: true,
    };
  },
};

export default LightboxConfig;
