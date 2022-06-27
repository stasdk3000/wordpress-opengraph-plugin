<?php


namespace ManuteamCore\Helpers;


interface UploadMediaInterface
{

    public static function upload_media($media_url, $post_id);

    public static function get_media($media_id);
}