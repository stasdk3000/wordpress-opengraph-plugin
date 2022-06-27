<?php

namespace ManuteamCore\Cron;

use ManuteamCore\Helpers\UploadMedia;
use ManuteamCore\Helpers\Options;
use ManuteamCore\Model\Query;
use ManuteamCore\Helpers\Meta;

class WaitResult implements WaitResultInterface
{
    protected $Options;
    protected $Query;
    protected $Meta;

    protected int $timewait = 100;

    public function __construct()
    {
        $this->Options = new Options();
        $this->Query   = new Query();
        $this->Meta    = new Meta();

        add_action( 'event_save_images_single_post', [$this, 'do_event_save_images_single_post'], 10, 4 );
    }

    public function event_save_images_single_post($args_service)
    {
        if (!is_array($args_service) || empty($args_service)) {
            return false;
        }

        wp_schedule_single_event(time() + $this->timewait, 'event_save_images_single_post', $args_service);

        return $this;
    }

    public function do_event_save_images_single_post( $post_id, $vk, $fb, $tw )
    {
        UploadMedia::clear_media_by_meta($post_id);

        $attach_id_vk = UploadMedia::upload_media($vk, $post_id);
        $attach_id_fb = UploadMedia::upload_media($fb, $post_id);
        //$attach_id_tw = $this->Upload->upload_media($tw);

        if ($attach_id_vk) {
            $this->Meta->set_meta($post_id, OGGWC_META_VK, wp_get_attachment_image_url($attach_id_vk, 'full'));
        }
        if ($attach_id_fb) {
            $this->Meta->set_meta($post_id, OGGWC_META_FB, wp_get_attachment_image_url($attach_id_fb, 'full'));
        }
    }

}