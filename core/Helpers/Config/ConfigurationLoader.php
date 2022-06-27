<?php

namespace ManuteamCore\Helpers\Config;

class ConfigurationLoader implements ConfigurationLoaderInterface
{
    private array $configParams;

    public function load()
    {
        $data = file_get_contents(OGGWC_CORE . "/configuration.json");

        $paramsArray = json_decode($data, true);

        $this->configParams = $paramsArray;

        return $this;
    }

    public function getConfig()
    {
        return $this->configParams;
    }
}