<?php
/**
 * This file for additional fucntions
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


if ( ! function_exists( 'oggwc_get_statistic_posts' ) ) {
    /**
     * Get 15 posts for admin page Statistic
     * @param string $type
     * @return array
     */
    function oggwc_get_statistic_posts( $type = 'statistic' )
    {
        $data = [];
        $args = [
            'numberposts' => 15,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post_type'   => 'post',
            'suppress_filters' => true,
        ];

        if ($type === 'statistic') {
            $args['meta_query'] = [
                'relation' => 'OR',
                [
                    'key' => OGGWC_META_VK,
                    'compare' => 'EXISTS'
                ],
                [
                    'key' => OGGWC_META_FB,
                    'compare' => 'EXISTS'
                ]
            ];
        }

        $posts = get_posts($args);

        foreach( $posts as $post ){

            $soc_vk = get_post_meta( $post->ID, OGGWC_META_VK, true );
            $soc_fb = get_post_meta( $post->ID, OGGWC_META_FB, true );
            $soc_tw = get_post_meta( $post->ID, OGGWC_META_FB, true );
            $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'full' );

            $data[] = [
                'ID' => $post->ID,
                'name' => $post->post_title,
                'url' => '/wp-admin/post.php?post='.$post->ID.'&action=edit',
                'soc_vk' => $soc_vk,
                'soc_fb' => $soc_fb,
                'soc_tw' => $soc_tw,
                'thumbnail_url' => $thumbnail_url,
            ];
        }
        wp_reset_postdata();
        return $data;

    }
}

if ( ! function_exists( 'oggwc_get_last_update' ) ) {
    /**
     * return text about last changes
     */
    function oggwc_get_last_update()
    {
        return ( get_transient('temporary_event') ) ? get_transient('temporary_event') : __( 'Changes not found', OGGWC_DOMAIN );
    }
}

if ( ! function_exists( 'oggwc_head_append' ) ) {
    /**
     * Embedding OG fields in markup
     */
    function oggwc_head_append()
    {
        global $post;

        if (!(is_singular()) || !get_queried_object_id()) {
            return;
        }

        if ($img = oggwc_get_post_attached($post->ID, OGGWC_META_VK)) {
            echo '<meta property="vk:image" content="' . $img . '">';
        }
        if ($img = oggwc_get_post_attached($post->ID, OGGWC_META_FB)) {
            echo '<meta property="twitter:image" content="' . $img . '">';
        }
        if ($img = oggwc_get_post_attached($post->ID, OGGWC_META_FB)) {
            echo '<meta property="og:image" content="' . $img . '">';
            echo '<meta property="og:image:width" content="476">';
            echo '<meta property="og:image:height" content="248">';
        }

    }
}

if ( ! function_exists( 'oggwc_get_post_attached' ) ) {

    /**
     * @param $id
     * @param $social
     * @return bool
     */
    function oggwc_get_post_attached($id, $social)
    {
        if (!$attach = get_post_meta($id, $social, true)) {
            return false;
        } else {
            return $attach;
        }
    }
}