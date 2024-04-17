<?php

namespace Ajaxy\Forms\Inc\Notifications;

class WebhookNotification implements NotificationInterface
{
    private $url;

    public function __construct($options)
    {
        $this->url = $options['url'];
        if (empty($this->url)) {
            throw new \Exception('Url is required to send a webhook notification');
        }
    }

    public function send($form, $data)
    {
        $response = wp_remote_post($this->url, [
            'body' => $data,
        ]);
        if (\is_wp_error($response)) {
            \error_log(\sprintf('Webhook notification failed to sent to %s with error %s - %s', $this->url, $response->get_error_message(), $response->get_error_message()));
        }
    }
}
