<?php
/**
 * Class Heartbeat_tools
 * Using for all Heartbeat functions
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Heartbeat_tools
{

    protected static $_instance;

    /**
     * @return Heartbeat_tools
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct( ) {

        // init actions & filters & hooks
        add_filter( 'heartbeat_settings', [ $this, 'oggwc_heartbeat_settings' ] );
        add_filter( 'heartbeat_received', [ $this, 'oggwc_receive_heartbeat' ], 10, 2 );
        add_filter( 'heartbeat_send',     [ $this, 'oggwc_send_heartbeat' ], 10, 2 );
    }

    /**
     * @param $settings
     * @return mixed
     */
    public function oggwc_heartbeat_settings($settings) {

        // set heartbeat interval 15 sec.
        $settings['mainInterval'] = 15;
        return $settings;
    }

    /**
     * Received Heartbeat data and generate response.
     *
     * @flag oggwc_flag
     *
     * @param $response
     * @param $data
     * @return mixed
     */
    public function oggwc_receive_heartbeat( $response, $data ) {

        // check oggwc_flag data
        if ( empty( $data['oggwc_flag'] ) ) {
            return $response;
        }

        // get data
        $received_data = $data['example_1'];

        // set data
        $response['example_2'] = [];

        // callback new heartbeat array
        return $response;
    }

    /**
     * Sending data to a web client
     *
     * @param $response
     * @param $screen_id
     * @return mixed
     */
    public function oggwc_send_heartbeat( $response, $screen_id ) {

        $response['oggwc_flag'] = get_transient('temporary_event');

        return $response;
    }
}