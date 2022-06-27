<?php


function ManuteamCoreLoadClass($className)
{
    $path = OGGWC_CORE . str_replace('\\', '/', substr($className, 12)) . '.php';

    if (file_exists($path)) {
        require $path;
    }
}

spl_autoload_register('ManuteamCoreLoadClass', true);