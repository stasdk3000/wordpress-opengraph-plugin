<?php
/**
 * This file for main admin part
 * @since 1.0
 */
class Oggwc_admin {

    private $submenu_page;

    public function __construct( $submenu_page ) {
        $this->submenu_page = $submenu_page;
    }

    public function init() {
        add_action( 'admin_menu', [ $this, 'add_options_page' ] );
    }

    public function add_options_page() {

        add_options_page(
            'Open Graph',
            'Open Graph',
            'manage_options',
            'open-graph-wp',
            array( $this->submenu_page, 'render' )
        );
    }
}