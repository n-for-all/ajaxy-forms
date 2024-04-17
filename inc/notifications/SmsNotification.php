<?php

namespace Ajaxy\Forms\Inc\Notifications;

//create a class to send email notification from form data via wordpress
class SmsNotification implements NotificationInterface
{
    private $to;
    private $from;
    private $message;
    private $bitly_token;

    public function __construct($options)
    {
        $this->to = $options['to'];
        if (empty($this->to)) {
            throw new \Exception('To email address is required to send an email notification');
        }

        $this->from = $options['from'] ?? get_option('admin_email');
        $this->message = $options['message'] ?? '';
        $this->key = $options['key'] ?? '';
        $this->token = $options['token'] ?? '';

        $this->bitly_token = $options['bitly_token'] ?? null;
    }

    public function send($form, $data)
    {
        $message = $this->parseMessage($this->message);
        try {
            $client = new Twilio\Rest\Client($this->key, $this->token);
            $client->messages->create(
                $this->to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );
        } catch (TwilioException $e) {
            \error_log(\sprintf('SMS notification failed to sent to %s with error %s - %s',   $this->to, $e->getCode(), $e->getMessage()));
        }
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
