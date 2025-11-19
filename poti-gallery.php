<?php
/**
 * Plugin Name: Poti Gallery
 * Plugin URI: https://github.com/mrmsoares/poti-gallery
 * Description: Galeria dinâmica de imagens com recursos avançados de exibição e gerenciamento
 * Version: 1.0.0
 * Author: MRM Soares
 * Author URI: https://github.com/mrmsoares
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: poti-gallery
 * Domain Path: /languages
 */

// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Define constantes do plugin
define('POTI_GALLERY_VERSION', '1.0.0');
define('POTI_GALLERY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('POTI_GALLERY_PLUGIN_URL', plugin_dir_url(__FILE__));

// Inclui arquivos necessários
require_once POTI_GALLERY_PLUGIN_DIR . 'includes/class-poti-gallery.php';
require_once POTI_GALLERY_PLUGIN_DIR . 'includes/class-poti-gallery-admin.php';
require_once POTI_GALLERY_PLUGIN_DIR . 'includes/class-poti-gallery-shortcode.php';

// Inicializa o plugin
function poti_gallery_init() {
    $plugin = new Poti_Gallery();
    $plugin->run();
}
add_action('plugins_loaded', 'poti_gallery_init');

// Hook de ativação
register_activation_hook(__FILE__, 'poti_gallery_activate');
function poti_gallery_activate() {
    // Cria tabela personalizada se necessário
    global $wpdb;
    $table_name = $wpdb->prefix . 'poti_galleries';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        description text,
        images longtext,
        settings longtext,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Hook de desativação
register_deactivation_hook(__FILE__, 'poti_gallery_deactivate');
function poti_gallery_deactivate() {
    // Limpeza se necessário
}
