<?php
/**
 * Plugin Name: Poti Mosaic Gallery
 * Description: Widget de galeria premium com mosaico inteligente. 1000% Compatível com Elementor.
 * Version: 1.0.0
 * Author: Agência Poti
 * Author URI: https://agenciapoti.com
 * Text Domain: poti-mosaic-gallery
 * Domain Path: /languages
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Elementor tested up to: 3.25
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package Poti\MosaicGallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Plugin constants
define( 'POTI_GALLERY_VERSION', '1.0.0' );
define( 'POTI_GALLERY_PATH', plugin_dir_path( __FILE__ ) );
define( 'POTI_GALLERY_URL', plugin_dir_url( __FILE__ ) );
define( 'POTI_GALLERY_FILE', __FILE__ );
define( 'POTI_GALLERY_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Load Composer Autoloader
 */
if ( file_exists( POTI_GALLERY_PATH . 'vendor/autoload.php' ) ) {
	require POTI_GALLERY_PATH . 'vendor/autoload.php';
} else {
	/**
	 * Fallback notice if composer autoload is missing
	 */
	add_action( 'admin_notices', function() {
		$class = 'notice notice-error';
		$message = sprintf(
			/* translators: %s: composer install command */
			__( 'Poti Mosaic Gallery: Execute "%s" na pasta do plugin para gerar o autoloader.', 'poti-mosaic-gallery' ),
			'<code>composer install</code>'
		);
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
	});
	return;
}

/**
 * Initialize the plugin
 *
 * Loads the plugin only after all plugins are loaded
 * and checks for Elementor compatibility using the Sentinel.
 */
add_action( 'plugins_loaded', function() {
	// Load text domain for translations
	load_plugin_textdomain(
		'poti-mosaic-gallery',
		false,
		dirname( POTI_GALLERY_BASENAME ) . '/languages'
	);

	// Check compatibility and initialize
	if ( \Poti\MosaicGallery\Core\Compatibility_Sentinel::check() ) {
		\Poti\MosaicGallery\Core\Plugin::instance();
	}
}, 20 );

/**
 * Activation hook
 */
register_activation_hook( __FILE__, function() {
	// Check PHP version
	if ( version_compare( PHP_VERSION, '8.1', '<' ) ) {
		deactivate_plugins( POTI_GALLERY_BASENAME );
		wp_die(
			esc_html__( 'Poti Mosaic Gallery requer PHP 8.1 ou superior.', 'poti-mosaic-gallery' ),
			esc_html__( 'Plugin Activation Error', 'poti-mosaic-gallery' ),
			array( 'back_link' => true )
		);
	}

	// Check if Elementor is installed
	if ( ! did_action( 'elementor/loaded' ) ) {
		deactivate_plugins( POTI_GALLERY_BASENAME );
		wp_die(
			esc_html__( 'Poti Mosaic Gallery requer o Elementor instalado e ativo.', 'poti-mosaic-gallery' ),
			esc_html__( 'Plugin Activation Error', 'poti-mosaic-gallery' ),
			array( 'back_link' => true )
		);
	}
});
