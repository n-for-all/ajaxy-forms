<?php

namespace Ajaxy\Forms\Inc\Actions;

use Ajaxy\Forms\Inc\Helper;
use Ajaxy\Forms\Inc\TemplateField;

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

        $parsed = Helper::parse_submit_data($data, $form);
        $values = [
            'data' => TemplateField::from_data($parsed),
        ];

        $to = Helper::create_twig_template($this->to, $values);
        $subject = Helper::create_twig_template($this->subject, $values);

        // The message used to be handed the raw submission while the subject
        // got the parsed one, so the {{data[FIELD].value}} the help asks for
        // came out empty here. Same shape in all three now.
        $message = Helper::create_twig_template(
            $this->message,
            $values + ['table' => Helper::create_table($parsed, $form)]
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
                "help" => "Enter the single email address to send the email from",
            ],
            [
                "order" => 1,
                "label" => "To",
                "type" => "text",
                "name" => "to",
                "required" => true,
                "help" => "Enter the email address to send the email to. Separate several with a comma, a name can be included as in Ops Team <ops@example.com>. Twig is supported, so {{data[FIELD_NAME].value}} sends to an address the visitor submitted",
            ],
            [
                "order" => 2,
                "label" => "Subject",
                "type" => "text",
                "name" => "subject",
                "required" => true,
                "help" => "Enter the subject of the email: you can use {{data[FIELD_NAME].value}} to display the field value data or {{data[FIELD_NAME].value_label}} to display the field data label",
            ],
            [
                "order" => 3,
                "label" => "Message",
                "type" => "textarea",
                "name" => "message",
                "help" => "Enter the message of the email: use {{table}} or {{data[FIELD_NAME].value}} to display the field value data or {{data[FIELD_NAME].value_label}} to display the field data label, you can also use {{data[FIELD_NAME].type}} or {{data[FIELD_NAME].label}} to display the field type and label, supports twig language",
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
                "help" => "One header per line, ex. cc: john@test.com or bcc: archive@test.com",
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
