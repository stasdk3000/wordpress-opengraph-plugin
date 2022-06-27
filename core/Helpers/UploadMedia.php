<?php


namespace ManuteamCore\Helpers;

use ManuteamCore\Helpers\Logger\Logger;
use ManuteamCore\Model\Query;

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

class UploadMedia implements UploadMediaInterface
{

    /**
     * Upload media by URL
     * @param $media_url
     * @param $post_id
     * @return bool|int|object|\WP_Error
     */
    public static function upload_media($media_url, $post_id = 0)
    {

        $tmp = download_url($media_url);

        if (is_wp_error($tmp)) {
            Logger::log(Logger::ERROR, json_encode($tmp) . ': core/Helpers/UploadMedia.php');
            return false;
        }

        $file_array = [
            'name' => basename($media_url),
            'tmp_name' => $tmp,
            'error' => 0,
            'size' => filesize($tmp),
        ];

        $attach_id = media_handle_sideload($file_array, $post_id);
        $data = wp_get_attachment_metadata($attach_id);
        $data['image_meta'][OGGWC_DOMAIN . '_flag'] = true;
        wp_update_attachment_metadata($attach_id, $data);

        @unlink($tmp);

        if (!$attach_id) {
            Logger::log(Logger::ERROR, __('Attach id is missing: core/Helpers/UploadMedia.php', OGGWC_DOMAIN));
            return false;
        }

        return $attach_id;
    }

    /**
     * Upload media by $FILE
     * @param $media_array
     * @param $post_id
     * @return bool|int|object|\WP_Error
     */
    public static function upload_media_by_file($media_array, $post_id = 0)
    {

        if (!is_array($media_array)) {
            return false;
        }

        $attach_id = media_handle_sideload($media_array, $post_id);

        if (!$attach_id) {
            return false;
        }

        return $attach_id;
    }

    /**
     * @param $media_id
     * @param string $type
     * @return bool|false|string
     */
    public static function get_media($media_id, $type = 'full')
    {
        $img_url = wp_get_attachment_image_url($media_id, $type);

        if (!$img_url) {
            return false;
        }

        return $img_url;
    }

    public static function clear_media_by_meta($post_id)
    {
        $args = [
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'post_parent' => $post_id,
        ];

        $Query = new Query();
        $data = $Query->getMainQuery($args, true);

        if ( ! $data) {
            return true;
        }

        foreach( $data as $attachment ){
            $metadata = wp_get_attachment_metadata($attachment->ID);
            if (isset($metadata['image_meta'][OGGWC_DOMAIN . '_flag'])) {
                wp_delete_post($attachment->ID, true);
            }
        }

        return true;
    }

    public static function clear_media_by_url($image_url) {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
        if (empty($attachment)) {
            return true;
        }
        wp_delete_post($attachment[0], true);
        return true;
    }
}