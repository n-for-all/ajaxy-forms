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

    /**
     * Create a new form instance
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param array $fields
     * @param array  $options
     * @param array  $actions
     * @param array $initial_data
     */
    public function __construct($name, $fields, $options = [], $actions = [], $initial_data = null)
    {
        $this->set_name($name);
        $this->set_fields($fields);
        $this->set_options($options);
        $this->set_actions($actions);
        $this->set_initial_data($initial_data);
    }

    /**
     * Get form name
     *
     * @date 2024-05-24
     *
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * Set form name
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return void
     */
    public function set_name(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the form fields
     *
     * @date 2024-05-24
     *
     * @return array
     */
    public function get_fields()
    {
        return $this->fields;
    }

    /**
     * Set the form fields
     *
     * @date 2024-05-24
     *
     * @param array $fields
     *
     * @return void
     */
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

    /**
     * Get the form actions to execute
     *
     * @date 2024-05-24
     *
     * @return array
     */
    public function get_actions()
    {
        return $this->actions;
    }

    /**
     * Set the form actions
     *
     * @date 2024-05-24
     *
     * @param array $actions
     *
     * @return void
     */
    public function set_actions(array $actions)
    {
        $this->actions = $actions ?? [];
    }

    /**
     * Add a form action
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param array $action
     *
     * @return void
     */
    public function add_action($name, $action)
    {
        $this->actions[$name] = $action;
    }

    /**
     * Get the initial data to populate the form
     *
     * @date 2024-05-24
     *
     * @return array|null
     */
    public function get_initial_data()
    {
        return $this->initial_data;
    }

    /**
     * Set the initial data to populate the form
     *
     * @date 2024-05-24
     *
     * @param array|null $initial_data
     *
     * @return void
     */
    public function set_initial_data(array $initial_data = null)
    {
        $this->initial_data = $initial_data ?? [];
    }

    /**
     * Get form options
     *
     * @date 2024-05-24
     *
     * @return array
     */
    public function get_options()
    {
        return $this->options;
    }

    /**
     * Get a form option value
     *
     * @date 2024-05-24
     *
     * @param string $key
     * @param string $default
     *
     * @return array|string|null
     */
    public function get_option($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * Check if the form settings are set to submit via ajax
     *
     * @date 2024-05-24
     *
     * @return boolean
     */
    public function is_ajax()
    {
        return $this->ajax;
    }

    /**
     * Set the form options
     *
     * @date 2024-05-24
     *
     * @param array $options
     *
     * @return void
     */
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

    /**
     * Get form message from options
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function get_message($name = 'success')
    {
        $messages = $this->get_option('messages', []);
        return $messages[$name] ?? null;
    }
}
