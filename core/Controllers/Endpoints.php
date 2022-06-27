<?php

namespace ManuteamCore\Controllers;

use ManuteamCore\Helpers\Config\ConfigurationLoader;
use ManuteamCore\Helpers\Logger\Logger;
use ManuteamCore\Helpers\Options;
use ManuteamCore\Helpers\UploadMedia;
use ManuteamCore\Helpers\Prepare;
use ManuteamCore\Model\Query;
use ManuteamCore\Helpers\Meta;

class Endpoints implements EndpointsInterface
{

    protected $Options;
    protected $Upload;
    protected $Prepare;
    protected $Remotes;
    protected $Query;
    protected $Meta;
    protected $additionalHost;
    protected $validateEndpoint;

    public function __construct()
    {
        $this->Options = new Options();
        $this->Remotes = new Remotes();
        $this->Upload  = new UploadMedia();
        $this->Prepare = new Prepare();
        $this->Query   = new Query();
        $this->Meta    = new Meta();

        $configLoader  = new ConfigurationLoader();
        $config        = $configLoader->load()->getConfig();

        $this->additionalHost = str_replace(['https://', 'http://'], [''], $config['service_url']);
        $this->validateEndpoint = $config['endpoints']['validate_key'];

        add_filter('http_request_host_is_external', [$this, 'allow_additional_host'], 10, 3 );

        $this->register_ajax_endpoints($config['points']);

        if ( $this->Prepare->getEndpointOG() ) {
            add_action('wp_ajax_nopriv_' . $this->Prepare->getEndpointOG(), [$this, 'service_endpoint_callback']);
        }

    }

    public function allow_additional_host($allow, $host, $url)
    {
        return $host == $this->additionalHost || $allow;
    }

    public function register_ajax_endpoints($points)
    {
        if (!is_array($points) && empty($points)) {
            return false;
        }

        if (!defined('OGGWC_DOMAIN')) {
            return false;
        }

        foreach ($points as $register_name) {
            add_action('wp_ajax_' . OGGWC_DOMAIN . '_' . $register_name, [$this, $register_name . '_callback']);
        }

        return true;
    }

    /**
     * function to update plugin option:
     * @param {int} oggwc_logo_img  —  logo ( image url  )
     * @param {string} oggwc_lic_key   —  license key
     * @param {bool} oggwc_soc_vk — generate VK preview
     * @param {bool} oggwc_soc_fb — generate facebook preview
     * @param {bool} oggwc_soc_tw — generate Twitter preview
     * @param {array} oggwc_sizes — sizes for image generator ['vk'=>'', 'fb'=>'', 'tw'=>'']
     * @param {string} oggwc_default_color - background color preview img
     * @param {array} oggwc_soc_vk_text_coords - coordinates position text vk preview ['x' => '', y => '']
     * @param {array} oggwc_soc_vk_logo_coords - coordinates position logo vk preview ['x' => '', y => '']
     * @param {array} oggwc_soc_fb_text_coords - coordinates position text fb preview ['x' => '', y => '']
     * @param {array} oggwc_soc_fb_logo_coords - coordinates position logo fb preview ['x' => '', y => '']
     * @param {array} oggwc_soc_tw_text_coords - coordinates position text tw preview ['x' => '', y => '']
     * @param {array} oggwc_soc_tw_logo_coords - coordinates position logo tw preview ['x' => '', y => '']
     */
    public function update_admin_settings_callback()
    {

        try {

            if (!$_REQUEST) {
                throw new \Exception(__('Request is missing', OGGWC_DOMAIN));
            }

            if (!empty($_REQUEST[OGGWC_DOMAIN . '_lic_key'])) {
                $validate = $this->Remotes->checkValidate($_REQUEST[OGGWC_DOMAIN . '_lic_key'], $this->additionalHost, $this->validateEndpoint);
                if (is_wp_error($validate)) {
                    throw new \Exception($validate->get_error_message());
                }
            }

            $result = [];
            foreach ($_REQUEST as $key => $arg) {
                if (isset($_REQUEST[$key]) and ("" !== trim($arg))) {
                    $result[$key] = $arg;
                    $this->Options->setOption($key, $arg);
                }
            }

            wp_send_json(['result' => $result], 200);

        } catch (\Exception $e) {
            wp_send_json_error((array(
                'text' => $e->getMessage(),
            )), 200);
        }
    }

    /**
     * Upload image handler
     * Using in settings page for upload logo
     */
    public function upload_logo_callback()
    {

        try {

            if (!$_FILES['file']) {
                throw new \Exception(__('File is missing', OGGWC_DOMAIN));
            }

            $attach_id = $this->Upload->upload_media_by_file($_FILES['file']);

            if (!$attach_id) {
                throw new \Exception(__('Attach is missing', OGGWC_DOMAIN));
            }

            $attach_url = $this->Upload->get_media($attach_id, OGGWC_DOMAIN.'-logo-thumb');
            $this->Options->setOption('_logo_img', $attach_url);

            wp_send_json(['img_url' => $attach_url], 200);

        } catch (\Exception $e) {
            wp_send_json_error((array(
                'text' => $e->getMessage(),
            )), 200);
        }
    }

    public function service_endpoint_callback()
    {
        global $wp_version;
        $event = $_POST['event'];
        // add new log to log file
        $log = $wp_version . ":" . current_time('timestamp') . ":" . $event . "\n";
        // write log in file
        Logger::log(Logger::INFO, $log);
        wp_send_json(['wp_v' => $wp_version, 'time' => current_time('timestamp')]);
    }

    /**
     * Delete image handler
     * Use in settings page for upload logo
     */
    public function delete_logo_callback()
    {
        try {

            if (empty($_REQUEST)) {
                throw new \Exception(__('Any problem', OGGWC_DOMAIN));
            }
            $currentLogo = $this->Options->getOption('_logo_img');
            if (!empty($currentLogo)) {
                UploadMedia::clear_media_by_url($currentLogo);
            }
            $this->Options->setOption('_logo_img', '');

            wp_send_json(['status' => 'ok'], 200);

        } catch (\Exception $e) {
            wp_send_json_error((array(
                'text' => $e->getMessage(),
            )), 200);
        }
    }

    /**
     * Search post by chars
     */
    public function search_post_callback()
    {
        if (!$_REQUEST['s']) {
            wp_send_json_error(null, 400);
        }

        $data = [];

        $args = [
            's' => $_REQUEST['s'],
            'post_type' => 'post',
            'numberposts' => 10,
            'orderby' => 'date',
            'order' => 'DESC',
        ];
        $query = $this->Query->getMainQuery($args);

        if (!$query) {
            wp_send_json_error(null, 400);
        }

        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();

            $data[] = [
                'ID' => $id,
                'name' => get_the_title(),
                'url' => '/wp-admin/post.php?post=' . $id . '&action=edit',
                'soc_vk' => $this->Meta->get_meta($id, OGGWC_META_VK),
                'soc_fb' => $this->Meta->get_meta($id, OGGWC_META_FB),
                'soc_tw' => $this->Meta->get_meta($id, OGGWC_META_FB),
            ];
        }

        wp_reset_postdata();

        wp_send_json_success($data, 200);
    }

    /**
     * AJAX action if user click a button on post_edit page "Generate OG Previews"
     */
    public function hand_generate_for_post_callback()
    {
        try {

            if (!isset($_REQUEST['post_id'])) {
                throw new \Exception(__('Post id is missing', OGGWC_DOMAIN));
            }

            $post_data = $this->Prepare->getPreparedPostData($_REQUEST['post_id']);
            $callback_body = $this->Prepare->prepare_for_generate($post_data);

            wp_send_json(['post_data' => $callback_body], 200);

        } catch (\Exception $e) {
            wp_send_json_error((array(
                'text' => $e->getMessage(),
            )), 200);
        }
    }

}