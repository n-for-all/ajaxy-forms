<?php

namespace Ajaxy\Forms\Inc\Actions;

use Ajaxy\Forms\Inc\Form;

interface ActionInterface
{
    public function execute($data, Form $form);
}
