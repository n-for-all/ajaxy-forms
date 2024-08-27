<?php

/**
 * @package Ajaxy
 */
/*
	Plugin Name: Ajaxy Forms
	Plugin URI: https://ajaxy.org/product/ajaxy-forms
	Description: Enhanced WordPress forms with advanced features and integrations
	Version: 1.0.0
	Author: Naji Amer (Ajaxy)
	Author URI: https://www.ajaxy.org
	License: GPLv2 or later
    License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
    Requires PHP: 7.4
*/


namespace Ajaxy\Forms;

require 'vendor/autoload.php';
require 'functions.php';

use Ajaxy\Forms\Inc\Constraints;
use Ajaxy\Forms\Inc\Data;
use Ajaxy\Forms\Inc\Form;
use Ajaxy\Forms\Inc\Helper;
use Ajaxy\Forms\Inc\Types\Transformer\UploadedFileTransformer;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Validation;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use WP_Error;

define("AJAXY_FORMS_PLUGIN_URL", plugins_url('', __FILE__));
define("AJAXY_FORMS_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("AJAXY_FORMS_BASENAME", plugin_basename(__FILE__));
define("AJAXY_FORMS_VERSION", "1.0.0");

class Plugin
{
    public const DB_VERSION = '1.0.0';
    public static $instance = null;

    private $settings = null;
    private $entry_settings = null;
    private $builder = null;
    private $csrf_token_manager = null;



    /**
     * Init the plugin
     *
     * @date 2024-04-10
     *
     * @return self
     */
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {

        spl_autoload_register(
            function ($class_name) {
                if (\stripos($class_name, __NAMESPACE__ . '\\') === 0) {
                    $class_name = dirname(__FILE__) . '/' . str_ireplace([__NAMESPACE__ . '\\', '\\'], ['', '/'], $class_name) . '.php';
                    if (!file_exists($class_name)) {
                        $classes = explode('/', $class_name);
                        $name = ucwords($classes[count($classes) - 1]);

                        $classes = array_map('strtolower', $classes);
                        $classes[count($classes) - 1] = $name;
                        $class_name = implode('/', $classes);
                    }

                    include_once $class_name;
                }
            }
        );


        if (is_admin()) {
            $this->settings = new Admin\Inc\Form\Settings();
            $this->entry_settings = new Admin\Inc\Entry\Settings();
            $this->builder = new Admin\Inc\Form\Builder();
        }

        $this->csrf_token_manager = new CsrfTokenManager();
        $this->actions();
        $this->filters();

        \add_shortcode('form', [$this, 'shortcode']);
    }

    public function shortcode($atts)
    {
        $atts = shortcode_atts(
            array(
                'name' => ''
            ),
            $atts,
            'ajaxy-forms'
        );

        $name = $atts['name'] ?? '';

        if ('' === $name) {
            return '';
        } else {
            $form = Data::parse_form($name);
            if ($form) {
                $parsed_form = $this->create_form($form, $form->get_initial_data());
                if (is_wp_error($parsed_form)) {
                    return \sprintf(__('Error creating form for %1$s: %2$s', "ajaxy-forms"), $name, $parsed_form->get_error_message());
                }
                $this->on_submit($name, $parsed_form);
                return $this->render($name, $parsed_form, $form->get_theme());
            } else {
                return \sprintf(__('Form %s not found', "ajaxy-forms"), $name);
            }
        }
    }

    public function create_form(Form $form, $initial_data = null)
    {

        // Set up the Validator component
        $validator = Validation::createValidator();

        // Set up the Form component
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new CsrfExtension($this->csrf_token_manager))
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory();


        $options = \array_replace([
            'csrf_protection' => false,
            'csrf_message' => __('It appears you\'ve already submitted this form, or it may have timed out. Please refresh the page and try again.', "ajaxy-forms"),
            'csrf_token_id'   => 'form_intention_' . $form->get_name(),
            'allow_extra_fields' => true,
            'required' => false,
        ], $form->get_options());

        unset($options['messages']);

        if (isset($options['attr']['class'])) {
            $options['attr']['class'] .= ' ajaxy-form';
        } else {
            $options['attr']['class'] = 'ajaxy-form';
        }

        $options['action'] = $form->is_ajax() ? get_rest_url(null, sprintf('ajaxy-forms/v1/form/%s', $form->get_name())) : '';
        $builder = $formFactory->createNamedBuilder($form->get_name(), Type\FormType::class, $initial_data, $options);

        $start_repeater = '';
        $repeater = [];
        foreach ($form->get_fields() as $field) {
            $field_options = $field;

            $field['constraints'] = $field['constraints'] ?? [];
            if (isset($field['required']) && $field['required'] == "1") {
                $field['constraints'][] = ['type' => 'not_blank', 'message' => $field_options['invalid_message'] ?? __('This field is required.')];
                $field['required'] = '';
            }

            if (!empty($field['constraints'])) {
                $field_options['constraints'] = \array_filter(\array_map(function ($constraint) {
                    return Constraints::getInstance()->create_constraint($constraint);
                }, $field['constraints'] ?? []), function ($constraint) {
                    return !is_null($constraint);
                });
            } else {
                unset($field_options['constraints']);
            }

            if (!empty($field['attr']) || !empty($field['attributes'])) {
                $field_options['attr'] = $field['attr'] ?? $field['attributes'];
            }

            if (isset($field_options['rounding_mode'])) {
                $field_options['rounding_mode'] = intval($field_options['rounding_mode']);
            }

            if (isset($field_options['html5'])) {
                $field_options['html5'] = \boolval($field_options['html5']);
            }

            if ($field['type'] == 'number') {
                $field_options['input'] = 'string';
                $field_options['data'] = '0';
            }

            if ($field['type'] == 'checkbox') {
                if (isset($field_options['checked'])) {
                    if ($field_options['checked'] == "1") {
                        $field_options['data'] = true;
                    }
                    unset($field_options['checked']);
                }
            }

            if ($field['type'] == 'choice') {
                if (isset($field_options['choices']) && is_array($field_options['choices'])) {
                    array_walk($field_options['choices'], function (&$choice) {
                        if ($choice['value'] == '') {
                            $choice['value'] = \sanitize_text_field($choice['label']);
                        }
                    });

                    $selected = array_values(\array_filter($field_options['choices'], function ($choice) {
                        return $choice['selected'] ?? false;
                    }));

                    if (count($selected) > 0) {
                        $field_options['data'] = $selected[0]['value'];
                    }

                    $field_options['choices'] = \array_combine(\array_column($field_options['choices'], 'value'), \array_column($field_options['choices'], 'label'));
                }
            }

            if ($field['type'] == 'time') {
                $field_options['input'] = 'array';
                // $field_options['html5'] = false;
            }

            if ($field['type'] == 'date' || $field['type'] == 'time' || $field['type'] == 'datetime' || $field['type'] == 'birthday') {
                if (!isset($field_options['widget']) || !$field_options['widget'] || $field_options['widget'] == 'single_text') {
                    $field_options['input'] = 'string';
                    $field_options['widget'] = 'single_text';
                } else {
                    $field_options['input'] = 'array';
                }
            }

            unset($field_options['name']);
            unset($field_options['type']);

            if (isset($field_options['field_type'])) {
                $field_options['type'] = $field_options['field_type'];
                unset($field_options['field_type']);
            }


            try {
                if ($field['type'] == 'repeater_start') {
                    $builder->add($field['name'] . '-start', $this->get_field('repeater_start'), $field_options);
                    $start_repeater = $field['name'];
                    continue;
                }
                if ($start_repeater && $start_repeater != '') {
                    if ($field['type'] == 'repeater_end') {
                        $builder->add($start_repeater, $this->get_field('repeater_end'), $field_options + [
                            'name' => $start_repeater,
                            'fields' => $repeater
                        ]);
                        $start_repeater = '';
                        $repeater = [];
                    } else {
                        $repeater[] = [
                            'name' => $field['name'],
                            'type' => $field['type'],
                            'class' => $this->get_field($field['type']),
                            'options' => $field_options
                        ];
                    }
                    continue;
                }
                if ($field['type'] == 'file') {
                    Helper::create_file_field($builder, $field, $field_options);
                    continue;
                }
                $builder->add($field['name'], $this->get_field($field['type']), $field_options);
                if ($field['type'] == 'checkbox' || $field['type'] == 'radio') {
                    $builder->get($field['name'])->addModelTransformer(new CallbackTransformer(
                        function ($activeAsString) {
                            return (bool)(int)$activeAsString;
                        },
                        function ($activeAsBoolean) {
                            return (string)(int)$activeAsBoolean;
                        }
                    ));
                }
            } catch (\Throwable $e) {
                \error_log($e->getMessage());
            }
        }
        $builder->add('_message', $this->get_field('html'), [
            'html' => '<div class="form-message"></div>'
        ]);
        try {
            return $builder->getForm();
        } catch (\Throwable $e) {
            // \var_dump($e);
            return new WP_Error('form_error', $e->getMessage());
        }
    }

    /**
     * Render the form
     *
     * @date 2024-04-11
     *
     * @param string $name
     * @param \Symfony\Component\Form\Form $form
     * @param string $theme
     *
     * @return void
     */
    public function render($name, $form, $theme = null)
    {
        $twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(array(
            AJAXY_FORMS_PLUGIN_DIR . 'inc/themes',
            AJAXY_FORMS_PLUGIN_DIR . 'vendor/symfony/twig-bridge/Resources/views/Form',
        )), [
            'debug' => true
        ]);

        // Set up the Translation component
        $translator = new Translator('en');
        $translator->addLoader('xlf', new XliffFileLoader());
        $translator->addResource('xlf', AJAXY_FORMS_PLUGIN_DIR . 'vendor/symfony/form/Resources/translations/validators.en.xlf', 'en', 'validators');
        $translator->addResource('xlf', AJAXY_FORMS_PLUGIN_DIR . 'vendor/symfony/validator/Resources/translations/validators.en.xlf', 'en', 'validators');

        $twig->addExtension(new TranslationExtension($translator));


        $twig->addExtension(
            new FormExtension($translator)
        );

        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $theme = $theme ?? 'tailwind_2_layout.html.twig';

        $twig->addRuntimeLoader(new \Twig\RuntimeLoader\FactoryRuntimeLoader(
            array(
                FormRenderer::class => function () use ($twig, $theme) {
                    return new FormRenderer(new TwigRendererEngine(array($theme), $twig));
                }
            )
        ));

        if ($form->isSubmitted() && $form->isValid()) {
            $registered_form = Data::parse_form($name);
            $messages = array_replace([
                'success' => __('Form submitted successfully', "ajaxy-forms"),
                'error' => __('Form failed to submit, Please try again', "ajaxy-forms"),
            ], $registered_form->get_option('messages', []));
            return sprintf('<div class="ajaxy-form"><div class="form-message success">%s</div></div>', $messages['success'] ?? '');
        }

        $output = $twig->render($twig->createTemplate('{{ form(form) }}'), ['form' => $form->createView()]);
        return $output;
    }

    /**
     * On Form Submit
     *
     * @date 2024-04-10
     *
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    public function on_submit($form_name, $form)
    {
        $form->handleRequest();
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data = Helper::handle_files($data);

            $registered_form = Data::parse_form($form_name);
            $actions = $registered_form->get_actions();

            foreach ($actions as $action) {
                \call_user_func($action, $data, $registered_form);
            }
            $this->csrf_token_manager->refreshToken('form_intention_' . $registered_form->get_name());
            return true;
        }

        return false;
    }


    public function action_links($links, $file)
    {
        if ($file != AJAXY_FORMS_BASENAME) {
            return $links;
        }

        if (!current_user_can('manage_options')) {
            return $links;
        }

        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            menu_page_url('ajaxy-forms-settings', false),
            __('Settings', "ajaxy-forms")
        );

        array_unshift($links, $settings_link);

        return $links;
    }

    public function filters()
    {

        // add_filter('plugin_action_links', [$this, 'action_links'], 10, 2);
    }
    public function actions()
    {
        add_action('rest_api_init', function () {
            register_rest_route("ajaxy-forms" . '/v1', '/form/(?P<name>.+)', array(
                'methods' => 'GET,POST',
                'permission_callback' => '__return_true',
                'callback' => [$this, 'submit'],
            ));
            register_rest_route("ajaxy-forms" . '/v1', '/form-data/', array(
                'methods' => 'GET,POST',
                'permission_callback' => '__return_true',
                'callback' => [$this, 'get_data'],
            ));
        });

        add_action('wp_enqueue_scripts', array(&$this, 'scripts'));
    }

    public function scripts()
    {
        wp_enqueue_style("ajaxy-forms" . "-style", AJAXY_FORMS_PLUGIN_URL . '/assets/css/styles.css', [], "1.0");
        wp_enqueue_script("ajaxy-forms" . '-script',  AJAXY_FORMS_PLUGIN_URL  . '/assets/js/script.js', array(), 1.0, true);
        wp_localize_script("ajaxy-forms" . '-script', 'ajaxyFormsSettings', array(
            'nonce' => wp_create_nonce('wp_rest'),
            'dataUrl' => get_rest_url(null, sprintf('ajaxy-forms/v1/form-data/'))
        ));
    }


    public function get_field($type)
    {
        $data = preg_split('/(?=[A-Z])/', $type);
        $type = \strtolower(implode('_', $data));

        $field = Inc\Fields::getInstance()->get($type);
        return $field['class'];
    }

    public function register($name, $fields, $options = [], $actions = [], $initial_data = null)
    {
        Data::register_form($name, $fields, $options, $actions, $initial_data);
    }

    /**
     * Register a form action
     *
     * @date 2024-04-10
     *
     * @param string $form_name
     * @param string $action_name
     * @param array|callable $optionsOrCallable
     *
     * @return void
     */
    public function register_action($form_name, $action_name, $optionsOrCallable)
    {
        $form = Data::parse_form($form_name);
        if (!$form) {
            throw new \Exception('Please register the form first before registering the action');
        }

        $form->add_action($action_name, $optionsOrCallable);
    }


    public function register_action_type($type, $options)
    {
        Inc\Actions::getInstance()->register($type, $options);
    }

    public function get_data(\WP_REST_Request $request)
    {
        $type = $request->get_param('type') ?? null;
        switch ($type) {
            case 'posts_by_term':
                $term_id = $request->get_param('term_id') ?? null;
                $taxonomy = $request->get_param('taxonomy') ?? 'category';
                $post_type = $request->get_param('post_type') ?? 'post';
                $orderby = $request->get_param('orderby') ?? 'date';
                $order = $request->get_param('order') ?? 'DESC';
                $meta_key = $request->get_param('meta_key') ?? '';
                $meta_value = $request->get_param('meta_value') ?? '';
                $exclude = $request->get_param('exclude') ?? '';
                $include = $request->get_param('include') ?? '';
                $posts = get_posts(array(
                    'post_type' => $post_type,
                    'order' => $order,
                    'orderby' => $orderby,
                    'meta_key' => $meta_key,
                    'meta_value' => $meta_value,
                    'exclude' => array_filter(explode(',', $exclude)),
                    'include' => array_filter(explode(',', $include)),
                    'posts_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'field' => 'id',
                            'terms' => $term_id,
                        ),
                    ),
                ));
                $response = array_map(function ($post) {
                    return array(
                        'id' => $post->ID,
                        'title' => $post->post_title
                    );
                }, $posts);
                return new \WP_REST_Response([
                    'data' => $response,
                    'status' => 'success'
                ]);
            default:
                return new \WP_REST_Response(['status' => 'error', 'message' => 'Unsupported data type']);
        }
    }
    public function submit(\WP_REST_Request $request)
    {
        $form_name = $request->get_param('name') ?? null;
        if (!$form_name || trim($form_name) == "") {
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Form not found']);
        }

        $form = Data::parse_form($form_name);
        if (!$form) {
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Form not found']);
        }

        $messages = array_replace([
            'success' => __('Form submitted successfully', "ajaxy-forms"),
            'error' => __('Form failed to submit, Please try again', "ajaxy-forms"),
            'wp_error' => __('Error submitting form: %s', "ajaxy-forms"),
        ], $form->get_option('messages', []));

        $data = $request->get_body_params()[$form_name] ?? [];
        $submitted_form = $this->create_form($form, $data);
        if (\is_wp_error($submitted_form)) {
            return new \WP_REST_Response([
                'status' => 'error',
                'message' => \sprintf($messages['wp_error'], $submitted_form->get_error_message()),
                '_token' => $this->csrf_token_manager->getToken('form_intention_' . $form->get_name())->getValue()
            ]);
        }
        $valid = $this->on_submit($form_name, $submitted_form);
        if ($valid) {
            $message = $form->get_message('success');

            return new \WP_REST_Response([
                'status' => 'success',
                'message' => $message ? $message : $messages['success'],
                '_token' => $this->csrf_token_manager->getToken('form_intention_' . $form->get_name())->getValue()
            ]);
        }
        $message = $form->get_message('error');
        $errors = $this->get_form_error_messages($submitted_form);
        if (count($errors) > 0) {
            $message .= implode('<br>', $errors);
        }

        return new \WP_REST_Response([
            'status' => 'error',
            'message' => $message ? $message : $messages['error'],
            'fields' => $this->get_fields_error_messages($submitted_form)
        ]);
    }

    private function get_fields_error_messages(\Symfony\Component\Form\FormInterface $form)
    {
        $errors = array();
        if ($form->count() && $form->isSubmitted()) {
            /** @var \Symfony\Component\Form\Form */
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $children = $child->all();
                    $subError = false;
                    if (\count($children) > 0) {
                        foreach ($children as $sub_child) {
                            if (!$sub_child->isValid()) {
                                $errors[$child->getName()][$sub_child->getName()] = $this->get_form_error_messages($sub_child);
                                $subError = true;
                            }
                        }
                    }
                    if (!$subError) {
                        $errors[$child->getName()] = implode(', ', $this->get_form_error_messages($child));
                    }
                }
            }
        }
        return $errors;
    }

    private function get_form_error_messages(\Symfony\Component\Form\FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {

            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[] = $template;
        }

        return $errors;
    }

    public function register_field($type, $options)
    {
        Inc\Fields::getInstance()->register($type, $options);
    }
}

register_activation_hook(__FILE__, function () {
    Inc\Data::install();
});

\Ajaxy\Forms\Plugin::init();


\register_form_field('html', [
    "label" => "HTML",
    "class" => Inc\Types\HtmlType::class,
    "docs" => false,
    "disable_constraints" => true,
    "inherited" => [],
    "properties" => [[
        "section" => "basic",
        "order" => 1,
        "label" => "Label",
        "type" => "text",
        "name" => "label",
        "help" => "Enter the label for the field, keep empty to hide it",
    ], [
        "section" => "basic",
        "order" => 1,
        "label" => "Html",
        "type" => "textarea",
        "name" => "html",
        "help" => "Enter the HTML to display in the form",
    ]],
    "order" => 50,
    "keywords" => "html,custom",
    "common" => false
]);

\register_form_action_type('storage', [
    "label" => "Storage",
    "class" => Inc\Actions\Storage::class,
    "docs" => false,
    "properties" => Inc\Actions\Storage::get_properties()
]);

\register_form_action_type('email', [
    "label" => "Email",
    "class" => Inc\Actions\Email::class,
    "docs" => false,
    "properties" => Inc\Actions\Email::get_properties()
]);

\register_form_action_type('sms', [
    "label" => "SMS",
    "class" => Inc\Actions\Sms::class,
    "docs" => false,
    "properties" => Inc\Actions\Sms::get_properties()
]);


\register_form_action_type('whatsapp', [
    "label" => "Whatsapp",
    "class" => Inc\Actions\Whatsapp::class,
    "docs" => false,
    "properties" => Inc\Actions\Whatsapp::get_properties()
]);

\register_form_action_type('webhook', [
    "label" => "Webhook",
    "class" => Inc\Actions\Webhook::class,
    "docs" => false,
    "properties" => Inc\Actions\Webhook::get_properties()
]);

\register_form_action_type('email_responder', [
    "label" => "Auto Responder Email",
    "class" => Inc\Actions\Email::class,
    "docs" => false,
    "properties" => Inc\Actions\Email::get_properties()
]);
