<?php


namespace ManuteamCore\Helpers;


interface MetaInterface
{
    public function get_meta($id, $key);

    public function set_meta($id, $key, $value);

    public function delete_meta($id, $key);
}