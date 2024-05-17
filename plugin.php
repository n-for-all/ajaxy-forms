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
    private $logger = null;
    private $settings = null;
    private $entry_settings = null;
    private $builder = null;




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


        define('VENDOR_DIR', \plugin_dir_path(__FILE__) . 'vendor');
        define('VENDOR_FORM_DIR', VENDOR_DIR . '/symfony/form');
        define('VENDOR_VALIDATOR_DIR', VENDOR_DIR . '/symfony/validator');
        define('VENDOR_TWIG_BRIDGE_DIR', VENDOR_DIR . '/symfony/twig-bridge');
        define('THEMES_DIR', \plugin_dir_path(__FILE__) . 'inc/themes');

        if (is_admin()) {
            $this->settings = new Admin\Inc\Form\Settings();
            $this->entry_settings = new Admin\Inc\Entry\Settings();
            $this->builder = new Admin\Inc\Form\Builder();
        }


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
                $form = $this->create_form($name, $form['fields'] ?? [], $form['options'] ?? [], $form['initial_data'] ?? null);
                $this->on_submit($name, $form);
                return $this->render($name, $form);
            } else {
                return \sprintf(__('Form %s not found', AJAXY_FORMS_TEXT_DOMAIN), $name);
            }
        }
    }


    public function create_form($form_name, $fields, $options = [], $initial_data = null)
    {


        // Set up the CSRF Token Manager
        $csrfTokenManager = new CsrfTokenManager();

        // Set up the Validator component
        $validator = Validation::createValidator();

        // Set up the Form component
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new CsrfExtension($csrfTokenManager))
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory();


        $options = \array_replace([
            'csrf_protection' => true,
            'allow_extra_fields' => true,
            'required' => false,
        ], $options);

        unset($options['messages']);

        if (isset($options['attr']['class'])) {
            $options['attr']['class'] .= ' ajaxy-form';
        } else {
            $options['attr']['class'] = 'ajaxy-form';
        }
        $builder = $formFactory->createNamedBuilder($form_name, Type\FormType::class, $initial_data, $options);

        foreach ($fields as $field) {
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
            THEMES_DIR,
            VENDOR_TWIG_BRIDGE_DIR . '/Resources/views/Form',
        )), [
            'debug' => true
        ]);


        // Set up the Translation component
        $translator = new Translator('en');
        $translator->addLoader('xlf', new XliffFileLoader());
        $translator->addResource('xlf', VENDOR_FORM_DIR . '/Resources/translations/validators.en.xlf', 'en', 'validators');
        $translator->addResource('xlf', VENDOR_VALIDATOR_DIR . '/Resources/translations/validators.en.xlf', 'en', 'validators');

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
            ], $registered_form['options']['messages'] ?? []);
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
            if (isset($registered_form['notifications'])) {
                foreach ($registered_form['notifications'] as $notification) {
                    $notification->send($form_name, $data);
                }
            }

            if (isset($registered_form['storage'])) {
                unset($data['_message']);
                $registered_form['storage']($form_name, $data);
            }

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
        add_action('wp_enqueue_scripts', array(&$this, 'scripts'));
        add_action('wp_ajax_af_submit', [$this, 'ajax'], 10, 2);
        add_action('wp_ajax_nopriv_af_submit', [$this, 'ajax'], 10, 2);
    }

    public function scripts()
    {
        wp_enqueue_style(AJAXY_FORMS_TEXT_DOMAIN . "-style", AJAXY_FORMS_PLUGIN_URL . '/assets/css/styles.css');
        wp_enqueue_script(AJAXY_FORMS_TEXT_DOMAIN . '-script',  AJAXY_FORMS_PLUGIN_URL  . '/assets/js/script.js', array(), 1.0, true);
        wp_localize_script(AJAXY_FORMS_TEXT_DOMAIN . '-script', 'ajaxyFormsSettings', array('ajaxurl' => admin_url('admin-ajax.php')));
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
     * Register a form notification
     *
     * @date 2024-04-10
     *
     * @param string $form_name
     * @param string $type
     * @param array $options
     *
     * @return void
     */
    public function register_notification($form_name, $type, $options)
    {
        $form = Data::parse_form($form_name);
        if (!$form) {
            throw new \Exception('Please register the form first before registering the notification');
        }

        if (!$form['notifications']) {
            $form['notifications'] = [];
        }

        switch ($type) {
            case 'email':
                $form['notifications'][] = new Inc\Actions\Email($options);
                break;
            case 'sms':
                $form['notifications'][] = new Inc\Actions\Sms($options);
                break;
            case 'webhook':
                $form['notifications'][] = new Inc\Actions\Webhook($options);
                break;
            case 'whatsapp':
                $form['notifications'][] = new Inc\Actions\Whatsapp($options);
                break;
            case 'telegram':
                $form['notifications'][] = new Inc\Actions\Telegram($options);
                break;
            default:
                throw new \Exception('Notification type not found');
        }
    }

    public function register_storage($form_name, $options = [])
    {
        $form = Data::parse_form($form_name);
        if (!$form) {
            throw new \Exception('Please register the form first before registering the storage');
        }
        if (isset($options['callback'])) {
            if (!\is_callable($options['callback'])) {
                throw new \Exception('Callback should be a function or a callable string');
            }
            $form['storage'] = $options['callback'];
        } else {
            $form['storage'] = function ($form_name, $data) {
                Inc\Data::add($form_name, $data);
            };
        }
    }

    public function ajax()
    {

        $form_name = $_REQUEST['form_name'];
        $data = $_REQUEST['data'];

        if (!$form_name || trim($form_name) == "") {
            echo \wp_json_encode(['status' => 'error', 'message' => 'Form not found']);
            wp_die();
        }
        $form = Data::parse_form($form_name);
        if (!$form) {
            echo \wp_json_encode(['status' => 'error', 'message' => 'Form not found']);
        }

        $form = $this->create_form($form_name, $form['fields'] ?? [], $form['options'] ?? [], $data);
        $valid = $this->on_submit($form_name, $form);
        if ($valid) {
            echo \wp_json_encode(['status' => 'success', 'message' => __('Form submitted successfully', \AJAXY_FORMS_TEXT_DOMAIN)]);
        } else {
            echo \wp_json_encode(['status' => 'error', 'message' => $form['options']['messages']['error'] ?? __('Form failed to submit, Please try again', \AJAXY_FORMS_TEXT_DOMAIN), 'fields' => $this->getErrorMessages($form)]);
        }

        wp_die();
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count() && $form->isSubmitted()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }
}

register_activation_hook(__FILE__, function () {
    Inc\Data::install();
});

\Ajaxy\Forms\Plugin::init();
