<?php

namespace Ajaxy\Forms\Inc\Notifications;

class TelegramNotification extends SmsNotification
{
    private $token;

    public function __construct($options)
    {
        $this->token = $options['token'];
        $this->channel_id = $options['channel_id'];
        if (empty($this->token)) {
            throw new \Exception('Token is required to send a telegram notification');
        }
        if (empty($this->channel_id)) {
            throw new \Exception('Channel Id is required to send a telegram notification');
        }
    }

    public function send($form, $data)
    {
        // send telegram notification
        $message = $this->parseMessage($this->message);

        $query = http_build_query([
            'chat_id' => $this->channel_id,
            'text' => $message,
        ]);

        try {
            $url = 'https://api.telegram.org/bot' . $this->token . '/sendMessage?' . $query;
            $response = \wp_remote_get($url);
            if (\is_wp_error($response)) {
                throw new \Exception($response->get_error_message());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
