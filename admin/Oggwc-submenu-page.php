<?php
/**
 * Creates the submenu page for the plugin.
 *
 * @package Custom_Admin_Settings
 */

class Oggwc_Submenu_Page {

    /**
     * This function renders the contents of the page associated with the Submenu
     * that invokes the render method. In the context of this plugin, this is the
     * Submenu class.
     * @param $deserializer
     */

    public function __construct( $deserializer ) {
        // null
    }

    public function render() {
        include_once( 'template/template-main.php' );
    }
}