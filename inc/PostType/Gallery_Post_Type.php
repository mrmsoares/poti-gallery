<?php
/**
 * Custom Post Type Gallery Manager
 *
 * Manages the gallery custom post type and its metadata.
 *
 * @package Poti\MosaicGallery\PostType
 * @since 1.0.0
 */

namespace Poti\MosaicGallery\PostType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Gallery_Post_Type
 *
 * Handles the gallery custom post type registration and management.
 */
class Gallery_Post_Type {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post_poti_gallery', [ $this, 'save_gallery_meta' ] );
	}

	/**
	 * Register the gallery post type
	 */
	public function register_post_type() {
		$labels = [
			'name' => esc_html__( 'Galerias', 'poti-mosaic-gallery' ),
			'singular_name' => esc_html__( 'Galeria', 'poti-mosaic-gallery' ),
			'menu_name' => esc_html__( 'Poti Galleries', 'poti-mosaic-gallery' ),
			'add_new' => esc_html__( 'Adicionar Nova', 'poti-mosaic-gallery' ),
			'add_new_item' => esc_html__( 'Adicionar Nova Galeria', 'poti-mosaic-gallery' ),
			'edit_item' => esc_html__( 'Editar Galeria', 'poti-mosaic-gallery' ),
			'new_item' => esc_html__( 'Nova Galeria', 'poti-mosaic-gallery' ),
			'view_item' => esc_html__( 'Ver Galeria', 'poti-mosaic-gallery' ),
			'search_items' => esc_html__( 'Buscar Galerias', 'poti-mosaic-gallery' ),
			'not_found' => esc_html__( 'Nenhuma galeria encontrada', 'poti-mosaic-gallery' ),
			'not_found_in_trash' => esc_html__( 'Nenhuma galeria na lixeira', 'poti-mosaic-gallery' ),
		];

		$args = [
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-format-gallery',
			'supports' => [ 'title', 'editor', 'thumbnail' ],
			'show_in_rest' => true,
			'menu_position' => 20,
			'capability_type' => 'post',
			'rewrite' => [ 'slug' => 'galeria' ],
		];

		register_post_type( 'poti_gallery', $args );
	}

	/**
	 * Add meta boxes for gallery settings
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'poti_gallery_images',
			esc_html__( 'Imagens da Galeria', 'poti-mosaic-gallery' ),
			[ $this, 'render_images_meta_box' ],
			'poti_gallery',
			'normal',
			'high'
		);

		add_meta_box(
			'poti_gallery_settings',
			esc_html__( 'Configurações da Galeria', 'poti-mosaic-gallery' ),
			[ $this, 'render_settings_meta_box' ],
			'poti_gallery',
			'side',
			'default'
		);

		add_meta_box(
			'poti_gallery_shortcode',
			esc_html__( 'Shortcode', 'poti-mosaic-gallery' ),
			[ $this, 'render_shortcode_meta_box' ],
			'poti_gallery',
			'side',
			'default'
		);
	}

	/**
	 * Render images meta box
	 *
	 * @param \WP_Post $post Current post object
	 */
	public function render_images_meta_box( $post ) {
		wp_nonce_field( 'poti_gallery_meta_nonce', 'poti_gallery_nonce' );

		$images = get_post_meta( $post->ID, '_poti_gallery_images', true );
		$images = $images ? $images : [];

		?>
		<div class="poti-gallery-images-container">
			<div class="poti-gallery-images-list" id="poti-gallery-images-list">
				<?php if ( ! empty( $images ) ) : ?>
					<?php foreach ( $images as $image_id ) : ?>
						<?php $image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' ); ?>
						<div class="poti-gallery-image-item" data-id="<?php echo esc_attr( $image_id ); ?>">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="">
							<button type="button" class="poti-remove-image">&times;</button>
							<input type="hidden" name="poti_gallery_images[]" value="<?php echo esc_attr( $image_id ); ?>">
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<p>
				<button type="button" class="button button-primary" id="poti-add-images">
					<?php esc_html_e( 'Adicionar Imagens', 'poti-mosaic-gallery' ); ?>
				</button>
				<span class="description">
					<?php esc_html_e( 'Arraste para reordenar as imagens', 'poti-mosaic-gallery' ); ?>
				</span>
			</p>
		</div>

		<style>
			.poti-gallery-images-list {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
				gap: 10px;
				margin-bottom: 15px;
			}
			.poti-gallery-image-item {
				position: relative;
				border: 1px solid #ddd;
				padding: 5px;
				cursor: move;
			}
			.poti-gallery-image-item img {
				width: 100%;
				height: auto;
				display: block;
			}
			.poti-remove-image {
				position: absolute;
				top: -5px;
				right: -5px;
				background: #dc3232;
				color: white;
				border: none;
				border-radius: 50%;
				width: 20px;
				height: 20px;
				cursor: pointer;
				font-size: 16px;
				line-height: 1;
			}
		</style>
		<?php
	}

	/**
	 * Render settings meta box
	 *
	 * @param \WP_Post $post Current post object
	 */
	public function render_settings_meta_box( $post ) {
		$columns = get_post_meta( $post->ID, '_poti_gallery_columns', true );
		$columns = $columns ? $columns : 4;

		$layout = get_post_meta( $post->ID, '_poti_gallery_layout', true );
		$layout = $layout ? $layout : 'grid';

		$lightbox = get_post_meta( $post->ID, '_poti_gallery_lightbox', true );
		$lightbox = $lightbox !== '' ? $lightbox : 'yes';

		?>
		<p>
			<label for="poti_gallery_columns">
				<strong><?php esc_html_e( 'Colunas', 'poti-mosaic-gallery' ); ?></strong>
			</label>
			<select name="poti_gallery_columns" id="poti_gallery_columns" class="widefat">
				<?php for ( $i = 2; $i <= 6; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $columns, $i ); ?>>
						<?php echo esc_html( $i ); ?>
					</option>
				<?php endfor; ?>
			</select>
		</p>

		<p>
			<label for="poti_gallery_layout">
				<strong><?php esc_html_e( 'Layout', 'poti-mosaic-gallery' ); ?></strong>
			</label>
			<select name="poti_gallery_layout" id="poti_gallery_layout" class="widefat">
				<option value="grid" <?php selected( $layout, 'grid' ); ?>>
					<?php esc_html_e( 'Grid', 'poti-mosaic-gallery' ); ?>
				</option>
				<option value="masonry" <?php selected( $layout, 'masonry' ); ?>>
					<?php esc_html_e( 'Masonry', 'poti-mosaic-gallery' ); ?>
				</option>
				<option value="carousel" <?php selected( $layout, 'carousel' ); ?>>
					<?php esc_html_e( 'Carousel', 'poti-mosaic-gallery' ); ?>
				</option>
			</select>
		</p>

		<p>
			<label>
				<input type="checkbox" name="poti_gallery_lightbox" value="yes" <?php checked( $lightbox, 'yes' ); ?>>
				<strong><?php esc_html_e( 'Ativar Lightbox', 'poti-mosaic-gallery' ); ?></strong>
			</label>
		</p>
		<?php
	}

	/**
	 * Render shortcode meta box
	 *
	 * @param \WP_Post $post Current post object
	 */
	public function render_shortcode_meta_box( $post ) {
		if ( $post->ID ) {
			?>
			<p>
				<strong><?php esc_html_e( 'Use este shortcode:', 'poti-mosaic-gallery' ); ?></strong>
			</p>
			<input type="text"
			       class="widefat"
			       readonly
			       value='[poti_gallery id="<?php echo esc_attr( $post->ID ); ?>"]'
			       onclick="this.select();">
			<p class="description">
				<?php esc_html_e( 'Copie e cole este shortcode em qualquer post ou página.', 'poti-mosaic-gallery' ); ?>
			</p>
			<?php
		}
	}

	/**
	 * Save gallery meta data
	 *
	 * @param int $post_id Post ID
	 */
	public function save_gallery_meta( $post_id ) {
		// Verify nonce
		if ( ! isset( $_POST['poti_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['poti_gallery_nonce'], 'poti_gallery_meta_nonce' ) ) {
			return;
		}

		// Check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save images
		if ( isset( $_POST['poti_gallery_images'] ) ) {
			$images = array_map( 'intval', $_POST['poti_gallery_images'] );
			update_post_meta( $post_id, '_poti_gallery_images', $images );
		} else {
			delete_post_meta( $post_id, '_poti_gallery_images' );
		}

		// Save columns
		if ( isset( $_POST['poti_gallery_columns'] ) ) {
			update_post_meta( $post_id, '_poti_gallery_columns', absint( $_POST['poti_gallery_columns'] ) );
		}

		// Save layout
		if ( isset( $_POST['poti_gallery_layout'] ) ) {
			update_post_meta( $post_id, '_poti_gallery_layout', sanitize_text_field( $_POST['poti_gallery_layout'] ) );
		}

		// Save lightbox
		$lightbox = isset( $_POST['poti_gallery_lightbox'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_poti_gallery_lightbox', $lightbox );
	}
}
