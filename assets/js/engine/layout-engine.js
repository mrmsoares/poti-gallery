/**
 * Layout Engine
 *
 * Client-side layout calculation and distribution logic.
 *
 * @package Poti\MosaicGallery
 * @version 1.0.0
 */

export class LayoutEngine {
  /**
   * Constructor
   */
  constructor() {
    this.columns = [];
    this.images = [];
  }

  /**
   * Calculate image distribution
   *
   * @param {Array} images - Array of image objects
   * @param {Array} columnsConfig - Column configuration
   * @returns {Object} Distribution result
   */
  distributeImages(images, columnsConfig) {
    const distribution = {
      columns: [],
      overflow: [],
      totalCapacity: 0,
      overflowCount: 0,
    };

    let imageIndex = 0;

    // Iterate through each column
    columnsConfig.forEach((columnConfig, colIndex) => {
      const maxImages = parseInt(columnConfig.max_images, 10) || 1;
      const columnImages = [];

      // Fill column up to max capacity
      for (let i = 0; i < maxImages && imageIndex < images.length; i++) {
        columnImages.push(images[imageIndex]);
        imageIndex++;
      }

      distribution.columns.push({
        config: columnConfig,
        images: columnImages,
      });

      distribution.totalCapacity += maxImages;
    });

    // Handle overflow
    while (imageIndex < images.length) {
      distribution.overflow.push(images[imageIndex]);
      imageIndex++;
    }

    distribution.overflowCount = distribution.overflow.length;

    return distribution;
  }

  /**
   * Calculate total grid capacity
   *
   * @param {Array} columnsConfig - Column configuration
   * @returns {number} Total capacity
   */
  calculateTotalCapacity(columnsConfig) {
    return columnsConfig.reduce((total, column) => {
      return total + (parseInt(column.max_images, 10) || 1);
    }, 0);
  }

  /**
   * Get responsive breakpoint configuration
   *
   * @param {number} columnCount - Number of columns
   * @returns {Object} Breakpoint config
   */
  getResponsiveConfig(columnCount) {
    if (columnCount <= 3) {
      return {
        desktop: columnCount,
        tablet: Math.min(2, columnCount),
        mobile: 1,
      };
    }

    return {
      desktop: columnCount,
      tablet: Math.ceil(columnCount / 2),
      mobile: 2,
    };
  }

  /**
   * Check if mosaic mode is active
   *
   * @param {number} columnCount - Number of columns
   * @returns {boolean}
   */
  isMosaicMode(columnCount) {
    return columnCount >= 4;
  }
}

export default LayoutEngine;
