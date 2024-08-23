<?php

namespace Ajaxy\Forms\Inc\Types\Loader;

use Symfony\Component\Form\ChoiceList\Loader\AbstractChoiceLoader;

class DummyChoiceLoader extends AbstractChoiceLoader
{
    private $placeholder;
    public function __construct($placeholder)
    {
        $this->placeholder = $placeholder;
    }
    public function loadChoices(): iterable
    {
        if($this->placeholder) {
            return [$this->placeholder => ''];
        }
        return [];
    }

    public function loadChoicesForValues(array $values, $value = null): array
    {
        $choices = array();
        foreach ($values as $key => $val) {
            if (is_callable($value)) {
                $choices[$key] = (string)call_user_func($value, $val, $key);
            } else {
                $choices[$key] = $val;
            }
        }

        return $choices;
    }
}
