<?php
/**
 * Infrastructure Settings Page
 *
 * Hidden admin page for technical configuration and diagnostics.
 * Accessible only via direct URL or plugin settings link.
 *
 * @package Poti\MosaicGallery\Admin
 * @since 1.0.0
 */

namespace Poti\MosaicGallery\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Infra_Settings
 *
 * Manages the invisible infrastructure settings page.
 */
class Infra_Settings {

	/**
	 * Option group name
	 */
	const OPTION_GROUP = 'poti_gallery_infra';

	/**
	 * Option name for settings
	 */
	const OPTION_NAME = 'poti_gallery_settings';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_post_poti_gallery_clear_cache', [ $this, 'handle_clear_cache' ] );
		add_filter( 'plugin_action_links_' . POTI_GALLERY_BASENAME, [ $this, 'add_plugin_action_links' ] );
	}

	/**
	 * Add settings page to WordPress admin
	 */
	public function add_settings_page() {
		add_options_page(
			esc_html__( 'Poti Gallery Infrastructure', 'poti-mosaic-gallery' ),
			esc_html__( 'Poti Gallery', 'poti-mosaic-gallery' ),
			'manage_options',
			'poti-gallery-infra',
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Add action links to plugin row
	 *
	 * @param array $links Existing links
	 * @return array Modified links
	 */
	public function add_plugin_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=poti-gallery-infra' ),
			esc_html__( 'Settings', 'poti-mosaic-gallery' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting(
			self::OPTION_GROUP,
			self::OPTION_NAME,
			[
				'type' => 'array',
				'sanitize_callback' => [ $this, 'sanitize_settings' ],
				'default' => $this->get_default_settings(),
			]
		);

		// API Keys Section
		add_settings_section(
			'poti_gallery_api_section',
			esc_html__( 'API Keys', 'poti-mosaic-gallery' ),
			[ $this, 'render_api_section' ],
			'poti-gallery-infra'
		);

		// System Status Section
		add_settings_section(
			'poti_gallery_system_section',
			esc_html__( 'System Status', 'poti-mosaic-gallery' ),
			[ $this, 'render_system_section' ],
			'poti-gallery-infra'
		);

		// Cache Section
		add_settings_section(
			'poti_gallery_cache_section',
			esc_html__( 'Cache Management', 'poti-mosaic-gallery' ),
			[ $this, 'render_cache_section' ],
			'poti-gallery-infra'
		);
	}

	/**
	 * Get default settings
	 *
	 * @return array
	 */
	private function get_default_settings() {
		return [
			'google_vision_api_key' => '',
			'unsplash_api_key' => '',
			'enable_webp' => true,
			'enable_avif' => false,
			'enable_blurhash' => true,
		];
	}

	/**
	 * Sanitize settings
	 *
	 * @param array $input Raw input
	 * @return array Sanitized settings
	 */
	public function sanitize_settings( $input ) {
		$sanitized = [];

		if ( isset( $input['google_vision_api_key'] ) ) {
			$sanitized['google_vision_api_key'] = sanitize_text_field( $input['google_vision_api_key'] );
		}

		if ( isset( $input['unsplash_api_key'] ) ) {
			$sanitized['unsplash_api_key'] = sanitize_text_field( $input['unsplash_api_key'] );
		}

		$sanitized['enable_webp'] = isset( $input['enable_webp'] );
		$sanitized['enable_avif'] = isset( $input['enable_avif'] );
		$sanitized['enable_blurhash'] = isset( $input['enable_blurhash'] );

		return $sanitized;
	}

	/**
	 * Render API section description
	 */
	public function render_api_section() {
		echo '<p>' . esc_html__( 'Configure API keys for external services (preparação para v5.0).', 'poti-mosaic-gallery' ) . '</p>';
	}

	/**
	 * Render system section
	 */
	public function render_system_section() {
		echo '<p>' . esc_html__( 'System diagnostics and compatibility checks.', 'poti-mosaic-gallery' ) . '</p>';
		$this->display_system_status();
	}

	/**
	 * Render cache section
	 */
	public function render_cache_section() {
		echo '<p>' . esc_html__( 'Manage plugin cache and temporary files.', 'poti-mosaic-gallery' ) . '</p>';
	}

	/**
	 * Display system status information
	 */
	private function display_system_status() {
		$status = $this->get_system_status();

		echo '<table class="widefat" style="margin-top: 20px;">';
		echo '<thead><tr><th>' . esc_html__( 'Check', 'poti-mosaic-gallery' ) . '</th><th>' . esc_html__( 'Status', 'poti-mosaic-gallery' ) . '</th></tr></thead>';
		echo '<tbody>';

		foreach ( $status as $check => $data ) {
			$status_icon = $data['status'] ? '✅' : '❌';
			echo '<tr>';
			echo '<td>' . esc_html( $data['label'] ) . '</td>';
			echo '<td>' . $status_icon . ' ' . esc_html( $data['message'] ) . '</td>';
			echo '</tr>';
		}

		echo '</tbody></table>';
	}

	/**
	 * Get system status checks
	 *
	 * @return array
	 */
	private function get_system_status() {
		$upload_dir = wp_upload_dir();
		$cache_dir = $upload_dir['basedir'] . '/poti-gallery-cache';

		return [
			'php_version' => [
				'label' => 'PHP Version',
				'status' => version_compare( PHP_VERSION, '8.1', '>=' ),
				'message' => PHP_VERSION,
			],
			'wp_version' => [
				'label' => 'WordPress Version',
				'status' => version_compare( get_bloginfo( 'version' ), '6.4', '>=' ),
				'message' => get_bloginfo( 'version' ),
			],
			'elementor_version' => [
				'label' => 'Elementor Version',
				'status' => defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.18', '>=' ),
				'message' => defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : __( 'Not installed', 'poti-mosaic-gallery' ),
			],
			'gd_library' => [
				'label' => 'GD Library',
				'status' => extension_loaded( 'gd' ),
				'message' => extension_loaded( 'gd' ) ? __( 'Available', 'poti-mosaic-gallery' ) : __( 'Not available', 'poti-mosaic-gallery' ),
			],
			'imagick' => [
				'label' => 'ImageMagick',
				'status' => extension_loaded( 'imagick' ),
				'message' => extension_loaded( 'imagick' ) ? __( 'Available', 'poti-mosaic-gallery' ) : __( 'Not available', 'poti-mosaic-gallery' ),
			],
			'memory_limit' => [
				'label' => 'PHP Memory Limit',
				'status' => true,
				'message' => ini_get( 'memory_limit' ),
			],
			'cache_writable' => [
				'label' => 'Cache Directory Writable',
				'status' => is_writable( $upload_dir['basedir'] ),
				'message' => is_writable( $upload_dir['basedir'] ) ? $cache_dir : __( 'Not writable', 'poti-mosaic-gallery' ),
			],
		];
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Show success message if cache was cleared
		if ( isset( $_GET['cache_cleared'] ) && $_GET['cache_cleared'] === '1' ) {
			add_settings_error(
				'poti_gallery_messages',
				'poti_gallery_message',
				esc_html__( 'Cache cleared successfully!', 'poti-mosaic-gallery' ),
				'updated'
			);
		}

		settings_errors( 'poti_gallery_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<div style="background: #fff; padding: 20px; margin-top: 20px; border-left: 4px solid #2271b1;">
				<h2 style="margin-top: 0;">🎨 <?php esc_html_e( 'Poti Mosaic Gallery', 'poti-mosaic-gallery' ); ?></h2>
				<p><?php esc_html_e( 'Esta página contém configurações técnicas e ferramentas de diagnóstico.', 'poti-mosaic-gallery' ); ?></p>
				<p><strong><?php esc_html_e( 'Versão:', 'poti-mosaic-gallery' ); ?></strong> <?php echo esc_html( POTI_GALLERY_VERSION ); ?></p>
			</div>

			<form action="options.php" method="post">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( 'poti-gallery-infra' );
				?>
			</form>

			<!-- Cache Management -->
			<div style="margin-top: 30px;">
				<h2><?php esc_html_e( 'Cache Management', 'poti-mosaic-gallery' ); ?></h2>
				<p><?php esc_html_e( 'Clear all cached thumbnails, BlurHash data, and temporary files.', 'poti-mosaic-gallery' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="poti_gallery_clear_cache">
					<?php wp_nonce_field( 'poti_gallery_clear_cache', 'poti_gallery_nonce' ); ?>
					<?php submit_button( __( 'Clear Cache', 'poti-mosaic-gallery' ), 'secondary', 'submit', false ); ?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle cache clearing
	 */
	public function handle_clear_cache() {
		// Check user permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access', 'poti-mosaic-gallery' ) );
		}

		// Verify nonce
		if ( ! isset( $_POST['poti_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['poti_gallery_nonce'], 'poti_gallery_clear_cache' ) ) {
			wp_die( esc_html__( 'Invalid nonce', 'poti-mosaic-gallery' ) );
		}

		// Clear cache directory
		$upload_dir = wp_upload_dir();
		$cache_dir = $upload_dir['basedir'] . '/poti-gallery-cache';

		if ( file_exists( $cache_dir ) ) {
			$this->recursive_rmdir( $cache_dir );
		}

		// Delete transients
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_poti_gallery_%' OR option_name LIKE '_transient_timeout_poti_gallery_%'" );

		// Redirect back with success message
		wp_safe_redirect( add_query_arg( 'cache_cleared', '1', wp_get_referer() ) );
		exit;
	}

	/**
	 * Recursively remove directory
	 *
	 * @param string $dir Directory path
	 */
	private function recursive_rmdir( $dir ) {
		if ( is_dir( $dir ) ) {
			$objects = scandir( $dir );
			foreach ( $objects as $object ) {
				if ( $object !== '.' && $object !== '..' ) {
					if ( is_dir( $dir . '/' . $object ) ) {
						$this->recursive_rmdir( $dir . '/' . $object );
					} else {
						unlink( $dir . '/' . $object );
					}
				}
			}
			rmdir( $dir );
		}
	}
}
