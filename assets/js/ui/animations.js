/**
 * UI Animations
 *
 * FLIP animations and smooth transitions for UX Premium features.
 *
 * @package Poti\MosaicGallery
 * @version 1.0.0
 */

export class Animations {
  /**
   * FLIP animation for element repositioning
   *
   * @param {HTMLElement} element - Element to animate
   * @param {Function} callback - Function that changes element position
   */
  static flip(element, callback) {
    // First: Get initial position
    const first = element.getBoundingClientRect();

    // Last: Execute the change
    callback();

    // Get final position
    const last = element.getBoundingClientRect();

    // Invert: Calculate the difference
    const deltaX = first.left - last.left;
    const deltaY = first.top - last.top;

    // Play: Animate from inverted position to final
    element.animate(
      [
        {
          transform: `translate(${deltaX}px, ${deltaY}px)`,
        },
        {
          transform: 'translate(0, 0)',
        },
      ],
      {
        duration: 300,
        easing: 'ease-out',
      }
    );
  }

  /**
   * Fade in animation
   *
   * @param {HTMLElement} element - Element to fade in
   * @param {number} duration - Animation duration in ms
   */
  static fadeIn(element, duration = 300) {
    element.animate(
      [
        { opacity: 0 },
        { opacity: 1 },
      ],
      {
        duration,
        easing: 'ease-out',
        fill: 'forwards',
      }
    );
  }

  /**
   * Slide up animation
   *
   * @param {HTMLElement} element - Element to slide up
   * @param {number} duration - Animation duration in ms
   */
  static slideUp(element, duration = 300) {
    element.animate(
      [
        {
          transform: 'translateY(20px)',
          opacity: 0,
        },
        {
          transform: 'translateY(0)',
          opacity: 1,
        },
      ],
      {
        duration,
        easing: 'ease-out',
        fill: 'forwards',
      }
    );
  }

  /**
   * Elastic bounce effect
   *
   * @param {HTMLElement} element - Element to bounce
   */
  static elasticBounce(element) {
    element.animate(
      [
        { transform: 'scale(1)' },
        { transform: 'scale(1.05)' },
        { transform: 'scale(0.95)' },
        { transform: 'scale(1)' },
      ],
      {
        duration: 400,
        easing: 'ease-in-out',
      }
    );
  }
}

export default Animations;
