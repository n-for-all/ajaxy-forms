<?php

namespace Ajaxy\Forms\Inc\Notifications;

//create a class to send email notification from form data via wordpress
class WhatsappNotification extends SmsNotification
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->from = $options['from'] ?? null;
        if (empty($this->from)) {
            throw new \Exception('From phone number is required to send a whatsapp notification');
        }
    }

    public function send($form, $data)
    {
        $message = $this->parseMessage($this->message);
        try {
            $client = new Twilio\Rest\Client($this->key, $this->token);
            $client->messages->create(
                'whatsapp:' . $this->to,
                [
                    'from' => 'whatsapp:' . $this->from,
                    'body' => $message
                ]
            );
        } catch (TwilioException $e) {
            \error_log(\sprintf('Whatsapp notification failed to sent to %s with error %s - %s',   $this->to, $e->getCode(), $e->getMessage()));
        }
    }
}
