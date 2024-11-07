<?php

namespace Ajaxy\Forms\Inc;

use Isolated\Symfony\Component\Form\AbstractType;

class Fields
{
    private $sections = [
        "basic" => [
            "order" => 0,
            "type" => "section",
            "label" => "Basic",
            "expanded" => true,
            "fields" =>
            []
        ],
        "advanced" => [
            "type" => "section",
            "label" => "Advanced",
            "expanded" => false,
            "order" => 1,
            "fields" =>
            []
        ],
        "validation" => [
            "type" => "section",
            "label" => "Validation",
            "expanded" => false,
            "order" => 1,
            "fields" =>
            []
        ]
    ];
    private $inherited_properties = [

        "name" => [
            "name" => "name",
            "type" => "text",
            "label" => "Name",
            "help" => "Enter the name for the field, keep empty to generate it automatically",
            "required" => false,
            "section" => "advanced",
            "order" => -1
        ],
        "label" => [
            "name" => "label",
            "type" => "text",
            "label" => "Label",
            "help" => "Enter the label for the field, keep empty to hide it",
            "required" => false,
            "section" => "basic",
            "order" => 0
        ],
        "help" => [
            "name" => "help",
            "type" => "text",
            "label" => "Help",
            "help" => "Enter the help for the field",
            "required" => false,
            "section" => "basic",
            "order" => 1
        ],
        "data" => [
            "name" => "data",
            "type" => "text",
            "label" => "Data",
            "help" => "Enter the initial value for the field",
            "required" => false,
            "section" => "advanced",
            "order" => 2
        ],
        "empty_data" => [
            "name" => "empty_data",
            "type" => "text",
            "label" => "Empty Data",
            "help" => "Enter the initial empty value for the field",
            "required" => false,
            "section" => "advanced",
            "order" => 2
        ],
        "required" => [
            "name" => "required",
            "type" => "checkbox",
            "label" => "Required",
            "help" => "Check to mark the field as required",
            "required" => false,
            "section" => "basic",
            "order" => 4
        ],

        "disabled" => [
            "name" => "disabled",
            "type" => "checkbox",
            "label" => "Disabled",
            "help" => "Check to disable the field",
            "required" => false,
            "section" => "advanced",
            "order" => 0
        ],
        "attr" => [
            "name" => "attr",
            "type" => "repeater",
            "label" => "Attributes",
            "fields" => [
                [
                    "name" => "name",
                    "type" => "text",
                    "label" => "Name",
                ],
                [
                    "name" => "value",
                    "type" => "text",
                    "label" => "Value",
                ],
            ],
            "help" => "Enter the attributes for the field",
            "required" => false,
            "section" => "basic",
            "order" => 1
        ],
        "help_attr" => [
            "name" => "help_attr",
            "type" => "repeater",
            "label" => "Help Attributes",
            "fields" => [
                [
                    "name" => "name",
                    "type" => "text",
                    "label" => "Name",
                ],
                [
                    "name" => "value",
                    "type" => "text",
                    "label" => "Value",
                ],
            ],
            "help" => "Enter the help attributes for the field",
            "required" => false,
            "section" => "advanced",
            "order" => 2
        ],
        "help_html" => [
            "name" => "help_html",
            "type" => "checkbox",
            "label" => "Disable Html Escape for the Help",
            "help" => "This is useful when you want to use an icon in the help field",
            "required" => false,
            "section" => "advanced",
            "order" => 3
        ],
        "label_attr" => [
            "name" => "label_attr",
            "type" => "repeater",
            "label" => "Label Attributes",
            "fields" => [
                [
                    "name" => "name",
                    "type" => "text",
                    "label" => "Name",
                ],
                [
                    "name" => "value",
                    "type" => "text",
                    "label" => "Value",
                ],
            ],
            "help" => "Enter the label attributes for the field",
            "required" => false,
            "section" => "advanced",
            "order" => 4
        ],
        "label_html" => [
            "name" => "label_html",
            "type" => "checkbox",
            "label" => "Disable Html Escape for the Label",
            "help" => "This is useful when you want to use an icon in the label field",
            "required" => false,
            "section" => "advanced",
            "order" => 5
        ],

        "row_attr" => [
            "name" => "row_attr",
            "type" => "repeater",
            "label" => "Row Attributes",
            "fields" => [
                [
                    "name" => "name",
                    "type" => "text",
                    "label" => "Name",
                ],
                [
                    "name" => "value",
                    "type" => "text",
                    "label" => "Value",
                ],
            ],
            "help" => "Enter the row attributes for the field",
            "required" => false,
            "section" => "advanced",
            "order" => 6
        ],
        "sanitize_html" => [
            "name" => "sanitize_html",
            "type" => "checkbox",
            "label" => "Sanitize Html",
            "help" => "This protects the form input against XSS, clickjacking and CSS injection",
            "required" => false,
            "section" => "advanced",
            "order" => 7
        ],
        "trim" => [
            "name" => "trim",
            "type" => "checkbox",
            "label" => "Trim",
            "default" => true,
            "help" => "Trim the input",
            "required" => false,
            "section" => "advanced",
            "order" => 8
        ],
        "invalid_message" => [
            "section" => "basic",
            "order" => 9,
            "type" => "text",
            "name" => "invalid_message",
            "label" => "Invalid Message",
            "help" => "The message to display when the field value is invalid, defaults to 'This value is not valid', Please use the validation to better customize this message",
        ]
    ];


    private $fields = [];

    static $instance = null;


    public function __construct()
    {
        $this->fields = require_once __DIR__ . '/fields/settings.php';
        \add_action('init', [$this, 'initialize'], 11);
    }


    public function initialize()
    {
        \array_walk($this->fields, function (&$field, $key) {
            if (isset($field['properties']) && \is_array($field['properties'])) {
                \array_walk($field['properties'], function (&$property, $key) {
                    if (isset($property['options']) && \is_callable($property['options'])) {
                        $property['options'] = $property['options']();
                    }
                });
            }
        });
    }

    /**
     * Create a new instance of Fields
     *
     * @date 2024-05-24
     *
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Fields();
        }
        return self::$instance;
    }

    /**
     * Get a field by its name
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param string $default
     *
     * @return array|null
     */
    public function get($name, $default = "text")
    {
        return isset($this->fields[$name]) ? $this->fields[$name] : ($default ? $this->fields[$default] : null);
    }

    /**
     * Set a form field by name
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param array  $properties
     *
     * @return void
     */
    public function set($name, $properties = [])
    {
        $this->fields[$name] = $properties;
    }

    /**
     * Check if the field exists
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->fields[$name]);
    }

    /**
     * Remove a field
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return void
     */
    public function remove($name)
    {
        unset($this->fields[$name]);
    }

    /**
     * Remove all fields
     *
     * @date 2024-05-24
     *
     * @return void
     */
    public function clear()
    {
        $this->fields = array();
    }

    /**
     * Get all form fields
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
     * Get field inherited properties
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param string $section
     *
     * @return array
     */
    public function get_inherited_properties($name, $section = null)
    {
        $field = $this->fields[$name];
        if ($field) {
            $inherited = $field['inherited'] ?? [];
            if (!empty($inherited)) {
                $values = \array_filter(\array_map(function ($value) {
                    return $this->inherited_properties[$value] ?? null;
                }, $inherited), function ($value) use ($section) {
                    return $value !== null && (!$section || $value['section'] === $section);
                });

                return $values;
            }
        }
        return array();
    }

    /**
     * Get field properties by name
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return array
     */
    public function get_properties($name)
    {
        $field = $this->get($name);
        if (isset($field["properties"])) {
            foreach ($field["properties"] as $property) {
                if (isset($property['options']) && \is_callable($property['options'])) {
                    $property['options'] = $property['options']();
                }
            }
        }
        $values = array_merge($this->get_inherited_properties($name), $field["properties"] ?? []);

        return $values;
    }

    /**
     * Get all properties for all fields
     *
     * @date 2024-05-24
     *
     * @return array
     */
    public function get_all_properties()
    {
        $output = [];
        $fields = $this->get_fields();
        foreach ($fields as $name => $field) {
            unset($field["class"]);
            $properties = $this->sections;

            foreach ($properties as $key => &$section) {
                $values = array_merge($this->get_inherited_properties($name, $key), \array_filter($field["properties"] ?? [], function (&$value) use ($key) {
                    if (isset($value['options']) && \is_callable($value['options'])) {
                        $value['options'] = $value['options']();
                    }
                    return $value['section'] === $key;
                }));

                \array_multisort(\array_column($values, 'order'), SORT_ASC, $values);

                $section['fields'] = $values;
            }
            $output[$name] = ["name" => $name, "label" => \ucwords($name), "properties" => $properties, "docs" => $field['docs'] ?? null, "keywords" => $field['keywords'] ?? null, 'disable_constraints' => $field['disable_constraints'] ?? false];
        }

        return $output;
    }

    /**
     * Register a new field
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param string $properties
     *
     * @return void
     */
    public function register($name, $properties)
    {
        if (!isset($properties['class']) || !is_subclass_of($properties['class'], AbstractType::class)) {
            throw new \Exception("Field properties class must be an instance of Isolated\Symfony\Component\Form\AbstractType");
        }
        $this->fields[$name] = $properties;
    }
}
