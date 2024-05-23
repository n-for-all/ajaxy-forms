<?php

namespace Ajaxy\Forms\Inc\Actions;

class Whatsapp extends Sms
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->from = $options['from'] ?? null;
        if (empty($this->from)) {
            throw new \Exception('From phone number is required to send a whatsapp notification');
        }
    }

    public function get_name()
    {
        return 'whatsapp';
    }

    public function get_title()
    {
        return 'Whatsapp';
    }

    public function execute($data, $form)
    {
        $message = $this->parseMessage($this->message);
        try {
            $client = new \Twilio\Rest\Client($this->key, $this->token);
            $client->messages->create(
                'whatsapp:' . $this->to,
                [
                    'from' => 'whatsapp:' . $this->from,
                    'body' => $message
                ]
            );
        } catch (\Twilio\Exceptions\TwilioException $e) {
            \error_log(\sprintf('Whatsapp notification failed to sent to %s with error %s - %s',   $this->to, $e->getCode(), $e->getMessage()));
        }
    }

    public static function get_properties()
    {
        return [
            [
                'label' => 'To',
                'name' => 'to',
                'required' => true,
                'type' => 'text'
            ],
            [
                'label' => 'From',
                'name' => 'from',
                'required' => true,
                'type' => 'text'
            ],
            [
                'label' => 'Message',
                'name' => 'message',
                'type' => 'textarea'
            ],
            [
                'label' => 'Twilio Key',
                'name' => 'key',
                'required' => true,
                'type' => 'text'
            ],
            [
                'label' => 'Twilio Token',
                'name' => 'token',
                'required' => true,
                'type' => 'text'
            ],
            [
                'label' => 'Bitly Token',
                'name' => 'bitly_token',
                'type' => 'text'
            ],
        ];
    }
}
