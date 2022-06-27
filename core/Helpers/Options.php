<?php
/**
 * Class Options
 * Use for WordPress get options
 */

namespace ManuteamCore\Helpers;


class Options implements OptionsInterface
{
    protected string $option_domain = OGGWC_DOMAIN;

    public function getOption($key){
        return get_option( $this->option_domain . $key ) ?: NULL;
    }

    public function setOption($key, $value){

        $key = ( stripos($key, $this->option_domain) !== false ) ? $key : $this->option_domain . $key;

        update_option( $key, $value, false );
    }

}