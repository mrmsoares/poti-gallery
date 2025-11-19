<?php
/**
 * Image Optimizer - Performance & Modern Formats
 *
 * Handles WebP/AVIF generation, BlurHash, and image optimization.
 *
 * @package Poti\MosaicGallery\Engine
 * @since 1.0.0
 */

namespace Poti\MosaicGallery\Engine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Image_Optimizer
 *
 * Optimizes images for web delivery with modern formats and techniques.
 */
class Image_Optimizer {

	/**
	 * Cache directory name
	 */
	const CACHE_DIR = 'poti-gallery-cache';

	/**
	 * Check if WebP is supported
	 *
	 * @return bool
	 */
	public static function supports_webp() {
		if ( function_exists( 'imagewebp' ) ) {
			return true;
		}

		if ( extension_loaded( 'imagick' ) ) {
			$imagick = new \Imagick();
			return in_array( 'WEBP', $imagick->queryFormats(), true );
		}

		return false;
	}

	/**
	 * Check if AVIF is supported
	 *
	 * @return bool
	 */
	public static function supports_avif() {
		if ( function_exists( 'imageavif' ) ) {
			return true;
		}

		if ( extension_loaded( 'imagick' ) ) {
			$imagick = new \Imagick();
			return in_array( 'AVIF', $imagick->queryFormats(), true );
		}

		return false;
	}

	/**
	 * Generate WebP version of image
	 *
	 * @param string $source_path Source image path
	 * @param int $quality Quality (0-100)
	 * @return string|false WebP path or false on failure
	 */
	public static function generate_webp( $source_path, $quality = 85 ) {
		if ( ! self::supports_webp() || ! file_exists( $source_path ) ) {
			return false;
		}

		$webp_path = self::get_cache_path( $source_path, 'webp' );

		// Return cached if exists
		if ( file_exists( $webp_path ) ) {
			return $webp_path;
		}

		// Create directory if needed
		self::ensure_cache_directory();

		$image_type = exif_imagetype( $source_path );

		switch ( $image_type ) {
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg( $source_path );
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng( $source_path );
				break;
			default:
				return false;
		}

		if ( ! $image ) {
			return false;
		}

		// Convert to WebP
		$result = imagewebp( $image, $webp_path, $quality );
		imagedestroy( $image );

		return $result ? $webp_path : false;
	}

	/**
	 * Generate BlurHash for image
	 *
	 * @param int $attachment_id Attachment ID
	 * @return string|false BlurHash string or false
	 */
	public static function generate_blurhash( $attachment_id ) {
		// Check if already cached
		$cached = get_post_meta( $attachment_id, '_poti_blurhash', true );
		if ( $cached ) {
			return $cached;
		}

		$file_path = get_attached_file( $attachment_id );
		if ( ! $file_path || ! file_exists( $file_path ) ) {
			return false;
		}

		// Simple placeholder implementation
		// In production, you would use a proper BlurHash library
		$blurhash = self::simple_blurhash( $file_path );

		if ( $blurhash ) {
			update_post_meta( $attachment_id, '_poti_blurhash', $blurhash );
		}

		return $blurhash;
	}

	/**
	 * Simple BlurHash generation (placeholder)
	 *
	 * @param string $file_path Image file path
	 * @return string Simplified hash
	 */
	private static function simple_blurhash( $file_path ) {
		// This is a simplified version
		// In production, use a proper BlurHash implementation
		$image_info = getimagesize( $file_path );
		if ( ! $image_info ) {
			return false;
		}

		// Generate a simple color hash based on dominant color
		$dominant_color = self::get_dominant_color( $file_path );
		return 'data:image/svg+xml;base64,' . base64_encode(
			'<svg xmlns="http://www.w3.org/2000/svg" width="' . $image_info[0] . '" height="' . $image_info[1] . '"><rect width="100%" height="100%" fill="' . $dominant_color . '"/></svg>'
		);
	}

	/**
	 * Get dominant color from image
	 *
	 * @param string $file_path Image path
	 * @return string Hex color
	 */
	private static function get_dominant_color( $file_path ) {
		$image_type = exif_imagetype( $file_path );

		switch ( $image_type ) {
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg( $file_path );
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng( $file_path );
				break;
			default:
				return '#cccccc';
		}

		if ( ! $image ) {
			return '#cccccc';
		}

		// Resize to 1x1 to get average color
		$resized = imagecreatetruecolor( 1, 1 );
		imagecopyresampled(
			$resized,
			$image,
			0, 0, 0, 0,
			1, 1,
			imagesx( $image ),
			imagesy( $image )
		);

		$rgb = imagecolorat( $resized, 0, 0 );
		$r = ( $rgb >> 16 ) & 0xFF;
		$g = ( $rgb >> 8 ) & 0xFF;
		$b = $rgb & 0xFF;

		imagedestroy( $image );
		imagedestroy( $resized );

		return sprintf( '#%02x%02x%02x', $r, $g, $b );
	}

	/**
	 * Generate srcset for responsive images
	 *
	 * @param int $attachment_id Attachment ID
	 * @param array $sizes Array of sizes to generate
	 * @return string Srcset attribute value
	 */
	public static function generate_srcset( $attachment_id, $sizes = [] ) {
		if ( empty( $sizes ) ) {
			$sizes = [ 'thumbnail', 'medium', 'large', 'full' ];
		}

		$srcset = [];

		foreach ( $sizes as $size ) {
			$image = wp_get_attachment_image_src( $attachment_id, $size );
			if ( $image ) {
				$srcset[] = $image[0] . ' ' . $image[1] . 'w';
			}
		}

		return implode( ', ', $srcset );
	}

	/**
	 * Get cache directory path
	 *
	 * @return string Cache directory path
	 */
	private static function get_cache_directory() {
		$upload_dir = wp_upload_dir();
		return $upload_dir['basedir'] . '/' . self::CACHE_DIR;
	}

	/**
	 * Ensure cache directory exists
	 *
	 * @return bool True on success
	 */
	private static function ensure_cache_directory() {
		$cache_dir = self::get_cache_directory();

		if ( ! file_exists( $cache_dir ) ) {
			wp_mkdir_p( $cache_dir );

			// Add .htaccess for security
			$htaccess_file = $cache_dir . '/.htaccess';
			if ( ! file_exists( $htaccess_file ) ) {
				file_put_contents(
					$htaccess_file,
					"Options -Indexes\n<Files *.php>\ndeny from all\n</Files>"
				);
			}
		}

		return is_writable( $cache_dir );
	}

	/**
	 * Get cache file path for an image
	 *
	 * @param string $source_path Source image path
	 * @param string $format Target format (webp, avif)
	 * @return string Cache file path
	 */
	private static function get_cache_path( $source_path, $format ) {
		$cache_dir = self::get_cache_directory();
		$filename = basename( $source_path );
		$name_parts = pathinfo( $filename );

		return $cache_dir . '/' . $name_parts['filename'] . '.' . $format;
	}

	/**
	 * Get image performance score
	 *
	 * @param int $attachment_id Attachment ID
	 * @return string green|yellow|red
	 */
	public static function get_performance_score( $attachment_id ) {
		$file_path = get_attached_file( $attachment_id );
		if ( ! $file_path || ! file_exists( $file_path ) ) {
			return 'red';
		}

		$file_size = filesize( $file_path );

		// Score based on file size
		if ( $file_size < 100000 ) { // < 100KB
			return 'green';
		} elseif ( $file_size < 500000 ) { // < 500KB
			return 'yellow';
		} else {
			return 'red';
		}
	}

	/**
	 * Optimize image on upload
	 *
	 * @param int $attachment_id Attachment ID
	 */
	public static function optimize_on_upload( $attachment_id ) {
		// Generate WebP if supported
		if ( self::supports_webp() ) {
			$file_path = get_attached_file( $attachment_id );
			self::generate_webp( $file_path );
		}

		// Generate BlurHash
		self::generate_blurhash( $attachment_id );
	}
}
