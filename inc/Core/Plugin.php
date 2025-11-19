<?php
/**
 * Plugin Core Class - The Singleton Maestro
 *
 * Main plugin class that initializes all components.
 *
 * @package Poti\MosaicGallery\Core
 * @since 1.0.0
 */

namespace Poti\MosaicGallery\Core;

use Poti\MosaicGallery\Widgets\Poti_Gallery_Widget;
use Poti\MosaicGallery\Admin\Infra_Settings;
use Poti\MosaicGallery\PostType\Gallery_Post_Type;
use Poti\MosaicGallery\PostType\Gallery_Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Plugin
 *
 * The main plugin singleton that orchestrates all components.
 */
final class Plugin {

	/**
	 * Instance of this class
	 *
	 * @var Plugin|null
	 */
	private static $_instance = null;

	/**
	 * Get class instance
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * Private to prevent direct instantiation.
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize WordPress hooks
	 */
	private function init_hooks() {
		// Initialize Admin Interface (Infrastructure)
		if ( is_admin() ) {
			new Infra_Settings();
		}

		// Initialize Custom Post Type & Shortcode
		new Gallery_Post_Type();
		new Gallery_Shortcode();

		// Register Elementor widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Register Elementor controls (custom controls if needed)
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );

		// Enqueue frontend styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );

		// Enqueue frontend scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

		// Enqueue editor styles
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );

		// Enqueue editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		// Register Elementor widget categories
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_categories' ] );
	}

	/**
	 * Register custom Elementor widget category
	 *
	 * @param \Elementor\Elements_Manager $elements_manager
	 */
	public function register_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'poti-widgets',
			[
				'title' => esc_html__( 'Poti Widgets', 'poti-mosaic-gallery' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	/**
	 * Register widgets with Elementor
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 */
	public function register_widgets( $widgets_manager ) {
		$widgets_manager->register( new Poti_Gallery_Widget() );
	}

	/**
	 * Register custom controls (if needed in future)
	 *
	 * @param \Elementor\Controls_Manager $controls_manager
	 */
	public function register_controls( $controls_manager ) {
		// Placeholder for custom controls
		// Example: $controls_manager->register( new Custom_Control() );
	}

	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_frontend_styles() {
		// Elementor widget styles
		wp_enqueue_style(
			'poti-gallery-frontend',
			POTI_GALLERY_URL . 'assets/css/frontend.css',
			[],
			POTI_GALLERY_VERSION
		);

		// Shortcode gallery styles
		wp_enqueue_style(
			'poti-gallery-shortcode',
			POTI_GALLERY_URL . 'assets/css/shortcode-gallery.css',
			[],
			POTI_GALLERY_VERSION
		);

		// Enqueue Fancybox CSS
		wp_enqueue_style(
			'poti-gallery-fancybox',
			POTI_GALLERY_URL . 'assets/css/fancybox.css',
			[],
			'5.0.0'
		);
	}

	/**
	 * Register frontend scripts
	 */
	public function register_frontend_scripts() {
		// Register Fancybox
		wp_register_script(
			'poti-gallery-fancybox',
			POTI_GALLERY_URL . 'assets/js/fancybox.umd.js',
			[],
			'5.0.0',
			true
		);

		// Register main widget handler
		wp_register_script(
			'poti-gallery-widget-handler',
			POTI_GALLERY_URL . 'assets/js/widget-handler.js',
			[ 'jquery', 'poti-gallery-fancybox' ],
			POTI_GALLERY_VERSION,
			true
		);
	}

	/**
	 * Enqueue frontend scripts
	 */
	public function enqueue_frontend_scripts() {
		// Elementor widget handler
		wp_enqueue_script( 'poti-gallery-widget-handler' );

		// Shortcode gallery handler
		wp_enqueue_script(
			'poti-gallery-shortcode',
			POTI_GALLERY_URL . 'assets/js/shortcode-gallery.js',
			[ 'jquery', 'poti-gallery-fancybox' ],
			POTI_GALLERY_VERSION,
			true
		);

		// Localize script with data
		wp_localize_script(
			'poti-gallery-widget-handler',
			'potiGalleryConfig',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'poti_gallery_nonce' ),
				'i18n' => [
					'loading' => esc_html__( 'Carregando...', 'poti-mosaic-gallery' ),
					'error' => esc_html__( 'Erro ao carregar imagem', 'poti-mosaic-gallery' ),
					'close' => esc_html__( 'Fechar', 'poti-mosaic-gallery' ),
					'next' => esc_html__( 'Próxima', 'poti-mosaic-gallery' ),
					'prev' => esc_html__( 'Anterior', 'poti-mosaic-gallery' ),
				],
			]
		);
	}

	/**
	 * Enqueue editor styles
	 */
	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'poti-gallery-editor',
			POTI_GALLERY_URL . 'assets/css/editor.css',
			[],
			POTI_GALLERY_VERSION
		);
	}

	/**
	 * Enqueue editor scripts
	 */
	public function enqueue_editor_scripts() {
		// Elementor editor
		wp_enqueue_script(
			'poti-gallery-editor',
			POTI_GALLERY_URL . 'assets/js/editor.js',
			[ 'jquery', 'elementor-editor' ],
			POTI_GALLERY_VERSION,
			true
		);

		// Gallery post type admin
		$screen = get_current_screen();
		if ( $screen && $screen->post_type === 'poti_gallery' ) {
			wp_enqueue_media();
			wp_enqueue_script(
				'poti-gallery-admin',
				POTI_GALLERY_URL . 'assets/js/admin-gallery.js',
				[ 'jquery', 'jquery-ui-sortable' ],
				POTI_GALLERY_VERSION,
				true
			);
		}
	}

	/**
	 * Prevent cloning
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}
}
