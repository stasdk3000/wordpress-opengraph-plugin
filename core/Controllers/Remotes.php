<?php


namespace ManuteamCore\Controllers;
use ManuteamCore\Helpers\Config\ConfigurationLoader;
use ManuteamCore\Helpers\Options;
use WP_Error;

class Remotes implements RemotesInterface
{

    private string $service_create;
    private string $website_key;
    private string $website_url;
    protected $Options;

    public function __construct()
    {
        $configLoader  = new ConfigurationLoader();
        $this->Options = new Options();
        $config        = $configLoader->load()->getConfig();

        $this->service_create = $config['service_url'].$config['create_endpoint'];
        $this->website_url = $this->getWebsiteURL();
        $this->website_key = (string)$this->Options->getOption('_lic_key') ?: $config['dev_key'];
    }

    /**
     * @param $body
     * @return bool|mixed|string
     */
    public function send_to_create_img_oie95( $body )
    {

        if (!is_string($body)) {
            return false;
        }

        if (strlen($body) < 1) {
            return false;
        }

        $request = [
            'method' => 'POST',
            'timeout' => 30,
            'headers' => [
                'Content-Type' => "application/json",
                'Authorization' => "Bearer 888",
                'token' => $this->website_key
            ],
            'body' => $body
        ];

        $result = wp_remote_post(
            $this->service_create,
            $request
        );

        if( is_wp_error( $result ) ) {
            return $result->get_error_message();
        }

        return $result;
    }

    /**
     * Check key validation
     * @param $token
     * @param $service_url
     * @param $endpoint
     * @return WP_Error|bool
     */
    public function checkValidate($token, $service_url, $endpoint)
    {
        try {
            $validate = wp_remote_get("https://" . $service_url . $endpoint, [
                'headers' => [
                    'token' => $token,
                    'Origin' => $this->getWebsiteURL(),
                ]
            ]);
            if ($validate['response']['code'] !== 200) {

                if ($validate['response']['code'] === 429) {
                    throw new \Exception(__($validate['response']['message']), 429);
                } else {
                    throw new \Exception(__('License key is not valid', OGGWC_DOMAIN), 400);
                }
            }
            return true;
        } catch (\Exception $e) {
            return new \WP_Error(OGGWC_DOMAIN . '_errors', $e->getMessage(), $e->getCode());
        }
    }

    public function getWebsiteURL()
    {
        return str_replace(['https://', 'http://'], ['', ''], get_site_url(null, '', 'https'));
    }
}
