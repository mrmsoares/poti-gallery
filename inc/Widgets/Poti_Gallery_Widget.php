<?php
/**
 * Poti Gallery Widget - The Heart
 *
 * Main Elementor widget for the Poti Mosaic Gallery.
 * Provides all controls and rendering logic.
 *
 * @package Poti\MosaicGallery\Widgets
 * @since 1.0.0
 */

namespace Poti\MosaicGallery\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Poti\MosaicGallery\Engine\Layout_Calculator;
use Poti\MosaicGallery\Engine\Image_Optimizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Poti_Gallery_Widget
 *
 * The main gallery widget with all UX Premium features.
 */
class Poti_Gallery_Widget extends Widget_Base {

	/**
	 * Get widget name
	 *
	 * @return string Widget name
	 */
	public function get_name() {
		return 'poti_mosaic_gallery';
	}

	/**
	 * Get widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return esc_html__( 'Mosaico Poti', 'poti-mosaic-gallery' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Get widget categories
	 *
	 * @return array Widget categories
	 */
	public function get_categories() {
		return [ 'poti-widgets' ];
	}

	/**
	 * Get widget keywords
	 *
	 * @return array Widget keywords
	 */
	public function get_keywords() {
		return [ 'gallery', 'images', 'mosaic', 'grid', 'photos', 'poti' ];
	}

	/**
	 * Register widget controls
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
		$this->register_advanced_controls();
	}

	/**
	 * Register content tab controls
	 */
	private function register_content_controls() {
		// Section: Grid Structure
		$this->start_controls_section(
			'section_grid_structure',
			[
				'label' => esc_html__( 'Estrutura do Grid', 'poti-mosaic-gallery' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'column_count',
			[
				'label' => esc_html__( 'Número de Colunas', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 4,
				],
				'description' => esc_html__( '1-3: Grid Simplificado | 4-10: Mosaico Inteligente', 'poti-mosaic-gallery' ),
			]
		);

		$this->add_control(
			'grid_mode_info',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div style="padding: 10px; background: #e8f5e9; border-left: 3px solid #4caf50; margin: 10px 0;">
					<strong>🎨 Modo Mosaico Ativo</strong><br>
					Configure cada coluna individualmente abaixo.
				</div>',
				'condition' => [
					'column_count[size]' => range( 4, 10 ),
				],
			]
		);

		$this->add_control(
			'grid_simple_info',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div style="padding: 10px; background: #fff3e0; border-left: 3px solid #ff9800; margin: 10px 0;">
					<strong>📊 Grid Simplificado</strong><br>
					Stack vertical simples para 1-3 colunas.
				</div>',
				'condition' => [
					'column_count[size]' => range( 1, 3 ),
				],
			]
		);

		$this->end_controls_section();

		// Section: Column Configuration (Dynamic Repeater)
		$this->start_controls_section(
			'section_columns_config',
			[
				'label' => esc_html__( 'Configuração das Colunas', 'poti-mosaic-gallery' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'max_images',
			[
				'label' => esc_html__( 'Máximo de Imagens', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SELECT,
				'default' => '2',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
			]
		);

		$repeater->add_control(
			'internal_layout',
			[
				'label' => esc_html__( 'Layout Interno', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'stacked',
				'options' => [
					'full' => esc_html__( 'Full (1 img)', 'poti-mosaic-gallery' ),
					'stacked' => esc_html__( 'Empilhadas', 'poti-mosaic-gallery' ),
					'side-by-side' => esc_html__( 'Lado a Lado', 'poti-mosaic-gallery' ),
					'mixed-2-1' => esc_html__( 'Misto 2+1', 'poti-mosaic-gallery' ),
					'grid-2x2' => esc_html__( 'Grid 2x2', 'poti-mosaic-gallery' ),
				],
			]
		);

		$this->add_control(
			'columns_config',
			[
				'label' => esc_html__( 'Colunas', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'max_images' => '2', 'internal_layout' => 'stacked' ],
					[ 'max_images' => '3', 'internal_layout' => 'mixed-2-1' ],
					[ 'max_images' => '2', 'internal_layout' => 'side-by-side' ],
					[ 'max_images' => '1', 'internal_layout' => 'full' ],
				],
				'title_field' => esc_html__( 'Coluna {{{ max_images }}} imgs', 'poti-mosaic-gallery' ),
			]
		);

		$this->end_controls_section();

		// Section: Media (Image Selection)
		$this->start_controls_section(
			'section_media',
			[
				'label' => esc_html__( 'Mídia', 'poti-mosaic-gallery' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'gallery_images',
			[
				'label' => esc_html__( 'Adicionar Imagens', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [],
				'show_label' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => esc_html__( 'Tamanho da Imagem', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => [
					'thumbnail' => esc_html__( 'Thumbnail', 'poti-mosaic-gallery' ),
					'medium' => esc_html__( 'Medium', 'poti-mosaic-gallery' ),
					'medium_large' => esc_html__( 'Medium Large', 'poti-mosaic-gallery' ),
					'large' => esc_html__( 'Large', 'poti-mosaic-gallery' ),
					'full' => esc_html__( 'Full', 'poti-mosaic-gallery' ),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style tab controls
	 */
	private function register_style_controls() {
		// Section: Layout & Spacing
		$this->start_controls_section(
			'section_style_layout',
			[
				'label' => esc_html__( 'Layout & Espaçamento', 'poti-mosaic-gallery' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'gutter',
			[
				'label' => esc_html__( 'Espaçamento (Gutter)', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .poti-gallery-container' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .poti-gallery__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .poti-gallery__image',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_shadow',
				'selector' => '{{WRAPPER}} .poti-gallery__image',
			]
		);

		$this->end_controls_section();

		// Section: Captions & Overlay
		$this->start_controls_section(
			'section_style_captions',
			[
				'label' => esc_html__( 'Legendas & Overlay', 'poti-mosaic-gallery' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_caption',
			[
				'label' => esc_html__( 'Exibir Legendas', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Sim', 'poti-mosaic-gallery' ),
				'label_off' => esc_html__( 'Não', 'poti-mosaic-gallery' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'caption_position',
			[
				'label' => esc_html__( 'Posição da Legenda', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'top' => esc_html__( 'Topo', 'poti-mosaic-gallery' ),
					'bottom' => esc_html__( 'Embaixo', 'poti-mosaic-gallery' ),
					'overlay' => esc_html__( 'Overlay', 'poti-mosaic-gallery' ),
				],
				'condition' => [
					'show_caption' => 'yes',
				],
			]
		);

		$this->add_control(
			'glassmorphism',
			[
				'label' => esc_html__( 'Efeito Glassmorphism', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'poti-mosaic-gallery' ),
				'label_off' => esc_html__( 'Off', 'poti-mosaic-gallery' ),
				'default' => '',
				'condition' => [
					'show_caption' => 'yes',
					'caption_position' => 'overlay',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => esc_html__( 'Cor do Overlay', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} .poti-gallery__caption-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_caption' => 'yes',
					'caption_position' => 'overlay',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'selector' => '{{WRAPPER}} .poti-gallery__caption',
				'condition' => [
					'show_caption' => 'yes',
				],
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label' => esc_html__( 'Cor do Texto', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .poti-gallery__caption' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_caption' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Section: Hover Effects
		$this->start_controls_section(
			'section_style_hover',
			[
				'label' => esc_html__( 'Efeitos de Hover', 'poti-mosaic-gallery' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => esc_html__( 'Efeito de Hover', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'zoom',
				'options' => [
					'none' => esc_html__( 'Nenhum', 'poti-mosaic-gallery' ),
					'zoom' => esc_html__( 'Zoom', 'poti-mosaic-gallery' ),
					'tilt' => esc_html__( '3D Tilt', 'poti-mosaic-gallery' ),
					'fade' => esc_html__( 'Fade', 'poti-mosaic-gallery' ),
				],
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Duração da Transição', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 50,
					],
				],
				'default' => [
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .poti-gallery__image' => 'transition-duration: {{SIZE}}ms;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register advanced tab controls
	 */
	private function register_advanced_controls() {
		// Section: Click Behavior
		$this->start_controls_section(
			'section_advanced_behavior',
			[
				'label' => esc_html__( 'Comportamento do Clique', 'poti-mosaic-gallery' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'click_action',
			[
				'label' => esc_html__( 'Ao Clicar', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'lightbox',
				'options' => [
					'none' => esc_html__( 'Nada', 'poti-mosaic-gallery' ),
					'lightbox' => esc_html__( 'Abrir Lightbox', 'poti-mosaic-gallery' ),
					'link' => esc_html__( 'Link Direto', 'poti-mosaic-gallery' ),
				],
			]
		);

		$this->add_control(
			'lightbox_transition',
			[
				'label' => esc_html__( 'Transição do Lightbox', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hero',
				'options' => [
					'hero' => esc_html__( 'Hero (Expandir)', 'poti-mosaic-gallery' ),
					'fade' => esc_html__( 'Fade', 'poti-mosaic-gallery' ),
					'slide' => esc_html__( 'Slide', 'poti-mosaic-gallery' ),
				],
				'condition' => [
					'click_action' => 'lightbox',
				],
			]
		);

		$this->add_control(
			'enable_keyboard',
			[
				'label' => esc_html__( 'Controles de Teclado', 'poti-mosaic-gallery' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'poti-mosaic-gallery' ),
				'label_off' => esc_html__( 'Off', 'poti-mosaic-gallery' ),
				'default' => 'yes',
				'condition' => [
					'click_action' => 'lightbox',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['gallery_images'] ) ) {
			$this->render_empty_state();
			return;
		}

		$column_count = isset( $settings['column_count']['size'] ) ? (int) $settings['column_count']['size'] : 4;
		$columns_config = isset( $settings['columns_config'] ) ? $settings['columns_config'] : [];
		$images = $settings['gallery_images'];

		// Calculate distribution
		$distribution = Layout_Calculator::distribute_images( $images, $columns_config );

		// Render gallery
		$this->render_gallery( $distribution, $settings, $column_count );
	}

	/**
	 * Render empty state message
	 */
	private function render_empty_state() {
		?>
		<div class="poti-gallery-empty-state">
			<p><?php esc_html_e( 'Adicione imagens à galeria para começar.', 'poti-mosaic-gallery' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render the gallery
	 *
	 * @param array $distribution Image distribution data
	 * @param array $settings Widget settings
	 * @param int $column_count Number of columns
	 */
	private function render_gallery( $distribution, $settings, $column_count ) {
		$hover_effect = $settings['hover_effect'];
		$click_action = $settings['click_action'];
		$show_caption = $settings['show_caption'] === 'yes';
		$glassmorphism = isset( $settings['glassmorphism'] ) && $settings['glassmorphism'] === 'yes';

		$container_classes = [
			'poti-gallery-container',
			'poti-gallery--columns-' . $column_count,
			'poti-gallery--hover-' . $hover_effect,
		];

		if ( $glassmorphism ) {
			$container_classes[] = 'poti-gallery--glassmorphism';
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>"
		     data-lightbox="<?php echo esc_attr( $click_action === 'lightbox' ? '1' : '0' ); ?>"
		     data-transition="<?php echo esc_attr( $settings['lightbox_transition'] ?? 'hero' ); ?>">

			<?php foreach ( $distribution['columns'] as $col_index => $column ) : ?>
				<?php
				$column_images = $column['images'];
				$internal_layout = $column['config']['internal_layout'] ?? 'stacked';
				$layout_classes = Layout_Calculator::get_layout_classes( $internal_layout, count( $column_images ) );
				?>

				<div class="poti-gallery__column <?php echo esc_attr( $layout_classes ); ?>">
					<?php foreach ( $column_images as $image ) : ?>
						<?php $this->render_image( $image, $settings, $show_caption ); ?>
					<?php endforeach; ?>
				</div>

			<?php endforeach; ?>

			<?php
			// Render overflow images (hidden, for lightbox)
			if ( ! empty( $distribution['overflow'] ) ) {
				$this->render_overflow_images( $distribution['overflow'], $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render a single image
	 *
	 * @param array $image Image data
	 * @param array $settings Widget settings
	 * @param bool $show_caption Whether to show caption
	 */
	private function render_image( $image, $settings, $show_caption ) {
		$image_id = $image['id'];
		$image_size = $settings['image_size'];
		$click_action = $settings['click_action'];

		$image_url = wp_get_attachment_image_url( $image_id, $image_size );
		$full_url = wp_get_attachment_image_url( $image_id, 'full' );
		$alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		$caption = wp_get_attachment_caption( $image_id );

		// Generate BlurHash placeholder
		$blurhash = Image_Optimizer::generate_blurhash( $image_id );

		$item_classes = [ 'poti-gallery__item' ];

		if ( $click_action === 'lightbox' ) {
			$item_classes[] = 'poti-gallery__item--lightbox';
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
			<a href="<?php echo esc_url( $full_url ); ?>"
			   class="poti-gallery__link"
			   data-fancybox="poti-gallery-<?php echo esc_attr( $this->get_id() ); ?>"
			   <?php if ( $caption && $show_caption ) : ?>
			   data-caption="<?php echo esc_attr( $caption ); ?>"
			   <?php endif; ?>>

				<img src="<?php echo esc_url( $image_url ); ?>"
				     alt="<?php echo esc_attr( $alt_text ); ?>"
				     class="poti-gallery__image"
				     loading="lazy"
				     <?php if ( $blurhash ) : ?>
				     style="background-image: url('<?php echo esc_attr( $blurhash ); ?>'); background-size: cover;"
				     <?php endif; ?>>

				<?php if ( $show_caption && $caption ) : ?>
					<div class="poti-gallery__caption-overlay">
						<span class="poti-gallery__caption"><?php echo esc_html( $caption ); ?></span>
					</div>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render overflow images (hidden for lightbox)
	 *
	 * @param array $overflow_images Overflow images
	 * @param array $settings Widget settings
	 */
	private function render_overflow_images( $overflow_images, $settings ) {
		?>
		<div class="poti-gallery__overflow" style="display: none;">
			<?php foreach ( $overflow_images as $image ) : ?>
				<?php $this->render_image( $image, $settings, false ); ?>
			<?php endforeach; ?>
		</div>

		<div class="poti-gallery__overflow-badge">
			+<?php echo esc_html( count( $overflow_images ) ); ?>
		</div>
		<?php
	}

	/**
	 * Render widget output in the editor
	 */
	protected function content_template() {
		?>
		<#
		if ( settings.gallery_images.length === 0 ) {
			#>
			<div class="poti-gallery-empty-state">
				<p><?php esc_html_e( 'Adicione imagens à galeria para começar.', 'poti-mosaic-gallery' ); ?></p>
			</div>
			<#
			return;
		}

		const columnCount = settings.column_count.size || 4;
		const hoverEffect = settings.hover_effect || 'zoom';
		const containerClasses = 'poti-gallery-container poti-gallery--columns-' + columnCount + ' poti-gallery--hover-' + hoverEffect;
		#>

		<div class="{{{ containerClasses }}}">
			<# _.each( settings.gallery_images, function( image, index ) { #>
				<div class="poti-gallery__item">
					<img src="{{{ image.url }}}" class="poti-gallery__image" alt="">
				</div>
			<# }); #>
		</div>
		<?php
	}
}
