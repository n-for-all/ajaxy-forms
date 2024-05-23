<?php

namespace Ajaxy\Forms\Inc;

class Form
{
    private $name;
    private $fields;
    private $actions;
    private $options;
    private $initial_data;
    private $ajax = false;

    public function __construct($name, $fields, $options = [], $actions = [], $initial_data = null)
    {
        $this->set_name($name);
        $this->set_fields($fields);
        $this->set_options($options);
        $this->set_actions($actions);
        $this->set_initial_data($initial_data);
    }

    public function get_name()
    {
        return $this->name;
    }
    public function set_name(string $name)
    {
        $this->name = $name;
    }

    public function get_fields()
    {
        return $this->fields;
    }

    public function set_fields(array $fields)
    {
        if ($fields) {
            foreach ($fields as &$field) {
                if (isset($field['label_html'])) {
                    $field['label_html'] = $field['label_html'] == '1';
                }
                if (isset($field['help_html'])) {
                    $field['help_html'] = $field['help_html'] == '1';
                }

                foreach ((array)$field as $key => $v) {
                    if (\in_array($key, ['attr', 'help_attr', 'row_attr', 'label_attr'])) {
                        $attributes = [];
                        foreach ((array)$v as $attribute => $value) {
                            if (isset($value['name'])) {
                                $attributes[$value['name']] = $value['value'];
                            } else if (\is_string($attribute)) {
                                $attributes[$attribute] = $value;
                            }
                        }

                        $field[$key] = $attributes;
                    }
                }
            }
        }
        $this->fields = $fields ?? [];
    }

    public function get_actions()
    {
        return $this->actions;
    }

    public function set_actions(array $actions)
    {
        $this->actions = $actions ?? [];
    }

    public function add_action($name, $action)
    {
        $this->actions[$name] = $action;
    }

    public function get_initial_data()
    {
        return $this->initial_data;
    }

    public function set_initial_data(array $initial_data = null)
    {
        $this->initial_data = $initial_data ?? [];
    }

    public function get_options()
    {
        return $this->options;
    }

    public function get_option($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    public function is_ajax()
    {
        return $this->ajax;
    }

    public function set_options(array $options)
    {
        if ($options) {
            $submission = $options['submission'] ?? null;
            if ($submission != "1") {
                if (!isset($options['attr']['class'])) {
                    $options['attr']['class'] = '';
                }
                $options['attr']['class'] .= " is-ajax";

                $this->ajax = true;
            }
            unset($options['submission']);
        } else {
            $options = [];
            $options['attr'] = [];
            $options['attr']['class'] = 'is-ajax';

            $this->ajax = true;
        }
        $this->options = $options ?? [];
    }

    public function get_message($name = 'success')
    {
        $messages = $this->get_option('messages', []);
        return $messages[$name] ?? null;
    }
}
