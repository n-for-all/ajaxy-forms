<?php

/**
 * Register a new form
 *
 * @date 2024-04-10
 *
 * @param string $form_name The name of the form
 * @param array $fields
 * @param array $options
 * @param mixed $initial_data
 *
 * @return void
 */
function register_form($form_name, $fields, $options = [], $initial_data = null)
{
    \Ajaxy\Forms\Plugin::init()->register($form_name, $fields, $options, $initial_data);
}

/**
 * Register a form action
 *
 * @date 2024-04-10
 *
 * @param string $form_name
 * @param string $action_name
 * @param array $options
 *
 * @return void
 */
function register_form_action($form_name, $action_name, $options = [])
{
    \Ajaxy\Forms\Plugin::init()->register_action($form_name, $action_name, $options);
}

/**
 * Register a form field type
 *
 * @date 2024-05-19
 *
 * @param string $type
 * @param array $options
 *
 * @return void
 */
function register_form_field($type, $options)
{
    \Ajaxy\Forms\Plugin::init()->register_field($type, $options);
}

/**
 * Register a form action type
 *
 * @date 2024-05-19
 *
 * @param string $type
 * @param array $options
 *
 * @return void
 */
function register_form_action_type($type, $options)
{
    \Ajaxy\Forms\Plugin::init()->register_action_type($type, $options);
}