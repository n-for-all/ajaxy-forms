<?php

namespace Ajaxy\Forms\Inc\Actions;

//create a class to send email notification from form data via wordpress
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
        $this->headers = (array)$options['headers'] ?? [];
    }

    public function get_name()
    {
        return 'email';
    }

    public function get_title()
    {
        return 'Email';
    }

    public function execute($data, $form)
    {
        if (!empty($this->from)) {
            $this->headers[] = 'From: ' . $this->from;
        }
        $sent = \wp_mail($this->to, $this->subject, $this->message, $this->headers);
        if (!$sent) {
            \error_log('Email action failed to send to ' . $this->to);
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
            'subject' => [
                'label' => 'Subject',
                'value' => $this->subject,
                'type' => 'text'
            ],
            'message' => [
                'label' => 'Message',
                'value' => $this->message,
                'type' => 'textarea'
            ],
            // 'headers' => $this->headers
        ];
    }
}
