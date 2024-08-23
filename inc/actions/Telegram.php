<?php

namespace Ajaxy\Forms\Inc\Actions;


use Ajaxy\Forms\Inc\Helper;

class Telegram implements ActionInterface
{
    private $token;
    private $chat_id;
    private $message;

    public function __construct($options)
    {
        $this->token = $options['bot_token'];
        $this->chat_id = $options['chat_id'];
        $this->message = $options['message'];
        if (empty($this->token)) {
            throw new \Exception('Bot Token is required to send a telegram notification');
        }
        if (empty($this->chat_id)) {
            throw new \Exception('Chat Id is required to send a telegram notification');
        }
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
        $values = [
            'data' => $data,
        ];

        $message = Helper::create_twig_template($this->message, $values);

        $query = [
            'chat_id' => $this->chat_id,
            'text' => $message,
        ];

        try {
            $url = 'https://api.telegram.org/bot' . $this->token . '/sendMessage?' . $query;
            $response = \wp_remote_post(
                $url,
                [
                    'body' => $query,
                ]
            );
            if (\is_wp_error($response)) {
                throw new \Exception($response->get_error_message());
            }
        } catch (\Exception $e) {
            throw new \Exception(\esc_html($e->getMessage()));
        }
    }

    public static function get_properties()
    {
        return [
            [
                'label' => 'Bot Token',
                'name' => 'bot_token',
                'required' => true,
                'type' => 'text'
            ], [
                'label' => 'Chat Id',
                'name' => 'chat_id',
                'required' => true,
                'type' => 'text'
            ], [
                'label' => 'Message',
                'name' => 'message',
                'type' => 'textarea'
            ],
        ];
    }
}
