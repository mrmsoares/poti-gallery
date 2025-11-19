<?php
/**
 * Layout Calculator - The Mosaic Engine
 *
 * Handles the mathematical distribution of images across columns
 * based on configuration settings.
 *
 * @package Poti\MosaicGallery\Engine
 * @since 1.0.0
 */

namespace Poti\MosaicGallery\Engine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Layout_Calculator
 *
 * The brain behind the mosaic distribution algorithm.
 */
class Layout_Calculator {

	/**
	 * Calculate image distribution across columns
	 *
	 * @param array $images Array of image IDs or URLs
	 * @param array $columns_config Array of column configurations
	 * @return array Distributed images by column
	 */
	public static function distribute_images( $images, $columns_config ) {
		if ( empty( $images ) || empty( $columns_config ) ) {
			return [];
		}

		$distribution = [];
		$image_index = 0;
		$total_images = count( $images );

		// Iterate through each column configuration
		foreach ( $columns_config as $col_index => $column ) {
			$max_images = isset( $column['max_images'] ) ? (int) $column['max_images'] : 1;
			$internal_layout = isset( $column['internal_layout'] ) ? $column['internal_layout'] : 'full';

			$distribution[ $col_index ] = [
				'config' => $column,
				'images' => [],
			];

			// Fill column up to max_images
			for ( $i = 0; $i < $max_images && $image_index < $total_images; $i++ ) {
				$distribution[ $col_index ]['images'][] = $images[ $image_index ];
				$image_index++;
			}
		}

		// Handle overflow (ghost images)
		$overflow = [];
		while ( $image_index < $total_images ) {
			$overflow[] = $images[ $image_index ];
			$image_index++;
		}

		return [
			'columns' => $distribution,
			'overflow' => $overflow,
			'total_capacity' => $image_index - count( $overflow ),
			'overflow_count' => count( $overflow ),
		];
	}

	/**
	 * Calculate total grid capacity
	 *
	 * @param array $columns_config Array of column configurations
	 * @return int Total capacity
	 */
	public static function calculate_total_capacity( $columns_config ) {
		$total = 0;

		foreach ( $columns_config as $column ) {
			$max_images = isset( $column['max_images'] ) ? (int) $column['max_images'] : 1;
			$total += $max_images;
		}

		return $total;
	}

	/**
	 * Get CSS classes for internal layout
	 *
	 * @param string $layout_type Layout type identifier
	 * @param int $image_count Number of images in this layout
	 * @return string CSS classes
	 */
	public static function get_layout_classes( $layout_type, $image_count ) {
		$classes = [ 'poti-gallery__column-layout' ];

		switch ( $layout_type ) {
			case 'full':
				$classes[] = 'poti-layout--full';
				break;

			case 'stacked':
				$classes[] = 'poti-layout--stacked';
				break;

			case 'side-by-side':
				$classes[] = 'poti-layout--side-by-side';
				break;

			case 'mixed-2-1':
				$classes[] = 'poti-layout--mixed-2-1';
				break;

			case 'grid-2x2':
				$classes[] = 'poti-layout--grid-2x2';
				break;

			default:
				$classes[] = 'poti-layout--default';
		}

		$classes[] = 'poti-layout--images-' . $image_count;

		return implode( ' ', $classes );
	}

	/**
	 * Determine automatic layout based on image count
	 *
	 * @param int $image_count Number of images
	 * @return string Layout type
	 */
	public static function auto_determine_layout( $image_count ) {
		switch ( $image_count ) {
			case 1:
				return 'full';

			case 2:
				return 'stacked';

			case 3:
				return 'mixed-2-1';

			case 4:
				return 'grid-2x2';

			default:
				return 'stacked';
		}
	}

	/**
	 * Validate column configuration
	 *
	 * @param array $column Column configuration
	 * @return bool True if valid
	 */
	public static function validate_column_config( $column ) {
		if ( ! is_array( $column ) ) {
			return false;
		}

		$max_images = isset( $column['max_images'] ) ? (int) $column['max_images'] : 0;

		if ( $max_images < 1 || $max_images > 4 ) {
			return false;
		}

		return true;
	}

	/**
	 * Calculate responsive breakpoints
	 *
	 * @param int $column_count Total number of columns
	 * @return array Breakpoint configuration
	 */
	public static function get_responsive_config( $column_count ) {
		if ( $column_count <= 3 ) {
			// Simple stacking for small grids
			return [
				'desktop' => $column_count,
				'tablet' => min( 2, $column_count ),
				'mobile' => 1,
			];
		}

		// Intelligent mosaic for larger grids
		return [
			'desktop' => $column_count,
			'tablet' => (int) ceil( $column_count / 2 ),
			'mobile' => 2,
		];
	}

	/**
	 * Generate grid template CSS
	 *
	 * @param int $column_count Number of columns
	 * @param string $gutter Gutter size
	 * @return string CSS for grid-template-columns
	 */
	public static function generate_grid_css( $column_count, $gutter = '20px' ) {
		$columns = str_repeat( '1fr ', $column_count );
		return trim( $columns );
	}

	/**
	 * Calculate image aspect ratio
	 *
	 * @param int $width Image width
	 * @param int $height Image height
	 * @return float Aspect ratio
	 */
	public static function calculate_aspect_ratio( $width, $height ) {
		if ( $height === 0 ) {
			return 1;
		}

		return $width / $height;
	}

	/**
	 * Determine if layout is in mosaic mode
	 *
	 * @param int $column_count Number of columns
	 * @return bool True if mosaic mode
	 */
	public static function is_mosaic_mode( $column_count ) {
		return $column_count >= 4;
	}
}
