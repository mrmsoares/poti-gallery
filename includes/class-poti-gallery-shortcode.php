<?php
/**
 * Classe de Shortcode do Poti Gallery
 */
class Poti_Gallery_Shortcode {

    public function __construct() {
        add_shortcode('poti_gallery', array($this, 'render_gallery'));
    }

    public function render_gallery($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'columns' => '',
            'layout' => '',
            'lightbox' => ''
        ), $atts);

        $post_id = intval($atts['id']);

        if (!$post_id) {
            return '<p>ID da galeria não especificado.</p>';
        }

        $post = get_post($post_id);

        if (!$post || $post->post_type !== 'poti_gallery') {
            return '<p>Galeria não encontrada.</p>';
        }

        // Pega imagens e configurações
        $images = get_post_meta($post_id, '_poti_gallery_images', true) ?: array();
        $columns = $atts['columns'] ?: get_post_meta($post_id, '_poti_gallery_columns', true) ?: '3';
        $layout = $atts['layout'] ?: get_post_meta($post_id, '_poti_gallery_layout', true) ?: 'grid';
        $lightbox = $atts['lightbox'] ?: get_post_meta($post_id, '_poti_gallery_lightbox', true) ?: 'yes';

        if (empty($images)) {
            return '<p>Nenhuma imagem na galeria.</p>';
        }

        ob_start();
        ?>
        <div class="poti-gallery poti-gallery-<?php echo esc_attr($layout); ?> poti-gallery-columns-<?php echo esc_attr($columns); ?>"
             data-lightbox="<?php echo esc_attr($lightbox); ?>"
             data-gallery-id="<?php echo esc_attr($post_id); ?>">
            <?php foreach ($images as $image_id): ?>
                <?php
                $image_url = wp_get_attachment_image_url($image_id, 'large');
                $thumbnail_url = wp_get_attachment_image_url($image_id, 'medium');
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                $image_title = get_the_title($image_id);
                ?>
                <div class="poti-gallery-item">
                    <a href="<?php echo esc_url($image_url); ?>"
                       class="poti-gallery-link"
                       data-lightbox="poti-gallery-<?php echo esc_attr($post_id); ?>"
                       data-title="<?php echo esc_attr($image_title); ?>">
                        <img src="<?php echo esc_url($thumbnail_url); ?>"
                             alt="<?php echo esc_attr($image_alt); ?>"
                             loading="lazy" />
                        <div class="poti-gallery-overlay">
                            <span class="poti-gallery-icon">🔍</span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
