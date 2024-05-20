<?php

namespace Ajaxy\Forms\Inc\Actions;

class Telegram extends Sms
{
    private $token;
    private $channel_id;

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

        parent::__construct($options);
    }

    public function get_name()
    {
        return 'telegram';
    }

    public function get_title()
    {
        return 'Telegram';
    }

    public function execute($data, $form)
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

    public function get_properties($values = [])
    {
        return [
            'token' => [
                'label' => 'Token',
                'value' => $this->token,
                'type' => 'text'
            ],
            'channel_id' => [
                'label' => 'Channel Id',
                'value' => $this->channel_id,
                'type' => 'text'
            ],
            'message' => [
                'label' => 'Message',
                'value' => $this->message,
                'type' => 'textarea'
            ],
        ];
    }
}
