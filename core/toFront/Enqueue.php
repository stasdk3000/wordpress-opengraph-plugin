<?php

namespace ManuteamCore\toFront;

use ManuteamCore\Helpers\Config\ConfigurationLoader;
use ManuteamCORE\Helpers\Options;

class Enqueue
{
    protected $Options;
    protected $Config;


    public function __construct()
    {
        $this->plugin_folder_name = basename( plugin_dir_path(  dirname( __FILE__ , 2 ) ) );
        $this->Options = new Options();
        $configLoader = new ConfigurationLoader();
        $this->Config = $configLoader->load()->getConfig();

        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        add_action('enqueue_block_editor_assets', [$this, 'admin_enqueues_editor_assets']);
    }

    /**
     * Enqueues Gutenberg assets
     * @since 1.0.0
     */
    public function admin_enqueues_editor_assets()
    {
        $screen = get_current_screen();
        if( $screen->post_type === 'post' ) {
            $admin_data_object = [
                'ajax_url' => admin_url('admin-ajax.php'),
            ];

            wp_enqueue_style(OGGWC_DOMAIN . '_admin', OGGWC_PACK_URL . '/admin/gutenberg/build/app.css');
            wp_enqueue_script(
                OGGWC_DOMAIN . '_sidebar',
                OGGWC_PACK_URL . '/admin/gutenberg/build/bundle.js',
                array('wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post'),
                time()
            );
            wp_localize_script(
                OGGWC_DOMAIN . '_sidebar',
                OGGWC_DOMAIN . '_admin',
                $admin_data_object
            );
        }
    }

    /**
     * Include scripts and css styles in admin
     *
     */
    public function admin_scripts()
    {

        wp_enqueue_style(OGGWC_DOMAIN . '_admin', OGGWC_PACK_URL . '/admin/gutenberg/build/app.css');
        wp_enqueue_script(OGGWC_DOMAIN . '_admin_chunk_settings', OGGWC_PACK_ADMIN_URL . '/assets/js/chunk-vendors.js', ['jquery'], 1.0, true);

        if (get_current_screen()->id == 'settings_page_open-graph-wp') {
            wp_register_script(OGGWC_DOMAIN . '-admin-settings', OGGWC_PACK_ADMIN_URL . '/assets/js/app.js', ['jquery'], 1.0, true);
        }

        $this->add_custom_styles_by_font_family();

        //$plugin_folder_name = basename( plugin_dir_path(  dirname( __FILE__ , 2 ) ) );
        $fonts = [];
        foreach ($this->Config['fonts'] as $font) {
            $object = new \stdClass();
            $object->name = $font;
            $object->url = plugins_url() . '/' .$this->plugin_folder_name . "/core/Fonts/$font";
            $fonts[] = $object;
        }

        $admin_data_object = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'oggwc_statistic_posts' => oggwc_get_statistic_posts(),
            'oggwc_preview_posts' => oggwc_get_statistic_posts('preview'),
            'oggwc_last_update' => oggwc_get_last_update(),
            'font_sizes' => $this->Config['font_sizes'],
            'font_family' => $fonts,
            'api_endpoints' => $this->get_api_endpoints(),
            'locale' => get_user_locale(),
        ];

        if ( $this->Config['hiw'] ) {
            $admin_data_object['oggwc_hiw'] = $this->Config['hiw'][0];
        }

        if ( $this->Config['opts'] ) {
            $admin_data_object = array_merge($admin_data_object, $this->get_js_object());
        }

        wp_localize_script(
            OGGWC_DOMAIN . '-admin-settings',
            OGGWC_DOMAIN . '_admin',
            $admin_data_object
        );
        wp_enqueue_script(OGGWC_DOMAIN . '-admin-settings');
        wp_enqueue_script(OGGWC_DOMAIN . '-vuejs', OGGWC_PACK_URL . '/libs/vue.min.js', ['jquery'], 1.0, true);
        wp_enqueue_script(OGGWC_DOMAIN . '-axios', OGGWC_PACK_URL . '/libs/axios.min.js', ['jquery'], 1.0, true);
    }


    private function get_js_object()
    {
        if (empty($this->Config['opts'])) {
            return false;
        }

        $data = [];

        foreach ($this->Config['opts'] as $opt) {
            $data[ OGGWC_DOMAIN . $opt ] =  $this->Options->getOption($opt);
        }

        return $data;
    }

    private function add_custom_styles_by_font_family()
    {
        $fonts = $this->Config['fonts'];

        foreach ($fonts as $font) {
            $font_name = explode('.', $font);
            $font_src = plugins_url() . '/' .$this->plugin_folder_name . "/core/Fonts/$font";
            $custom_css_font_family = ".font_family_$font_name[0] { font-family: {$font_name[0]}, Arial !important}";
            $custom_css_font_face = "@font-face { font-family: {$font_name[0]}; src: url($font_src); }";

            wp_add_inline_style(OGGWC_DOMAIN . '_admin', $custom_css_font_face);
            wp_add_inline_style(OGGWC_DOMAIN . '_admin', $custom_css_font_family);
        }
    }

    private function get_api_endpoints()
    {
        try {
            $domain_url = $this->Config['service_url'];
            $endpoints = $this->Config['endpoints'];

            $result = [];
            foreach ($endpoints as $key => $endpoint) {
                $result[$key] = $domain_url . $endpoint;
            }
            return $result;
        } catch (\Exception $exception)  {
            return $exception->getMessage();
        }
    }
}