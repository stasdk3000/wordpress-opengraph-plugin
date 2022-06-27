<?php
namespace ManuteamCore\Cron;

interface WaitResultInterface
{
    public function event_save_images_single_post($args_service);

    public function do_event_save_images_single_post($post_id, $image1, $image2, $image3);
}