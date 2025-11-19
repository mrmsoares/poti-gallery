<?php
/**
 * Gallery Shortcode Handler
 *
 * Handles the [poti_gallery] shortcode rendering.
 *
 * @package Poti\MosaicGallery\PostType
 * @since 1.0.0
 */

namespace Poti\MosaicGallery\PostType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Gallery_Shortcode
 *
 * Renders galleries via shortcodes.
 */
class Gallery_Shortcode {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'poti_gallery', [ $this, 'render_shortcode' ] );
	}

	/**
	 * Render gallery shortcode
	 *
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			[
				'id' => 0,
				'columns' => '',
				'layout' => '',
				'lightbox' => '',
			],
			$atts,
			'poti_gallery'
		);

		$gallery_id = intval( $atts['id'] );

		if ( ! $gallery_id ) {
			return '<p>' . esc_html__( 'ID da galeria não especificado.', 'poti-mosaic-gallery' ) . '</p>';
		}

		$post = get_post( $gallery_id );

		if ( ! $post || $post->post_type !== 'poti_gallery' ) {
			return '<p>' . esc_html__( 'Galeria não encontrada.', 'poti-mosaic-gallery' ) . '</p>';
		}

		// Get gallery images
		$images = get_post_meta( $gallery_id, '_poti_gallery_images', true );

		if ( empty( $images ) ) {
			return '<p>' . esc_html__( 'Esta galeria não possui imagens.', 'poti-mosaic-gallery' ) . '</p>';
		}

		// Get settings (shortcode overrides post meta)
		$columns = $atts['columns'] ? intval( $atts['columns'] ) : get_post_meta( $gallery_id, '_poti_gallery_columns', true );
		$columns = $columns ? $columns : 4;

		$layout = $atts['layout'] ? sanitize_text_field( $atts['layout'] ) : get_post_meta( $gallery_id, '_poti_gallery_layout', true );
		$layout = $layout ? $layout : 'grid';

		$lightbox = $atts['lightbox'] ? sanitize_text_field( $atts['lightbox'] ) : get_post_meta( $gallery_id, '_poti_gallery_lightbox', true );
		$lightbox = $lightbox === 'yes' || $lightbox === 'true';

		// Generate unique ID for this gallery instance
		$instance_id = 'poti-gallery-' . $gallery_id . '-' . wp_rand();

		// Start output buffering
		ob_start();

		// Render gallery based on layout
		switch ( $layout ) {
			case 'masonry':
				$this->render_masonry_layout( $images, $columns, $lightbox, $instance_id );
				break;

			case 'carousel':
				$this->render_carousel_layout( $images, $lightbox, $instance_id );
				break;

			case 'grid':
			default:
				$this->render_grid_layout( $images, $columns, $lightbox, $instance_id );
				break;
		}

		return ob_get_clean();
	}

	/**
	 * Render grid layout
	 *
	 * @param array $images Array of image IDs
	 * @param int $columns Number of columns
	 * @param bool $lightbox Enable lightbox
	 * @param string $instance_id Unique instance ID
	 */
	private function render_grid_layout( $images, $columns, $lightbox, $instance_id ) {
		$columns = max( 2, min( 6, $columns ) );
		?>
		<div class="poti-gallery poti-gallery--grid poti-gallery--columns-<?php echo esc_attr( $columns ); ?>"
		     id="<?php echo esc_attr( $instance_id ); ?>"
		     data-lightbox="<?php echo $lightbox ? '1' : '0'; ?>">

			<?php foreach ( $images as $image_id ) : ?>
				<?php
				$image_url = wp_get_attachment_image_url( $image_id, 'large' );
				$full_url = wp_get_attachment_image_url( $image_id, 'full' );
				$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
				$caption = wp_get_attachment_caption( $image_id );
				?>

				<div class="poti-gallery__item">
					<?php if ( $lightbox ) : ?>
						<a href="<?php echo esc_url( $full_url ); ?>"
						   class="poti-gallery__link"
						   data-fancybox="<?php echo esc_attr( $instance_id ); ?>"
						   <?php if ( $caption ) : ?>
						   data-caption="<?php echo esc_attr( $caption ); ?>"
						   <?php endif; ?>>
							<img src="<?php echo esc_url( $image_url ); ?>"
							     alt="<?php echo esc_attr( $alt ); ?>"
							     class="poti-gallery__image"
							     loading="lazy">
						</a>
					<?php else : ?>
						<img src="<?php echo esc_url( $image_url ); ?>"
						     alt="<?php echo esc_attr( $alt ); ?>"
						     class="poti-gallery__image"
						     loading="lazy">
					<?php endif; ?>

					<?php if ( $caption ) : ?>
						<div class="poti-gallery__caption">
							<?php echo esc_html( $caption ); ?>
						</div>
					<?php endif; ?>
				</div>

			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render masonry layout
	 *
	 * @param array $images Array of image IDs
	 * @param int $columns Number of columns
	 * @param bool $lightbox Enable lightbox
	 * @param string $instance_id Unique instance ID
	 */
	private function render_masonry_layout( $images, $columns, $lightbox, $instance_id ) {
		$columns = max( 2, min( 6, $columns ) );
		?>
		<div class="poti-gallery poti-gallery--masonry poti-gallery--columns-<?php echo esc_attr( $columns ); ?>"
		     id="<?php echo esc_attr( $instance_id ); ?>"
		     data-lightbox="<?php echo $lightbox ? '1' : '0'; ?>">

			<?php foreach ( $images as $image_id ) : ?>
				<?php
				$image_url = wp_get_attachment_image_url( $image_id, 'large' );
				$full_url = wp_get_attachment_image_url( $image_id, 'full' );
				$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
				$caption = wp_get_attachment_caption( $image_id );
				?>

				<div class="poti-gallery__item">
					<?php if ( $lightbox ) : ?>
						<a href="<?php echo esc_url( $full_url ); ?>"
						   class="poti-gallery__link"
						   data-fancybox="<?php echo esc_attr( $instance_id ); ?>"
						   <?php if ( $caption ) : ?>
						   data-caption="<?php echo esc_attr( $caption ); ?>"
						   <?php endif; ?>>
							<img src="<?php echo esc_url( $image_url ); ?>"
							     alt="<?php echo esc_attr( $alt ); ?>"
							     class="poti-gallery__image"
							     loading="lazy">
						</a>
					<?php else : ?>
						<img src="<?php echo esc_url( $image_url ); ?>"
						     alt="<?php echo esc_attr( $alt ); ?>"
						     class="poti-gallery__image"
						     loading="lazy">
					<?php endif; ?>

					<?php if ( $caption ) : ?>
						<div class="poti-gallery__caption">
							<?php echo esc_html( $caption ); ?>
						</div>
					<?php endif; ?>
				</div>

			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render carousel layout
	 *
	 * @param array $images Array of image IDs
	 * @param bool $lightbox Enable lightbox
	 * @param string $instance_id Unique instance ID
	 */
	private function render_carousel_layout( $images, $lightbox, $instance_id ) {
		?>
		<div class="poti-gallery poti-gallery--carousel"
		     id="<?php echo esc_attr( $instance_id ); ?>"
		     data-lightbox="<?php echo $lightbox ? '1' : '0'; ?>">

			<div class="poti-gallery__carousel-wrapper">
				<?php foreach ( $images as $image_id ) : ?>
					<?php
					$image_url = wp_get_attachment_image_url( $image_id, 'large' );
					$full_url = wp_get_attachment_image_url( $image_id, 'full' );
					$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
					$caption = wp_get_attachment_caption( $image_id );
					?>

					<div class="poti-gallery__item">
						<?php if ( $lightbox ) : ?>
							<a href="<?php echo esc_url( $full_url ); ?>"
							   class="poti-gallery__link"
							   data-fancybox="<?php echo esc_attr( $instance_id ); ?>"
							   <?php if ( $caption ) : ?>
							   data-caption="<?php echo esc_attr( $caption ); ?>"
							   <?php endif; ?>>
								<img src="<?php echo esc_url( $image_url ); ?>"
								     alt="<?php echo esc_attr( $alt ); ?>"
								     class="poti-gallery__image"
								     loading="lazy">
							</a>
						<?php else : ?>
							<img src="<?php echo esc_url( $image_url ); ?>"
							     alt="<?php echo esc_attr( $alt ); ?>"
							     class="poti-gallery__image"
							     loading="lazy">
						<?php endif; ?>

						<?php if ( $caption ) : ?>
							<div class="poti-gallery__caption">
								<?php echo esc_html( $caption ); ?>
							</div>
						<?php endif; ?>
					</div>

				<?php endforeach; ?>
			</div>

			<button class="poti-gallery__nav poti-gallery__nav--prev" aria-label="<?php esc_attr_e( 'Anterior', 'poti-mosaic-gallery' ); ?>">
				&lsaquo;
			</button>
			<button class="poti-gallery__nav poti-gallery__nav--next" aria-label="<?php esc_attr_e( 'Próxima', 'poti-mosaic-gallery' ); ?>">
				&rsaquo;
			</button>
		</div>
		<?php
	}
}
