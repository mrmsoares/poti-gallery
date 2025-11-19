/**
 * Shortcode Gallery Frontend Script
 *
 * Handles carousel navigation and lightbox for shortcode galleries.
 *
 * @package Poti\MosaicGallery
 * @version 1.0.0
 */

(function ($) {
  'use strict';

  $(document).ready(function () {
    /**
     * Initialize Lightbox for shortcode galleries
     */
    function initLightbox() {
      $('.poti-gallery[data-lightbox="1"]').each(function () {
        const $gallery = $(this);
        const galleryId = $gallery.attr('id');

        if (typeof Fancybox !== 'undefined') {
          Fancybox.bind(`[data-fancybox="${galleryId}"]`, {
            animated: true,
            showClass: 'fancybox-zoomIn',
            hideClass: 'fancybox-zoomOut',
            Images: {
              zoom: true,
              protected: true,
            },
            Thumbs: {
              autoStart: true,
            },
            Toolbar: {
              display: {
                left: ['infobar'],
                middle: [],
                right: ['slideshow', 'thumbs', 'close'],
              },
            },
          });
        }
      });
    }

    /**
     * Initialize Carousel Navigation
     */
    function initCarousel() {
      $('.poti-gallery--carousel').each(function () {
        const $gallery = $(this);
        const $wrapper = $gallery.find('.poti-gallery__carousel-wrapper');
        const $prevBtn = $gallery.find('.poti-gallery__nav--prev');
        const $nextBtn = $gallery.find('.poti-gallery__nav--next');

        if (!$wrapper.length) {
          return;
        }

        // Previous button
        $prevBtn.on('click', function () {
          const scrollLeft = $wrapper.scrollLeft();
          const itemWidth = $wrapper.find('.poti-gallery__item').outerWidth(true);
          $wrapper.animate(
            {
              scrollLeft: scrollLeft - itemWidth,
            },
            300
          );
        });

        // Next button
        $nextBtn.on('click', function () {
          const scrollLeft = $wrapper.scrollLeft();
          const itemWidth = $wrapper.find('.poti-gallery__item').outerWidth(true);
          $wrapper.animate(
            {
              scrollLeft: scrollLeft + itemWidth,
            },
            300
          );
        });

        // Hide/show buttons based on scroll position
        $wrapper.on('scroll', function () {
          const scrollLeft = $wrapper.scrollLeft();
          const scrollWidth = $wrapper[0].scrollWidth;
          const clientWidth = $wrapper[0].clientWidth;

          // Hide prev button at start
          if (scrollLeft <= 0) {
            $prevBtn.css('opacity', '0.5').prop('disabled', true);
          } else {
            $prevBtn.css('opacity', '1').prop('disabled', false);
          }

          // Hide next button at end
          if (scrollLeft + clientWidth >= scrollWidth - 1) {
            $nextBtn.css('opacity', '0.5').prop('disabled', true);
          } else {
            $nextBtn.css('opacity', '1').prop('disabled', false);
          }
        });

        // Trigger initial state
        $wrapper.trigger('scroll');

        // Touch/swipe support for carousel
        let touchStartX = 0;
        let touchEndX = 0;

        $wrapper.on('touchstart', function (e) {
          touchStartX = e.originalEvent.touches[0].clientX;
        });

        $wrapper.on('touchend', function (e) {
          touchEndX = e.originalEvent.changedTouches[0].clientX;
          handleSwipe();
        });

        function handleSwipe() {
          const swipeThreshold = 50;
          const diff = touchStartX - touchEndX;

          if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
              // Swipe left (next)
              $nextBtn.trigger('click');
            } else {
              // Swipe right (prev)
              $prevBtn.trigger('click');
            }
          }
        }
      });
    }

    /**
     * Initialize Masonry Layout (if using native CSS columns)
     * This ensures images are properly loaded before masonry activates
     */
    function initMasonry() {
      $('.poti-gallery--masonry').each(function () {
        const $gallery = $(this);
        const $images = $gallery.find('img');

        // Wait for all images to load
        let loadedCount = 0;
        const totalImages = $images.length;

        if (totalImages === 0) {
          return;
        }

        $images.on('load', function () {
          loadedCount++;
          if (loadedCount === totalImages) {
            $gallery.addClass('poti-gallery--loaded');
          }
        });

        // Trigger load event for cached images
        $images.each(function () {
          if (this.complete) {
            $(this).trigger('load');
          }
        });
      });
    }

    // Initialize all features
    initLightbox();
    initCarousel();
    initMasonry();
  });
})(jQuery);
