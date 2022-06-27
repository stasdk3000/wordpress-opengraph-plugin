<?php


namespace ManuteamCore\Helpers;


class Meta implements MetaInterface
{

    public function get_meta($id, $key)
    {
        return get_post_meta($id, $key, true);
    }

    public function set_meta($id, $key, $value)
    {
        return update_post_meta( $id, $key, $value );
    }

    public function delete_meta($id, $key)
    {
        return delete_post_meta( $id, $key);
    }
}