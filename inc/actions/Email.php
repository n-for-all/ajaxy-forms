<?php

namespace Ajaxy\Forms\Inc\Actions;

use Ajaxy\Forms\Inc\Helper;

class Email implements ActionInterface
{
    private $to;
    private $from;
    private $subject;
    private $message;
    private $headers;

    public function __construct($options)
    {
        $this->to = $options['to'];
        if (empty($this->to)) {
            throw new \Exception('To email address is required to send an email');
        }

        $this->from = $options['from'] ?? get_option('admin_email');
        $this->subject = $options['subject'] ?? '';
        $this->message = $options['message'] ?? '';
        $this->headers = $this->parse_headers((string)$options['headers']);
    }

    public function get_name()
    {
        return 'email';
    }

    private function parse_headers(string $headers)
    {
        $splitted = explode("\n", str_replace("\r\n", "\n", $headers));
        $parsed = [];
        foreach ($splitted as $header) {
            $parts = explode(':', $header);
            if (count($parts) === 2) {
                $parsed[] = $parts[0] . ": " . \trim((string)$parts[1]);
            }
        }
        return $parsed;
    }
    public function get_title()
    {
        return 'Email';
    }

    public function execute($data, $form)
    {
        if (!empty($this->from)) {
            $this->headers[] = 'From: ' . \trim((string)$this->from);
        }

        $this->headers[] = 'Content-Type: text/html; charset=UTF-8';

        $values = [
            'data' => $data,
        ];

        $to = Helper::create_twig_template($this->to, $values);
        $subject = Helper::create_twig_template($this->subject, $values);
        $message = Helper::create_twig_template(
            $this->message,
            [
                'data' => $data,
                'table' => Helper::create_table($data, $form)
            ]
        );

        $sent = \wp_mail($to, $subject, $message, $this->headers);

        if (!$sent) {
            \error_log('Email action failed to send to ' . $to);
            throw new \Exception('Failed to send email');
        }
    }

    public static function get_properties()
    {
        return [
            [
                "order" => 0,
                "label" => "From",
                "type" => "email",
                "name" => "from",
                "required" => true,
                "help" => "Enter the email address to send the email from",
            ],
            [
                "order" => 1,
                "label" => "To",
                "type" => "text",
                "name" => "to",
                "required" => true,
                "help" => "Enter the email address to send the email to",
            ],
            [
                "order" => 2,
                "label" => "Subject",
                "type" => "text",
                "name" => "subject",
                "required" => true,
                "help" => "Enter the subject of the email: you can use {{data.FIELD_NAME}} to display the field data",
            ],
            [
                "order" => 3,
                "label" => "Message",
                "type" => "textarea",
                "name" => "message",
                "help" => "Enter the message of the email: use {{table}} or {{data.FIELD_NAME}} to display the data",
            ],
            [
                "order" => 4,
                "type" => "separator",
                "name" => "html",
            ],
            [
                "order" => 4,
                "label" => "Additional Headers",
                "type" => "textarea",
                "name" => "headers",
                "default" => "",
                "help" => "Add some headers, ex. <b>cc: john@test.com</b>",
            ],
            [
                "order" => 4,
                "label" => "Send as plain text",
                "type" => "select",
                "name" => "html",
                "default" => "0",
                "options" => [
                    "1" => "Yes",
                    "0" => "No",
                ],
                "help" => "Send the email as plain text or html",
            ]
        ];
    }
}
