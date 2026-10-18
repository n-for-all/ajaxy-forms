<?php

namespace Ajaxy\Forms\Inc\Actions;

use Ajaxy\Forms\Inc\Helper;
use Ajaxy\Forms\Inc\TemplateField;

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
        if (!$this->key || !$this->token) {
            throw new \Exception('Twilio credentials are required: account_sid and auth_token are required');
        }
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

    /**
     * Hand the message to Twilio
     *
     * Twilio answers 2xx once it has accepted the message. The "status" it
     * returns in the body is the delivery state, queued or sent, never an
     * http code, so reading it as one made every successful send look like
     * a failure. The http status is what decides.
     *
     * @param string $accountSid
     * @param array  $data
     * @param string $authToken
     *
     * @throws \Exception when Twilio refuses the message
     *
     * @return void
     */
    public function sendCurlMessage($accountSid, $data, $authToken)
    {
        $url = 'https://api.twilio.com/2010-04-01/Accounts/' . \rawurlencode($accountSid) . '/Messages.json';

        $response = \wp_remote_post($url, [
            'timeout' => 15,
            'headers' => [
                'Authorization' => 'Basic ' . \base64_encode($accountSid . ':' . $authToken),
            ],
            'body' => $data,
        ]);

        if (\is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $code = (int)\wp_remote_retrieve_response_code($response);
        $body = \json_decode(\wp_remote_retrieve_body($response), true);

        if ($code < 200 || $code >= 300) {
            throw new \Exception(
                !empty($body['message'])
                    ? $body['message']
                    : \sprintf('Twilio answered with %d', $code)
            );
        }
    }
    public function execute($data, $form)
    {
        $values = [
            'data' => TemplateField::from_data(Helper::parse_submit_data($data, $form)),
        ];
        $to = Helper::create_twig_template($this->to, $values);

        $message = $this->parseMessage(Helper::create_twig_template($this->message, $values));
        try {
            $data = array(
                'From' => $this->from,
                'To' => $to,
                'Body' => $message
            );
            $this->sendCurlMessage($this->key, $data, $this->token);
        } catch (\Exception $e) {
            \error_log(\sprintf('SMS/Whatsapp action failed to sent to %s with error %s - %s',   $this->to, $e->getCode(), $e->getMessage()));
            throw new \Exception(\esc_html(\sprintf('SMS/Whatsapp action failed to sent to %s with error %s - %s',   $this->to, $e->getCode(), $e->getMessage())));
        }
    }
    public static function get_properties()
    {
        return [
            [
                'label' => 'To Number',
                'type' => 'text',
                'name' => 'to',
                'required' => true,
                'help' => 'Enter the phone number to send the message to'
            ],
            [
                'label' => 'From Number',
                'type' => 'text',
                'name' => 'from',
                'required' => true,
                'help' => 'Enter the phone number to send the message from (Twilio Number)'
            ],
            [
                'label' => 'Message',
                'type' => 'textarea',
                'name' => 'message',
                'required' => true,
                'help' => 'Enter the message to send'
            ],
            [
                'label' => 'Twilio Key',
                'type' => 'text',
                'secret' => true,
                'name' => 'key',
                'required' => true,
                'help' => 'Enter the Twilio API Key'
            ],
            [
                'label' => 'Twilio Token',
                'type' => 'text',
                'secret' => true,
                'name' => 'token',
                'required' => true,
                'help' => 'Enter the Twilio API Token'
            ],
            [
                'label' => 'Bitly Token',
                'type' => 'text',
                'secret' => true,
                'name' => 'bitly_token',
                'help' => 'Enter the Bitly API Token to shorten the URLs if any URLs are used'
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
        if (!$message || !$this->bitly_token) {
            return $message;
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
