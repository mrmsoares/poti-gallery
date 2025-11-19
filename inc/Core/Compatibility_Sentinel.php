<?php
/**
 * Compatibility Sentinel - The Guardian
 *
 * Performs feature detection and version checks to ensure
 * the plugin can run safely in the current environment.
 *
 * @package Poti\MosaicGallery\Core
 * @since 1.0.0
 */

namespace Poti\MosaicGallery\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Compatibility_Sentinel
 *
 * The Guardian that protects the site from incompatibilities.
 */
class Compatibility_Sentinel {

	/**
	 * Minimum Elementor Version Required
	 */
	const MIN_ELEMENTOR_VERSION = '3.18.0';

	/**
	 * Minimum PHP Version Required
	 */
	const MIN_PHP_VERSION = '8.1';

	/**
	 * Minimum WordPress Version Required
	 */
	const MIN_WP_VERSION = '6.4';

	/**
	 * Check all compatibility requirements
	 *
	 * @return bool True if all checks pass, false otherwise
	 */
	public static function check() {
		// 1. Check if Elementor is loaded
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ __CLASS__, 'notice_missing_elementor' ] );
			return false;
		}

		// 2. Check Elementor version
		if ( ! self::check_elementor_version() ) {
			add_action( 'admin_notices', [ __CLASS__, 'notice_elementor_version' ] );
			return false;
		}

		// 3. Check PHP version
		if ( version_compare( PHP_VERSION, self::MIN_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ __CLASS__, 'notice_php_version' ] );
			return false;
		}

		// 4. Check WordPress version
		if ( ! self::check_wp_version() ) {
			add_action( 'admin_notices', [ __CLASS__, 'notice_wp_version' ] );
			return false;
		}

		// 5. Check for required PHP extensions
		if ( ! self::check_php_extensions() ) {
			add_action( 'admin_notices', [ __CLASS__, 'notice_php_extensions' ] );
			return false;
		}

		return true;
	}

	/**
	 * Check Elementor version
	 *
	 * @return bool
	 */
	private static function check_elementor_version() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return false;
		}

		return version_compare( ELEMENTOR_VERSION, self::MIN_ELEMENTOR_VERSION, '>=' );
	}

	/**
	 * Check WordPress version
	 *
	 * @return bool
	 */
	private static function check_wp_version() {
		global $wp_version;
		return version_compare( $wp_version, self::MIN_WP_VERSION, '>=' );
	}

	/**
	 * Check required PHP extensions
	 *
	 * @return bool
	 */
	private static function check_php_extensions() {
		$required_extensions = [ 'gd', 'json' ];
		$missing_extensions = [];

		foreach ( $required_extensions as $extension ) {
			if ( ! extension_loaded( $extension ) ) {
				$missing_extensions[] = $extension;
			}
		}

		// Store missing extensions for notice
		if ( ! empty( $missing_extensions ) ) {
			set_transient( 'poti_gallery_missing_extensions', $missing_extensions, HOUR_IN_SECONDS );
			return false;
		}

		return true;
	}

	/**
	 * Notice: Elementor is missing
	 */
	public static function notice_missing_elementor() {
		$message = sprintf(
			/* translators: 1: Plugin name, 2: Elementor */
			esc_html__( '"%1$s" requer o "%2$s" instalado e ativo.', 'poti-mosaic-gallery' ),
			'<strong>' . esc_html__( 'Poti Mosaic Gallery', 'poti-mosaic-gallery' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'poti-mosaic-gallery' ) . '</strong>'
		);

		printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Notice: Elementor version is too old
	 */
	public static function notice_elementor_version() {
		$message = sprintf(
			/* translators: 1: Plugin name, 2: Elementor, 3: Required version */
			esc_html__( '"%1$s" requer "%2$s" versão %3$s ou superior.', 'poti-mosaic-gallery' ),
			'<strong>' . esc_html__( 'Poti Mosaic Gallery', 'poti-mosaic-gallery' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'poti-mosaic-gallery' ) . '</strong>',
			self::MIN_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Notice: PHP version is too old
	 */
	public static function notice_php_version() {
		$message = sprintf(
			/* translators: 1: Plugin name, 2: PHP, 3: Required version */
			esc_html__( '"%1$s" requer %2$s versão %3$s ou superior.', 'poti-mosaic-gallery' ),
			'<strong>' . esc_html__( 'Poti Mosaic Gallery', 'poti-mosaic-gallery' ) . '</strong>',
			'<strong>PHP</strong>',
			self::MIN_PHP_VERSION
		);

		printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Notice: WordPress version is too old
	 */
	public static function notice_wp_version() {
		$message = sprintf(
			/* translators: 1: Plugin name, 2: WordPress, 3: Required version */
			esc_html__( '"%1$s" requer %2$s versão %3$s ou superior.', 'poti-mosaic-gallery' ),
			'<strong>' . esc_html__( 'Poti Mosaic Gallery', 'poti-mosaic-gallery' ) . '</strong>',
			'<strong>WordPress</strong>',
			self::MIN_WP_VERSION
		);

		printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Notice: Missing PHP extensions
	 */
	public static function notice_php_extensions() {
		$missing = get_transient( 'poti_gallery_missing_extensions' );

		if ( ! $missing ) {
			return;
		}

		$message = sprintf(
			/* translators: 1: Plugin name, 2: List of extensions */
			esc_html__( '"%1$s" requer as seguintes extensões PHP: %2$s', 'poti-mosaic-gallery' ),
			'<strong>' . esc_html__( 'Poti Mosaic Gallery', 'poti-mosaic-gallery' ) . '</strong>',
			'<code>' . implode( '</code>, <code>', $missing ) . '</code>'
		);

		printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( $message ) );
	}
}
