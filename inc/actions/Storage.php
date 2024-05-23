<?php

namespace Ajaxy\Forms\Inc\Actions;

use Ajaxy\Forms\Inc\Data;
use Ajaxy\Forms\Inc\Form;

class Storage implements ActionInterface
{
    public function __construct($options)
    {
    }

    public function get_name()
    {
        return 'storage';
    }

    public function get_title()
    {
        return 'Storage';
    }

    public function execute($data, Form $form)
    {
        $not_allowed = ['_message', '_token'];
        $nData = array_filter($data, function ($k) use ($not_allowed) {
            return !in_array($k, $not_allowed);
        }, ARRAY_FILTER_USE_KEY);
        Data::add($form->get_name(), $nData);
    }

    public static function get_properties()
    {
        return [];
    }
}
