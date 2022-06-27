<?php

namespace ManuteamCore\Model;

use WP_Query;

class Query
{
    public function __construct()
    {

    }

    public function getPost($id, $key)
    {
        return get_post( $id, $key) ?: false;
    }

    public function getMainQuery($args = [], $queryLine = false)
    {
        $args_default = [
            'numberposts' => -1,
            'orderby'     => 'date',
            'order'       => 'DESC',
        ];

        $args = array_merge($args_default, $args);

        if (!$queryLine) {
            $query = new WP_Query($args);

            if (!$query->have_posts()) {
                return false;
            }
        } else {
            $newQuery = new WP_Query;
            $query = $newQuery->query($args);
        }

        return $query;
    }
}