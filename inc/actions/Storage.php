<?php

namespace Ajaxy\Forms\Inc\Actions;

use Ajaxy\Forms\Inc\Data;
use Ajaxy\Forms\Inc\Form;
use Ajaxy\Forms\Inc\Helper;

class Storage implements ActionInterface
{
    public function __construct($options) {}

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
        $nData = Helper::parse_submit_data($data, $form);
        Data::add($form->get_name(), $nData);
    }

    public static function get_properties()
    {
        return [];
    }
}
