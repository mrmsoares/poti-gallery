/**
 * Poti Gallery Widget Handler
 *
 * Main frontend JavaScript for gallery interaction and lightbox.
 *
 * @package Poti\MosaicGallery
 * @version 1.0.0
 */

(function ($) {
  'use strict';

  /**
   * Poti Gallery Handler Class
   */
  class PotiGalleryHandler {
    constructor($scope) {
      this.$scope = $scope;
      this.$container = $scope.find('.poti-gallery-container');

      if (!this.$container.length) {
        return;
      }

      this.settings = {
        lightbox: this.$container.data('lightbox') === 1,
        transition: this.$container.data('transition') || 'hero',
      };

      this.init();
    }

    /**
     * Initialize gallery
     */
    init() {
      this.initLightbox();
      this.initHoverEffects();
      this.initLazyLoading();
      this.handleOverflow();
    }

    /**
     * Initialize Fancybox lightbox
     */
    initLightbox() {
      if (!this.settings.lightbox || typeof Fancybox === 'undefined') {
        return;
      }

      const fancyboxOptions = {
        animated: true,
        hideScrollbar: true,
        compact: false,
        idle: false,
        dragToClose: true,
        keyboard: {
          Escape: 'close',
          Delete: 'close',
          Backspace: 'close',
          ArrowLeft: 'prev',
          ArrowRight: 'next',
        },
        Images: {
          zoom: true,
          protected: true,
        },
        Thumbs: {
          autoStart: true,
        },
        Toolbar: {
          display: {
            left: [],
            middle: [],
            right: ['close'],
          },
        },
        on: {
          init: (fancybox) => {
            this.onLightboxInit(fancybox);
          },
          reveal: (fancybox, slide) => {
            this.onLightboxReveal(fancybox, slide);
          },
        },
      };

      // Initialize Fancybox for this gallery
      Fancybox.bind(this.$scope[0], '[data-fancybox]', fancyboxOptions);
    }

    /**
     * Lightbox initialized callback
     */
    onLightboxInit(fancybox) {
      // Add custom class for styling
      fancybox.$container.addClass('poti-lightbox');
    }

    /**
     * Lightbox slide reveal callback
     */
    onLightboxReveal(fancybox, slide) {
      // Hero transition effect
      if (this.settings.transition === 'hero') {
        slide.$el.style.animation = 'poti-lightbox-hero 0.3s ease-out';
      }
    }

    /**
     * Initialize hover effects
     */
    initHoverEffects() {
      // 3D Tilt effect
      if (this.$container.hasClass('poti-gallery--hover-tilt')) {
        this.init3DTilt();
      }
    }

    /**
     * Initialize 3D tilt effect on hover
     */
    init3DTilt() {
      const items = this.$container.find('.poti-gallery__item');

      items.each((index, item) => {
        const $item = $(item);
        const $link = $item.find('.poti-gallery__link');

        $link.on('mousemove', (e) => {
          const rect = item.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;

          const centerX = rect.width / 2;
          const centerY = rect.height / 2;

          const rotateX = ((y - centerY) / centerY) * 5; // Max 5 degrees
          const rotateY = ((centerX - x) / centerX) * 5;

          $link.css({
            transform: `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`,
          });
        });

        $link.on('mouseleave', () => {
          $link.css({
            transform: '',
          });
        });
      });
    }

    /**
     * Initialize lazy loading for images
     */
    initLazyLoading() {
      if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              const img = entry.target;
              img.classList.add('poti-gallery__image--loaded');
              observer.unobserve(img);
            }
          });
        });

        this.$container.find('.poti-gallery__image').each((index, img) => {
          imageObserver.observe(img);
        });
      }
    }

    /**
     * Handle overflow images
     */
    handleOverflow() {
      const $overflowBadge = this.$container.find('.poti-gallery__overflow-badge');

      if ($overflowBadge.length) {
        // Make overflow badge clickable to show all images in lightbox
        $overflowBadge.on('click', () => {
          const $firstHiddenImage = this.$container
            .find('.poti-gallery__overflow .poti-gallery__link')
            .first();

          if ($firstHiddenImage.length) {
            $firstHiddenImage.trigger('click');
          }
        });
      }
    }
  }

  /**
   * Initialize on Elementor frontend
   */
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
      'frontend/element_ready/poti_mosaic_gallery.default',
      function ($scope) {
        new PotiGalleryHandler($scope);
      }
    );
  });
})(jQuery);
