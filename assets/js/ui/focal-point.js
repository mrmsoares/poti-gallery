/**
 * Focal Point Selector
 *
 * Manual focal point selection for preventing unwanted image crops.
 *
 * @package Poti\MosaicGallery
 * @version 1.0.0
 */

export class FocalPoint {
  /**
   * Constructor
   *
   * @param {HTMLElement} imageElement - Image element
   */
  constructor(imageElement) {
    this.image = imageElement;
    this.focalX = 50; // Default center X (%)
    this.focalY = 50; // Default center Y (%)
  }

  /**
   * Enable focal point selector
   */
  enable() {
    this.image.addEventListener('click', this.handleClick.bind(this));
    this.image.style.cursor = 'crosshair';
  }

  /**
   * Disable focal point selector
   */
  disable() {
    this.image.removeEventListener('click', this.handleClick.bind(this));
    this.image.style.cursor = '';
  }

  /**
   * Handle click on image
   *
   * @param {MouseEvent} event - Click event
   */
  handleClick(event) {
    const rect = this.image.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    // Convert to percentage
    this.focalX = (x / rect.width) * 100;
    this.focalY = (y / rect.height) * 100;

    // Apply focal point
    this.apply();

    // Show visual indicator
    this.showIndicator(x, y);
  }

  /**
   * Apply focal point to image
   */
  apply() {
    this.image.style.objectPosition = `${this.focalX}% ${this.focalY}%`;
  }

  /**
   * Show visual indicator at focal point
   *
   * @param {number} x - X position in pixels
   * @param {number} y - Y position in pixels
   */
  showIndicator(x, y) {
    // Remove existing indicator
    const existing = this.image.parentElement.querySelector('.focal-point-indicator');
    if (existing) {
      existing.remove();
    }

    // Create indicator
    const indicator = document.createElement('div');
    indicator.className = 'focal-point-indicator';
    indicator.style.cssText = `
      position: absolute;
      left: ${x}px;
      top: ${y}px;
      width: 20px;
      height: 20px;
      border: 2px solid #ff6b35;
      border-radius: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
      animation: pulse 1s ease-out;
    `;

    this.image.parentElement.appendChild(indicator);

    // Remove after animation
    setTimeout(() => {
      indicator.remove();
    }, 1000);
  }

  /**
   * Get focal point coordinates
   *
   * @returns {Object} Focal point { x, y }
   */
  getFocalPoint() {
    return {
      x: this.focalX,
      y: this.focalY,
    };
  }
}

export default FocalPoint;
