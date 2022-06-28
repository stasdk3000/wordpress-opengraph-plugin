<?php
/**
 * Plugin Name: Open Graph Generator
 * Plugin URI:
 * Description: Open Graph Wizard and Construction
 * Author: MANU:TEAM { Web Development Services }
 * Version: 1.0.0
 * Author URI: https://manu.team
 * Requires PHP: 7.0
 * Tested up to: 5.4
 * Text Domain: oggwc
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Define constants
 *
 * @since 1.0.0
 */
define( 'OGGWC_DIR', dirname( __FILE__ ) . '/' );
define( 'OGGWC_CORE', dirname( __FILE__ ) . '/core' );
define( 'OGGWC_PACK_URL', plugins_url( '', __FILE__ ) );
define( 'OGGWC_DOMAIN', 'oggwc' );
define( 'OGGWC_PACK_ADMIN_URL', plugins_url( 'admin/'.OGGWC_DOMAIN.'/admin-settings', __FILE__ ) );
define( 'OGGWC_META_VK', OGGWC_DOMAIN . '_meta_vk' );
define( 'OGGWC_META_FB', OGGWC_DOMAIN . '_meta_fb' );
define( 'OGGWC_PLUGIN_JSON', 'https://repo.manu.team/json/42000000010/thefile.json' );

/**
 * Main class
 *
 * @since 1.0.0
 */
class Oggwc_Pack {

    private static $_instance = null;


    /**
     * Main class instance.
     *
     * Ensures only one instance of class is loaded or can be loaded.
     */
    public static function instance() {
        if ( !self::$_instance ) {
            self::$_instance = new Oggwc_Pack();
        }
        return self::$_instance;
    }

    /**
     * Class constructor
     * Adds all the methods to appropriate hooks or shortcodes
     */
    public function __construct() {

        register_activation_hook( __FILE__,   [ $this, 'on_activation' ] );
        register_deactivation_hook( __FILE__, [ $this, 'on_deactivation' ] );

        include OGGWC_CORE . '/autoload.php';

        require dirname( __FILE__ ) . '/admin/Oggwc-submenu-page.php';
        require_once dirname( __FILE__ ) . '/admin/Oggwc-admin.php';
        require_once dirname( __FILE__ ) . '/inc/oggwc-functions.php';
        require_once dirname( __FILE__ ) . '/inc/Meta_tools.php';
        require_once dirname( __FILE__ ) . '/inc/Heartbeat_tools.php';

        require dirname(__FILE__) . '/inc/plugin-update-checker/plugin-update-checker.php';

        $update_checker = Puc_v4_Factory::buildUpdateChecker(
            OGGWC_PLUGIN_JSON,
            __FILE__,
            OGGWC_DOMAIN
        );

        new ManuteamCore\Oggwc();

        // Add action hooks
        add_action( 'init', [ $this, 'includes' ] );
        add_action( 'init',  [ $this, 'gutenberd_meta_add' ] );
        add_action( 'init', [ $this, 'logo_size'] );

        $admin_part = new Oggwc_admin( new Oggwc_Submenu_Page( null ) );
        $admin_part->init();

        Meta_tools::instance();
        Heartbeat_tools::instance();
        add_action( 'wp_head', 'oggwc_head_append' );

        add_action( 'admin_notices', function(){
            if( !$this->check_version() ) {
                echo '<div class="notice notice-error is-dismissible"><p>Version of WordPress should be >= 5.4 and PHP 7.0 +</p></div>';
            }
        });
    }

    /**
     * Check WordPress and PHP versions
     *
     * @since 1.0.0
     */
    public function check_version() {
        global $wp_version;
        if ( version_compare( PHP_VERSION, '7', '>' ) && version_compare( $wp_version, '5.4', '>' ) ) {
            return true;
        }
        return false;
    }

    /**
     * Get plugin
     *
     * @since 1.0.0
     */
    public static function plugin() {
        return isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    }

    /**
     * Activation plugin
     *
     * @since 1.0.0
     */
    public static function on_activation() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        update_option( OGGWC_DOMAIN . '_default_text_color', '#fff', false );
        update_option( OGGWC_DOMAIN . '_title_font_family', 'Roboto-Regular', false );
        update_option( OGGWC_DOMAIN . '_title_font_size', 2, false );
        update_option( OGGWC_DOMAIN . '_default_color', '#1c1c1c', false );
        update_option( OGGWC_DOMAIN . '_bg_opacity', 0.7, false );
        update_option( OGGWC_DOMAIN . '_logo_img', 'null', false );
        update_option( OGGWC_DOMAIN . '_title_enable', 'false', false );
        update_option( OGGWC_DOMAIN . '_soc_vk', 'true', false );
        update_option( OGGWC_DOMAIN . '_text_position', 'left', false );
        
    }

    /**
     * Deactivation plugin
     *
     * @since 1.0.0
     */
    public static function on_deactivation() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_clear_scheduled_hook( 'event_save_images_single_post' );
            return;
        }
    }

    /**
     * Includes of files
     *
     * @since 1.0.0
     */
    public function includes() {
        load_plugin_textdomain( OGGWC_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
    }

    /**
     * Add Gutenberg meta
     * @since 1.1.0
     */
    public function gutenberd_meta_add(){

        register_meta( 'post', OGGWC_META_VK, array(
            'type'		=> 'string',
            'single'	=> true,
            'show_in_rest'	=> true,
        ) );

        register_meta( 'post', OGGWC_META_FB, array(
            'type'		=> 'string',
            'single'	=> true,
            'show_in_rest'	=> true,
        ) );

    }

    public function logo_size() {
        if (function_exists('add_image_size')) {
            add_image_size(OGGWC_DOMAIN.'-logo-thumb', 200, 200);
        }
    }

}
Oggwc_Pack::instance();

