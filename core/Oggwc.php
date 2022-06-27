<?php

namespace ManuteamCore;

use ManuteamCORE\toFront\Enqueue;

class Oggwc
{

    public function __construct()
    {
        new toFront\Enqueue();
        new Controllers\Endpoints();
    }
}