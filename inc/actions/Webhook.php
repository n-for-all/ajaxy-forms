<?php

namespace Ajaxy\Forms\Inc\Actions;

class Webhook implements ActionInterface
{
    private $url;

    public function __construct($options)
    {
        $this->url = $options['url'];
        if (empty($this->url)) {
            throw new \Exception('Url is required to send a webhook notification');
        }
    }

    public function get_name()
    {
        return 'webhook';
    }

    public function get_title()
    {
        return 'Webhook';
    }

    public function execute($data, $form)
    {
        $response = wp_remote_post($this->url, [
            'body' => $data,
        ]);
        if (\is_wp_error($response)) {
            \error_log(\sprintf('Webhook notification failed to sent to %s with error %s - %s', $this->url, $response->get_error_message(), $response->get_error_message()));
        }
    }

    public function get_properties($values = [])
    {
        return [
            'url' => [
                'label' => 'Url',
                'value' => $this->url,
                'type' => 'text'
            ],
        ];
    }
}
