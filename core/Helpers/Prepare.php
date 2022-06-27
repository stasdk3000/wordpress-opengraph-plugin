<?php

namespace ManuteamCore\Helpers;
use ManuteamCore\Controllers\Remotes;
use ManuteamCore\Cron\WaitResult;
use ManuteamCore\Helpers\Logger\Logger;
use ManuteamCore\Helpers\Options;

class Prepare
{

    protected string $option_domain = OGGWC_DOMAIN;

    protected $Remotes;
    protected $WaitResult;
    protected $Options;

    public function __construct()
    {
        $this->Remotes = new Remotes();
        $this->WaitResult = new WaitResult();
        $this->Options = new Options();

        add_action( 'post_updated', [$this, 'generate_post_save'], 9999, 1 );
    }


    public function getEndpointOG()
    {
        if ($this->Options->getOption('_lic_key') === NULL) {
            return false;
        }
        $token = explode(".", $this->Options->getOption('_lic_key'));
        return $token['1'];
    }

    public function getTextData($post_id, $title_enable = true)
    {
        return [
            'text' => get_the_title($post_id) && $title_enable ? get_the_title($post_id) : '',
            'font_color' => $this->Options->getOption('_default_text_color'),
            'position' => $this->Options->getOption('_text_position'),
            'font_family' => $this->Options->getOption('_title_font_family') . '.ttf',
            'font_size' => (int)$this->Options->getOption('_title_font_size')
        ];
    }

    public function getBackgroundData()
    {
        return [
            'color' => $this->Options->getOption('_default_color'),
            'opacity' => (int)$this->Options->getOption('_bg_opacity')
        ];
    }

    public function getPreparedPostData($post_id)
    {
        $img_id = get_post_thumbnail_id($post_id);
        $img_url = wp_get_attachment_image_url($img_id, 'full');
        $logo_url = $this->Options->getOption('_logo_img');
        $title_enable = $this->Options->getOption('_title_enable');

        if (empty($img_url)) {
            wp_send_json([
                'status' => 'error',
                'text' => 'the image is missing'
            ]);
        }

        $textObj = $this->getTextData($post_id, $title_enable);

        $backgroundObj = $this->getBackgroundData();

        $result = [
            "sizes" => [ ["x" => 1024, "y" => 456], ["x" => 1200, "y" => 630], ["x" => 1200, "y" => 630] ],
            "text" => $textObj,
            "background" => $backgroundObj,
            'post_id' => $post_id,
        ];

        if (!empty($logo_url)) {
            $result["images"] = [
                "logo" => $logo_url,
                "background_image" => $img_url
            ];
        }

        return $result;
    }

    /**
     * Prepare data for next request
     * @param $prepared_args
     * @param bool $silence
     * @return mixed
     */
    public function prepare_for_generate($prepared_args, $silence = false)
    {

        $target_post = $prepared_args['post_id'];
        $prepared_args = json_encode($prepared_args);
        $callback = $this->Remotes->send_to_create_img_oie95($prepared_args);

        try {
            $callback_body = json_decode($callback['body'], true);

            if ($callback['response']['code'] !== 201) {
                $message = (isset($callback_body['message'])) ? $callback_body['message'] : __('Error has not description', OGGWC_DOMAIN);
                if (!$silence) {
                    wp_send_json_error($message, 400);
                }
            }

            $imagesUrl = [$target_post];

            foreach ($callback_body['images'] as $images) {
                $imagesUrl[] = $images['url'];
            }

            $this->WaitResult->event_save_images_single_post($imagesUrl);

            return $callback_body;
        } catch (\Exception $e) {
            Logger::log(Logger::ERROR, $e);
            return [$e];
        }
    }

    /**
     * if post saved
     *
     * We can't use request funcs because it will reason for the post_save delay
     *
     * @param $post_id
     * @since 1.0
     */
    public function generate_post_save( $post_id )
    {
        if (has_post_thumbnail($post_id)) {
            try {
                $post_data = $this->getPreparedPostData($post_id);
                $this->prepare_for_generate($post_data, true);
            } catch (\Exception $e) {
                //silence
            }
        }
    }
}