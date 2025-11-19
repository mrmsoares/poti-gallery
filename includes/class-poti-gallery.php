<?php
/**
 * Classe principal do Poti Gallery
 */
class Poti_Gallery {

    protected $version;

    public function __construct() {
        $this->version = POTI_GALLERY_VERSION;
    }

    public function run() {
        // Carrega estilos e scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Inicializa componentes
        $admin = new Poti_Gallery_Admin();
        $shortcode = new Poti_Gallery_Shortcode();

        // Registra custom post type
        add_action('init', array($this, 'register_gallery_post_type'));
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'poti-gallery',
            POTI_GALLERY_PLUGIN_URL . 'css/poti-gallery.css',
            array(),
            $this->version,
            'all'
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            'poti-gallery',
            POTI_GALLERY_PLUGIN_URL . 'js/poti-gallery.js',
            array('jquery'),
            $this->version,
            true
        );

        // Localiza script com dados do WordPress
        wp_localize_script('poti-gallery', 'potiGallery', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('poti-gallery-nonce')
        ));
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style(
            'poti-gallery-admin',
            POTI_GALLERY_PLUGIN_URL . 'css/poti-gallery-admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_media();
        wp_enqueue_script(
            'poti-gallery-admin',
            POTI_GALLERY_PLUGIN_URL . 'js/poti-gallery-admin.js',
            array('jquery', 'jquery-ui-sortable'),
            $this->version,
            true
        );
    }

    public function register_gallery_post_type() {
        $labels = array(
            'name' => 'Galerias',
            'singular_name' => 'Galeria',
            'menu_name' => 'Poti Gallery',
            'add_new' => 'Adicionar Nova',
            'add_new_item' => 'Adicionar Nova Galeria',
            'edit_item' => 'Editar Galeria',
            'new_item' => 'Nova Galeria',
            'view_item' => 'Ver Galeria',
            'search_items' => 'Buscar Galerias',
            'not_found' => 'Nenhuma galeria encontrada',
            'not_found_in_trash' => 'Nenhuma galeria na lixeira'
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-format-gallery',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
        );

        register_post_type('poti_gallery', $args);
    }
}
