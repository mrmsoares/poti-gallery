<?php
/**
 * Classe de administração do Poti Gallery
 */
class Poti_Gallery_Admin {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_gallery_meta_boxes'));
        add_action('save_post', array($this, 'save_gallery_meta'));
        add_action('admin_menu', array($this, 'add_settings_page'));
    }

    public function add_gallery_meta_boxes() {
        add_meta_box(
            'poti_gallery_images',
            'Imagens da Galeria',
            array($this, 'render_gallery_meta_box'),
            'poti_gallery',
            'normal',
            'high'
        );

        add_meta_box(
            'poti_gallery_settings',
            'Configurações da Galeria',
            array($this, 'render_settings_meta_box'),
            'poti_gallery',
            'side',
            'default'
        );
    }

    public function render_gallery_meta_box($post) {
        wp_nonce_field('poti_gallery_meta_box', 'poti_gallery_meta_box_nonce');
        $images = get_post_meta($post->ID, '_poti_gallery_images', true);
        ?>
        <div class="poti-gallery-admin">
            <div class="gallery-images-container" id="poti-gallery-images">
                <?php
                if (!empty($images)) {
                    foreach ($images as $image_id) {
                        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                        echo '<div class="gallery-image-item" data-id="' . esc_attr($image_id) . '">';
                        echo '<img src="' . esc_url($image_url) . '" />';
                        echo '<button type="button" class="remove-image">×</button>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <button type="button" class="button button-primary" id="add-gallery-images">
                Adicionar Imagens
            </button>
            <input type="hidden" name="poti_gallery_images" id="poti-gallery-images-input"
                   value="<?php echo esc_attr(json_encode($images ?: array())); ?>" />
        </div>
        <?php
    }

    public function render_settings_meta_box($post) {
        $columns = get_post_meta($post->ID, '_poti_gallery_columns', true) ?: '3';
        $layout = get_post_meta($post->ID, '_poti_gallery_layout', true) ?: 'grid';
        $lightbox = get_post_meta($post->ID, '_poti_gallery_lightbox', true) ?: 'yes';
        ?>
        <p>
            <label for="poti_gallery_columns">Colunas:</label>
            <select name="poti_gallery_columns" id="poti_gallery_columns" class="widefat">
                <option value="2" <?php selected($columns, '2'); ?>>2 Colunas</option>
                <option value="3" <?php selected($columns, '3'); ?>>3 Colunas</option>
                <option value="4" <?php selected($columns, '4'); ?>>4 Colunas</option>
                <option value="5" <?php selected($columns, '5'); ?>>5 Colunas</option>
            </select>
        </p>
        <p>
            <label for="poti_gallery_layout">Layout:</label>
            <select name="poti_gallery_layout" id="poti_gallery_layout" class="widefat">
                <option value="grid" <?php selected($layout, 'grid'); ?>>Grade</option>
                <option value="masonry" <?php selected($layout, 'masonry'); ?>>Masonry</option>
                <option value="carousel" <?php selected($layout, 'carousel'); ?>>Carrossel</option>
            </select>
        </p>
        <p>
            <label for="poti_gallery_lightbox">
                <input type="checkbox" name="poti_gallery_lightbox" id="poti_gallery_lightbox"
                       value="yes" <?php checked($lightbox, 'yes'); ?> />
                Ativar Lightbox
            </label>
        </p>
        <p>
            <strong>Shortcode:</strong><br>
            <code>[poti_gallery id="<?php echo $post->ID; ?>"]</code>
        </p>
        <?php
    }

    public function save_gallery_meta($post_id) {
        if (!isset($_POST['poti_gallery_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['poti_gallery_meta_box_nonce'], 'poti_gallery_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Salva imagens
        if (isset($_POST['poti_gallery_images'])) {
            $images = json_decode(stripslashes($_POST['poti_gallery_images']), true);
            update_post_meta($post_id, '_poti_gallery_images', $images);
        }

        // Salva configurações
        if (isset($_POST['poti_gallery_columns'])) {
            update_post_meta($post_id, '_poti_gallery_columns', sanitize_text_field($_POST['poti_gallery_columns']));
        }

        if (isset($_POST['poti_gallery_layout'])) {
            update_post_meta($post_id, '_poti_gallery_layout', sanitize_text_field($_POST['poti_gallery_layout']));
        }

        $lightbox = isset($_POST['poti_gallery_lightbox']) ? 'yes' : 'no';
        update_post_meta($post_id, '_poti_gallery_lightbox', $lightbox);
    }

    public function add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=poti_gallery',
            'Configurações',
            'Configurações',
            'manage_options',
            'poti-gallery-settings',
            array($this, 'render_settings_page')
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Configurações do Poti Gallery</h1>
            <p>Plugin de galeria dinâmica de imagens para WordPress.</p>
            <p><strong>Versão:</strong> <?php echo POTI_GALLERY_VERSION; ?></p>
            <hr>
            <h2>Como Usar</h2>
            <ol>
                <li>Crie uma nova galeria em "Poti Gallery > Adicionar Nova"</li>
                <li>Adicione imagens à galeria</li>
                <li>Configure o layout e opções</li>
                <li>Copie o shortcode gerado</li>
                <li>Cole o shortcode em qualquer post ou página</li>
            </ol>
        </div>
        <?php
    }
}
