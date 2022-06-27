<?php
/**
 * interface Options
 * Use for WordPress get options
 */

namespace ManuteamCore\Helpers;


interface OptionsInterface
{
    public function getOption($key);

    public function setOption($key, $value);
}