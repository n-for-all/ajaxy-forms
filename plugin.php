<?php

/**
 * Plugin Name: Ajaxy Forms
 * Plugin URI: http://www.ajaxy.org
 * Description: Ajaxy Forms
 * Version: 1.0.0
 * Author: Naji Amer
 * Author URI: http://www.ajaxy.org
 */


namespace Ajaxy\Forms;

require 'vendor/autoload.php';
require 'functions.php';

use Ajaxy\Forms\Inc\Constraints;
use Ajaxy\Forms\Inc\Data;
use Ajaxy\Forms\Inc\Form;
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



define("AJAXY_FORMS_TEXT_DOMAIN", "ajaxy-forms");
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
                $form = $this->create_form($form, $form->get_initial_data());
                $valid = $this->on_submit($name, $form);
                return $this->render($name, $form);
            } else {
                return \sprintf(__('Form %s not found', AJAXY_FORMS_TEXT_DOMAIN), $name);
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
            'csrf_protection' => true,
            'csrf_message' => __('It appears you\'ve already submitted this form, or it may have timed out. Please refresh the page and try again.', \AJAXY_FORMS_TEXT_DOMAIN),
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

        foreach ($form->get_fields() as $field) {
            $field_options = $field;
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

            unset($field_options['name']);
            unset($field_options['type']);

            if (isset($field_options['field_type'])) {
                $field_options['type'] = $field_options['field_type'];
                unset($field_options['field_type']);
            }

            $builder->add($field['name'], $this->get_field($field['type']), $field_options);
        }
        $builder->add('_message', $this->get_field('html'), [
            'html' => '<div class="form-message"></div>'
        ]);

        return $builder->getForm();
    }

    /**
     * Render the form
     *
     * @date 2024-04-11
     *
     * @param \Symfony\Component\Form\Form $form
     *
     * @return void
     */
    public function render($name, $form)
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

        $twig->addRuntimeLoader(new \Twig\RuntimeLoader\FactoryRuntimeLoader(
            array(
                FormRenderer::class => function () use ($twig) {
                    return new FormRenderer(new TwigRendererEngine(array('tailwind_2_layout.html.twig'), $twig));
                }
            )
        ));

        if ($form->isSubmitted() && $form->isValid()) {
            $registered_form = Data::parse_form($name);
            $messages = array_replace([
                'success' => __('Form submitted successfully', \AJAXY_FORMS_TEXT_DOMAIN),
                'error' => __('Form failed to submit, Please try again', \AJAXY_FORMS_TEXT_DOMAIN),
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
            __('Settings', AJAXY_FORMS_TEXT_DOMAIN)
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
            register_rest_route(AJAXY_FORMS_TEXT_DOMAIN . '/v1', '/form/(?P<name>\w+)', array(
                'methods' => 'POST',
                'callback' => [$this, 'submit'],
            ));
        });

        add_action('wp_enqueue_scripts', array(&$this, 'scripts'));
    }

    public function scripts()
    {
        wp_enqueue_style(AJAXY_FORMS_TEXT_DOMAIN . "-style", AJAXY_FORMS_PLUGIN_URL . '/assets/css/styles.css');
        wp_enqueue_script(AJAXY_FORMS_TEXT_DOMAIN . '-script',  AJAXY_FORMS_PLUGIN_URL  . '/assets/js/script.js', array(), 1.0, true);
        wp_localize_script(AJAXY_FORMS_TEXT_DOMAIN . '-script', 'ajaxyFormsSettings', array(
            'nonce' => wp_create_nonce('wp_rest')
        ));
    }


    public function get_field($type)
    {
        $data = preg_split('/(?=[A-Z])/', $type);
        $type = \strtolower(implode('_', $data));

        $field = Inc\Fields::getInstance()->get($type);
        return $field['class'];
    }

    public function register($name, $fields, $options = [], $initial_data = null)
    {
        Data::register_form($name, $fields, $options, $initial_data);
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


        if (\is_callable($optionsOrCallable)) {
            $form->add_action($action_name, function ($data, $form) use ($optionsOrCallable) {
                $optionsOrCallable($data, $form);
            });
        } else if (\is_array($optionsOrCallable)) {
            $form->add_action($action_name, function ($data, $form) use ($action_name, $optionsOrCallable) {
                $action = Inc\Actions::getInstance()->get($action_name);
                if (!$action) {
                    throw new \Exception(sprintf('Action %s not found', $action_name));
                }
                $class = $action['class'];
                if (\is_string($class)) {
                    if ((!class_exists($class) || !\is_subclass_of($class, Inc\Actions\ActionInterface::class))) {
                        throw new \Exception(sprintf('Action %s class must be a callable function or a class that implements the __invoke() method, you can pass the class name as string', $action_name));
                    }

                    $instance = new $class($optionsOrCallable);
                    $instance->execute($data, $form);
                } else {
                    if (!\is_callable($class)) {
                        throw new \Exception(sprintf('Action %s class must be a callable function or a class that implements the __invoke() method', $action_name));
                    }

                    \call_user_func($class, $data, $form, $optionsOrCallable);
                }
            });
        } else {
            throw new \Exception('Invalid action options, must be a callable function or an array of options that have a class key with the class name');
        }
    }


    public function register_action_type($type, $options)
    {
        Inc\Actions::getInstance()->register($type, $options);
    }

    public function submit(\WP_REST_Request $request)
    {
        $form_name = $request->get_param('name');
        $data = $request->get_body_params()[$form_name];

        if (!$form_name || trim($form_name) == "") {
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Form not found']);
        }
        $form = Data::parse_form($form_name);
        if (!$form) {
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Form not found']);
        }

        $submitted_form = $this->create_form($form, $data);
        $valid = $this->on_submit($form_name, $submitted_form);
        if ($valid) {
            $message = $form->get_message('success');

            return new \WP_REST_Response([
                'status' => 'success', 'message' => $message ? $message : __('Form submitted successfully', \AJAXY_FORMS_TEXT_DOMAIN),
                '_token' => $this->csrf_token_manager->getToken('form_intention_' . $form->get_name())->getValue()
            ]);
        }
        $message = $form->get_message('error');
        $errors = $this->get_form_error_messages($submitted_form);
        if (count($errors) > 0) {
            $message .= implode('<br>', $errors);
        }
        return new \WP_REST_Response(['status' => 'error', 'message' => $message ? $message : __('Form failed to submit, Please try again', \AJAXY_FORMS_TEXT_DOMAIN), 'fields' => $this->get_fields_error_messages($submitted_form)]);
    }

    private function get_fields_error_messages(\Symfony\Component\Form\FormInterface $form)
    {
        $errors = array();
        if ($form->count() && $form->isSubmitted()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = implode(', ', $this->get_form_error_messages($child));
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
    "class" => Inc\Fields\HtmlType::class,
    "docs" => false,
    "inherited" => [],
    "properties" => [[
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
