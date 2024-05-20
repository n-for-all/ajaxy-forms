<?php

namespace Ajaxy\Forms\Inc\Actions;

interface ActionInterface
{
    public function execute($data, $form);
    public function get_properties($values = []);
    public function get_name();
    public function get_title();
}
