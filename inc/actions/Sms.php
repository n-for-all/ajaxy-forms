<?php

namespace Ajaxy\Forms\Inc\Actions;

//create a class to send email notification from form data via wordpress
class Sms implements ActionInterface
{
    protected $to;
    protected $from;
    protected $message;
    protected $bitly_token;
    protected $key;
    protected $token;

    public function __construct($options)
    {
        $this->to = $options['to'];
        if (empty($this->to)) {
            throw new \Exception('To phone number is required');
        }

        $this->from = $options['from'];
        $this->message = $options['message'] ?? '';
        if (empty($this->message)) {
            throw new \Exception('Message is required');
        }

        $this->key = $options['key'] ?? '';
        $this->token = $options['token'] ?? '';

        $this->bitly_token = $options['bitly_token'] ?? null;
    }

    public function get_name()
    {
        return 'sms';
    }

    public function get_title()
    {
        return 'SMS';
    }

    public function execute($data, $form)
    {
        $message = $this->parseMessage($this->message);
        try {
            $client = new \Twilio\Rest\Client($this->key, $this->token);
            $client->messages->create(
                $this->to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );
        } catch (\Twilio\Exceptions\TwilioException $e) {
            \error_log(\sprintf('SMS action failed to sent to %s with error %s - %s',   $this->to, $e->getCode(), $e->getMessage()));
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
            ]
        ];
    }

    /**
     * Parse SMS Message
     *
     * @date 2022-05-20
     *
     * @param string $message
     *
     * @return void
     */
    public function parseMessage($message)
    {
        if (!$message) {
            return '';
        }
        $matches = [];
        preg_match_all(
            '/\(?(?:(http|https|ftp):\/\/)?(?:((?:[^\W\s]|\.|-|[:]{1})+)@{1})?((?:www.)?(?:[^\W\s]|\.|-)+[\.][^\W\s]{2,4}|localhost(?=\/)|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::(\d*))?([\/]?[^\s\?]*[\/]{1})*(?:\/?([^\s\n\?\[\]\{\}\#]*(?:(?=\.)){1}|[^\s\n\?\[\]\{\}\.\#]*)?([\.]{1}[^\s\?\#]*)?)?(?:\?{1}([^\s\n\#\[\]]*))?([\#][^\s\n]*)?\)?/',
            $message,
            $matches
        );
        if (isset($matches[0]) && count($matches[0]) > 0) {
            foreach ($matches[0] as $url) {
                $shortened = $this->shorten(str_replace('#', '%23', $url));
                if ($shortened) {
                    $message = str_ireplace($url, $shortened, $message);
                }
            }
        }

        return $message;
    }

    public function shorten($url)
    {
        if (!$this->bitly_token) {
            return $url;
        }
        $response = wp_remote_get('https://api-ssl.bitly.com/v3/shorten?access_token=' . $this->bitly_token . '&longUrl=' . urlencode($url));
        if (is_wp_error($response)) {
            return $url;
        }
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);
        if (isset($data->data->url)) {
            return $data->data->url;
        }

        return $url;
    }
}
