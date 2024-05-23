<?php

namespace Ajaxy\Forms\Inc\Actions;

class Whatsapp extends Sms
{
    public function __construct($options)
    {
        parent::__construct($options);

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
        $this->from = 'whatsapp:' . \trim($this->from);
        $this->to = 'whatsapp:' . \trim($this->to);

        return parent::execute($data, $form);
    }
}
