<?php

namespace Ajaxy\Forms\Inc\Actions;

//create a class to send email notification from form data via wordpress
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

    public function get_properties($values = [])
    {
        return [
            'to' => [
                'label' => 'To',
                'value' => $this->to,
                'type' => 'text'
            ],
            'from' => [
                'label' => 'From',
                'value' => $this->from,
                'type' => 'text'
            ],
            'message' => [
                'label' => 'Message',
                'value' => $this->message,
                'type' => 'textarea'
            ],
            'key' => [
                'label' => 'Twilio Key',
                'value' => $this->key,
                'type' => 'text'
            ],
            'token' => [
                'label' => 'Twilio Token',
                'value' => $this->token,
                'type' => 'text'
            ],
            'bitly_token' => [
                'label' => 'Bitly Token',
                'value' => $this->bitly_token,
                'type' => 'text'
            ],
        ];
    }
}
