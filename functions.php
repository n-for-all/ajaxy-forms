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
 * Register a form notification
 *
 * @date 2024-04-10
 *
 * @param string $form_name
 * @param string $type
 * @param string $options
 *
 * @return void
 */
function register_form_notification($form_name, $type, $options)
{
    \Ajaxy\Forms\Plugin::init()->register_notification($form_name, $type, $options);
}

/**
 * Register the form storage
 *
 * @date 2024-04-11
 *
 * @param string $form_name
 * @param string $options
 *
 * @return void
 */
function register_form_storage($form_name, $options = [])
{
    \Ajaxy\Forms\Plugin::init()->register_storage($form_name, $options);
}
