<?php

namespace Ajaxy\Forms\Inc;

use Symfony\Component\Form\Extension\Core\Type;

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

        "label" => [
            "name" => "label",
            "type" => "text",
            "label" => "Label",
            "help" => "Enter the label for the text box, keep empty to hide it",
            "required" => false,
            "section" => "basic",
            "order" => 0
        ],
        "help" => [
            "name" => "help",
            "type" => "text",
            "label" => "Help",
            "help" => "Enter the help for the text box",
            "required" => false,
            "section" => "basic",
            "order" => 1
        ],
        "data" => [
            "name" => "data",
            "type" => "text",
            "label" => "Data",
            "help" => "Enter the initial value for the text box",
            "required" => false,
            "section" => "basic",
            "order" => 2
        ],
        "empty_data" => [
            "name" => "empty_data",
            "type" => "text",
            "label" => "Empty Data",
            "help" => "Enter the initial empty value for the text box",
            "required" => false,
            "section" => "basic",
            "order" => 3
        ],
        "required" => [
            "name" => "required",
            "type" => "checkbox",
            "label" => "Required",
            "help" => "Check to mark the text box as required",
            "required" => false,
            "section" => "basic",
            "order" => 4
        ],

        "disabled" => [
            "name" => "disabled",
            "type" => "checkbox",
            "label" => "Disabled",
            "help" => "Check to disable the text box",
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
            "help" => "Enter the attributes for the text box",
            "required" => false,
            "section" => "advanced",
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
            "help" => "Enter the help attributes for the text box",
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
            "help" => "Enter the label attributes for the text box",
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
            "help" => "Enter the row attributes for the text box",
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
            "section" => "advanced",
            "order" => 9,
            "type" => "text",
            "name" => "invalid_message",
            "label" => "Invalid Message",
            "help" => "The message to display when the field value is invalid, defaults to 'This value is not valid', Please use the validation to better customize this message",
        ]
    ];

    private $additional_fields = [];

    private $fields = array(
        'text' => [
            "label" => "Text",
            "class" => Type\TextType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/text.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim"],
            "additional" => [],
            "order" => 1,
            "keywords" => "text,short,small,string,textfield,paragraph",
            "common" => true
        ],
        'textarea' => [
            "label" => "Textarea",
            "class" => Type\TextareaType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/textarea.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim"],
            "additional" => [],
            "order" => 2,
            "keywords" => "textarea,long,medium,text,paragraph",
            "common" => true
        ],
        'email' => [
            "label" => "Email",
            "class" => Type\EmailType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/email.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [],
            "order" => 3,
            "keywords" => "email",
            "common" => true
        ],
        'integer' => [
            "label" => "Integer",
            "class" => Type\IntegerType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/integer.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 6,
                    "type" => "checkbox",
                    "label" => "Grouping",
                    "name" => "grouping",
                    "help" => "Numbers will be grouped with a comma or period (depending on your locale): 12345.123 would display as 12,345.123",
                ],
                [
                    "section" => "basic",
                    "order" => 7,
                    "type" => "select",
                    "name" => "rounding_mode",
                    "label" => "Rounding Mode",
                    "options" => [
                        "" => 'Select a rounding mode',
                        \NumberFormatter::ROUND_DOWN => "Round towards zero",
                        \NumberFormatter::ROUND_FLOOR => "Round towards negative infinity",
                        \NumberFormatter::ROUND_UP => "Round away from zero",
                        \NumberFormatter::ROUND_CEILING => "Round towards positive infinity"
                    ],
                    "help" => "By default, if the user enters a non-integer number, it will be rounded down",
                ]
            ],
            "order" => 30,
            "keywords" => "integer,number,float",
            "common" => false
        ],
        'money' => [
            "label" => "Money",
            "class" => Type\MoneyType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/money.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 6,
                    "type" => "text",
                    "name" => "currency",
                    "label" => "Currency",
                    "help" => 'This can be any <a href="https://en.wikipedia.org/wiki/ISO_4217" target="_blank">3 letter ISO 4217 code</a>. You can also set this to false to hide the currency symbol.',
                ],
                [
                    "section" => "basic",
                    "order" => 7,
                    "type" => "checkbox",
                    "label" => "Grouping",
                    "name" => "grouping",
                    "help" => "Numbers will be grouped with a comma or period (depending on your locale): 12345.123 would display as 12,345.123",
                ],
                [
                    "section" => "basic",
                    "order" => 8,
                    "type" => "select",
                    "name" => "rounding_mode",
                    "label" => "Rounding Mode",
                    "options" => [
                        "" => 'Select a rounding mode',
                        \NumberFormatter::ROUND_DOWN => "Round towards zero",
                        \NumberFormatter::ROUND_FLOOR => "Round towards negative infinity",
                        \NumberFormatter::ROUND_UP => "Round away from zero",
                        \NumberFormatter::ROUND_CEILING => "Round towards positive infinity"
                    ],
                    "help" => "By default, if the user enters a non-integer number, it will be rounded down",
                ],
                [
                    "section" => "advanced",
                    "order" => 6,
                    "type" => "number",
                    "name" => "divisor",
                    "label" => "Divisor",
                    "default" => 1,
                    "help" => 'If you need to divide your starting value by a number before rendering it to the user, you can use the divisor option',
                ],
                [
                    "section" => "advanced",
                    "order" => 6,
                    "type" => "number",
                    "name" => "scale",
                    "label" => "Scale",
                    "default" => 2,
                    "help" => 'If, for some reason, you need some scale other than 2 decimal places, you can modify this value',
                ],
                [
                    "section" => "advanced",
                    "order" => 7,
                    "type" => "checkbox",
                    "name" => "html5",
                    "label" => "Native HTML5",
                    "default" => 1,
                    "help" => 'If set to true, the HTML input will be rendered as a native HTML5',
                ]
            ],
            "order" => 31,
            "keywords" => "money,currency,dollar,euro,pound,yen",
            "common" => false
        ],
        'number' => [
            "label" => "Number",
            "class" => Type\NumberType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/number.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 7,
                    "type" => "checkbox",
                    "label" => "Grouping",
                    "name" => "grouping",
                    "help" => "Numbers will be grouped with a comma or period (depending on your locale): 12345.123 would display as 12,345.123",
                ],
                [
                    "section" => "basic",
                    "order" => 8,
                    "type" => "select",
                    "name" => "rounding_mode",
                    "label" => "Rounding Mode",
                    "options" => [
                        "" => 'Select a rounding mode',
                        \NumberFormatter::ROUND_DOWN => "Round towards zero",
                        \NumberFormatter::ROUND_FLOOR => "Round towards negative infinity",
                        \NumberFormatter::ROUND_UP => "Round away from zero",
                        \NumberFormatter::ROUND_CEILING => "Round towards positive infinity"
                    ],
                    "help" => "By default, if the user enters a non-integer number, it will be rounded down",
                ],
                [
                    "section" => "advanced",
                    "order" => 6,
                    "type" => "number",
                    "name" => "divisor",
                    "label" => "Divisor",
                    "default" => 1,
                    "help" => 'If you need to divide your starting value by a number before rendering it to the user, you can use the divisor option',
                ],
                [
                    "section" => "advanced",
                    "order" => 6,
                    "type" => "number",
                    "name" => "scale",
                    "label" => "Scale",
                    "default" => 2,
                    "help" => 'If, for some reason, you need some scale other than 2 decimal places, you can modify this value',
                ],
                [
                    "section" => "advanced",
                    "order" => 7,
                    "type" => "checkbox",
                    "name" => "html5",
                    "label" => "Native HTML5",
                    "default" => 1,
                    "help" => 'If you need to divide your starting value by a number before rendering it to the user, you can use the divisor option',
                ]
            ],
            "order" => 4,
            "keywords" => "number,integer,float",
            "common" => true
        ],
        'password' => [
            "label" => "Password",
            "class" => Type\PasswordType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/password.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "default" => true,
                    "type" => "checkbox",
                    "label" => "Always Empty",
                    "name" => "always_empty",
                    "help" => "If set to true, the field will always render blank, even if the corresponding field has a value.",
                ]
            ],
            "order" => 33,
            "keywords" => "password",
            "common" => false
        ],
        'percent' => [
            "label" => "Percent",
            "class" => Type\PercentType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/percent.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 8,
                    "type" => "select",
                    "name" => "rounding_mode",
                    "label" => "Rounding Mode",
                    "options" => [
                        "" => 'Select a rounding mode',
                        \NumberFormatter::ROUND_DOWN => "Round towards zero",
                        \NumberFormatter::ROUND_FLOOR => "Round towards negative infinity",
                        \NumberFormatter::ROUND_UP => "Round away from zero",
                        \NumberFormatter::ROUND_CEILING => "Round towards positive infinity"
                    ],
                    "help" => "By default, if the user enters a non-integer number, it will be rounded down",
                ],
                [
                    "section" => "advanced",
                    "order" => 6,
                    "type" => "text",
                    "name" => "symbol",
                    "label" => "Symbol",
                    "default" => "%",
                    "help" => 'By default, fields are rendered with a percentage sign % after the input. Setting the value to empty will not display the percentage sign',
                ],
                [
                    "section" => "advanced",
                    "order" => 7,
                    "type" => "number",
                    "name" => "scale",
                    "label" => "Scale",
                    "default" => 2,
                    "help" => 'If, for some reason, you need some scale other than 2 decimal places, you can modify this value',
                ],
                [
                    "section" => "advanced",
                    "order" => 8,
                    "type" => "select",
                    "name" => "type",
                    "label" => "Type",
                    "default" => "fractional",
                    "options" => [
                        "fractional" => "Fractional",
                        "integer" => "Integer"
                    ],
                    "help" => 'If set to true, the HTML input will be rendered as a native HTML5',
                ],
                [
                    "section" => "advanced",
                    "order" => 8,
                    "type" => "checkbox",
                    "name" => "html5",
                    "label" => "Native HTML5",
                    "default" => 1,
                    "help" => 'If set to true, the HTML input will be rendered as a native HTML5',
                ]
            ],
            "order" => 33,
            "keywords" => "percent",
            "common" => false
        ],
        'search' => [
            "label" => "Search",
            "class" => Type\SearchType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/search.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [],
            "order" => 33,
            "keywords" => "search",
            "common" => false
        ],
        'url' => [
            "label" => "URL",
            "class" => Type\UrlType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/url.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 9,
                    "type" => "text",
                    "name" => "default_protocol",
                    "label" => "Default Protocol",
                    "default" => "http",
                    "help" => "If a value is submitted that doesn't begin with some protocol (e.g. http://, ftp://, etc), this protocol will be prepended to the string when the data is submitted to the form.",
                ],
            ],
            "order" => 33,
            "keywords" => "url,http,https,protocol",
            "common" => false
        ],
        'range' => [
            "label" => "Range",
            "class" => Type\RangeType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/range.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "number",
                    "name" => "attr[min]",
                    "label" => "Minimum",
                    "help" => "The minimum value of the range",
                ], [
                    "section" => "basic",
                    "order" => 2,
                    "type" => "number",
                    "name" => "attr[max]",
                    "label" => "Maximum",
                    "help" => "The maximum value of the range",
                ],
            ],
            "order" => 33,
            "keywords" => "range,numbers",
            "common" => false
        ],
        'tel' => [
            "label" => "Phone",
            "class" => Type\TelType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/tel.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [],
            "order" => 5,
            "keywords" => "phone,telephone,tel",
            "common" => true
        ],
        'color' => [
            "label" => "Color",
            "class" => Type\ColorType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/color.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 3,
                    "type" => "checkbox",
                    "name" => "html5",
                    "label" => "Html5",
                    "help" => "When this option is set to true, the form type checks that its value matches the HTML5 color format (/^#[0-9a-f]{6}$/i)",
                ],
            ],
            "order" => 33,
            "keywords" => "color",
            "common" => false
        ],
        'choice' => [
            "label" => "Choice",
            "class" => Type\ChoiceType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/choice.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "repeater",
                    "name" => "choices",
                    "fields" => [
                        [
                            "order" => 1,
                            "type" => "text",
                            "name" => "label",
                            "label" => "Label",
                        ], [
                            "order" => 2,
                            "type" => "text",
                            "name" => "value",
                            "label" => "Value",
                        ], [
                            "order" => 3,
                            "type" => "checkbox",
                            "name" => "selected",
                            "label" => "Selected",
                        ], [
                            "order" => 4,
                            "type" => "checkbox",
                            "name" => "disabled",
                            "label" => "Disabled",
                        ]
                    ],
                    "label" => "Choices",
                    "help" => "Enter the choices for the select box",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "checkbox",
                    "name" => "multiple",
                    "label" => "Multiple",
                    "help" => "Whether the select box allows multiple choices",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "placeholder",
                    "label" => "Placeholder",
                    "help" => "The placeholder for the select box",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "preferred_choices",
                    "label" => "Preferred Choices",
                    "help" => "A comma separated list of choices that should be pre-selected",
                ],
            ],
            "order" => 5,
            "keywords" => "choice,select,check",
            "common" => true
        ],
        'country' => [
            "label" => "Country",
            "class" => Type\CountryType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/country.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "checkbox",
                    "name" => "alpha3",
                    "help" => 'If this option is true, the choice values use the <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3" target="_blank">ISO 3166-1 alpha-3</a> three-letter codes (e.g. New Zealand = NZL) instead of the default <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">ISO 3166-1 alpha-2</a> two-letter codes (e.g. New Zealand = NZ).',
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "checkbox",
                    "name" => "multiple",
                    "label" => "Multiple",
                    "help" => "Whether the select box allows multiple choices",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "placeholder",
                    "label" => "Placeholder",
                    "help" => "The placeholder for the select box",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "preferred_choices",
                    "label" => "Preferred Choices",
                    "help" => "A comma separated list of choices that should be pre-selected",
                ],
            ],
            "order" => 34,
            "keywords" => "country",
            "common" => false
        ],
        'language' => [
            "label" => "Language",
            "class" => Type\LanguageType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/language.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "checkbox",
                    "name" => "alpha3",
                    "help" => 'If this option is true, the choice values use the <a href="https://en.wikipedia.org/wiki/ISO_639-2" target="_blank">ISO 639-2 alpha-3 (2T)</a> three-letter codes (e.g. French = fra) instead of the default <a href="https://en.wikipedia.org/wiki/ISO_639-1" target="_blank">ISO 639-1 alpha-2</a> two-letter codes (e.g. French = fr).',
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "checkbox",
                    "name" => "multiple",
                    "label" => "Multiple",
                    "help" => "Whether the select box allows multiple choices",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "placeholder",
                    "label" => "Placeholder",
                    "help" => "The placeholder for the select box",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "preferred_choices",
                    "label" => "Preferred Choices",
                    "help" => "A comma separated list of choices that should be pre-selected",
                ],
            ],
            "order" => 35,
            "keywords" => "language,locale",
            "common" => false
        ],
        'locale' => [
            "label" => "Locale",
            "class" => Type\LocaleType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/locale.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 4,
                    "type" => "text",
                    "name" => "placeholder",
                    "label" => "Placeholder",
                    "help" => "The placeholder for the select box",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "checkbox",
                    "name" => "multiple",
                    "label" => "Multiple",
                    "help" => "Whether the select box allows multiple choices",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "preferred_choices",
                    "label" => "Preferred Choices",
                    "help" => "A comma separated list of choices that should be pre-selected",
                ],
            ],
            "order" => 36,
            "keywords" => "locale,language",
            "common" => false
        ],
        'timezone' => [
            "label" => "Timezone",
            "class" => Type\TimezoneType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/timezone.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 4,
                    "type" => "select",
                    "name" => "input",
                    "options" => [
                        "datetimezone" => "a \DateTimeZone object",
                        "intltimezone" => "an \IntlTimeZone object",
                        "string" => "String (e.g. America/New_York)",
                    ],
                    "label" => "Input",
                    "help" => "The format of the input data - i.e. the format that the timezone is stored on your underlying object",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "checkbox",
                    "name" => "multiple",
                    "label" => "Multiple",
                    "help" => "Whether the select box allows multiple choices",
                ],
                [
                    "section" => "basic",
                    "order" => 4,
                    "type" => "text",
                    "name" => "placeholder",
                    "label" => "Placeholder",
                    "help" => "The placeholder for the select box",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "preferred_choices",
                    "label" => "Preferred Choices",
                    "help" => "A comma separated list of choices that should be pre-selected",
                ]
            ],
            "order" => 37,
            "keywords" => "timezone,time,location",
            "common" => false
        ],
        'currency' => [
            "label" => "Currency",
            "class" => Type\CurrencyType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/currency.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 4,
                    "type" => "select",
                    "name" => "input",
                    "options" => [
                        "datetimezone" => "a \DateTimeZone object",
                        "intltimezone" => "an \IntlTimeZone object",
                        "string" => "String (e.g. America/New_York)",
                    ],
                    "label" => "Input",
                    "help" => "The format of the input data - i.e. the format that the timezone is stored on your underlying object",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "checkbox",
                    "name" => "multiple",
                    "label" => "Multiple",
                    "help" => "Whether the select box allows multiple choices",
                ],
                [
                    "section" => "basic",
                    "order" => 4,
                    "type" => "text",
                    "name" => "placeholder",
                    "label" => "Placeholder",
                    "help" => "The placeholder for the select box",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "preferred_choices",
                    "label" => "Preferred Choices",
                    "help" => "A comma separated list of choices that should be pre-selected",
                ],
            ],
            "order" => 38,
            "keywords" => "currency,money",
            "common" => false
        ],
        'date' => [
            "label" => "Date",
            "class" => Type\DateType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/date.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 4,
                    "type" => "select",
                    "name" => "input",
                    "options" => [
                        "datetimezone" => "a \DateTimeZone object",
                        "intltimezone" => "an \IntlTimeZone object",
                        "string" => "String (e.g. America/New_York)",
                    ],
                    "label" => "Input",
                    "help" => "The format of the input data - i.e. the format that the timezone is stored on your underlying object",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "checkbox",
                    "name" => "multiple",
                    "label" => "Multiple",
                    "help" => "Whether the select box allows multiple choices",
                ],
                [
                    "section" => "basic",
                    "order" => 4,
                    "type" => "text",
                    "name" => "placeholder",
                    "label" => "Placeholder",
                    "help" => "The placeholder for the select box",
                ],
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "text",
                    "name" => "preferred_choices",
                    "label" => "Preferred Choices",
                    "help" => "A comma separated list of choices that should be pre-selected",
                ]
            ],
            "order" => 6,
            "keywords" => "date,calendar",
            "common" => true
        ],
        'datetime' => [
            "label" => "DateTime",
            "class" => Type\DateTimeType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/datetime.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "select",
                    "name" => "type",
                    "label" => "Type",
                    "options" => [
                        "datetime" => "Datetime",
                        "date" => "Date",
                        "time" => "Time",
                    ],
                    "default" => "datetime",
                    "help" => 'Select the input type to use',
                ],
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "select",
                    "name" => "widget",
                    "label" => "Widget",
                    "options" => [
                        "choice" => "Choice",
                        "text" => "Text",
                        "single_text" => "Single Text",
                    ],
                    "default" => "single_text",
                    "help" => 'The basic way in which this field should be rendered. Can be one of the following:<br/>
                    choice: renders three select inputs. The order of the selects is defined in the format option.<br/>
                    text: renders a three field input of type text (month, day, year).<br/>
                    single_text: renders a single input of type date.',
                ],

                [
                    "section" => "basic",
                    "order" => 2,
                    "type" => "checkbox",
                    "name" => "html5",
                    "label" => "Native HTML5",
                    "default" => true,
                    "help" => 'Disable this field if you want to customize the date with javascript',
                ],
                [
                    "section" => "basic",
                    "order" => 3,
                    "type" => "text",
                    "name" => "input_format",
                    "label" => "Format",
                    "default" => "Y-m-d",
                    "help" => 'If the input option is set to string, this option specifies the format of the date. This must be a valid <a href="https://php.net/manual/en/function.date.php" target="blank">PHP date format</a>.',
                ],
                [
                    "section" => "basic",
                    "order" => 4,
                    "type" => "text",
                    "name" => "date_pattern",
                    "label" => "Date Pattern",
                    "default" => "",
                    "help" => 'A string with the date format to use.',
                ]
            ],
            "order" => 7,
            "keywords" => "date,calendar,time",
            "common" => false
        ],
        'time' => [
            "label" => "Time",
            "class" => Type\TimeType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/time.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "select",
                    "name" => "widget",
                    "label" => "Widget",
                    "options" => [
                        "choice" => "Choice",
                        "text" => "Text",
                        "single_text" => "Single Text",
                    ],
                    "default" => "single_text",
                    "help" => 'The basic way in which this field should be rendered. Can be one of the following:<br/>
                    choice: renders one, two (default) or three select inputs (hour, minute, second), depending on the with_minutes and with_seconds options.<br/>
                    text: renders one, two (default) or three text inputs (hour, minute, second), depending on the with_minutes and with_seconds options.<br/>
                    single_text: renders a single input of type time.',
                ],

                [
                    "section" => "basic",
                    "order" => 2,
                    "type" => "checkbox",
                    "name" => "html5",
                    "label" => "Native HTML5",
                    "default" => true,
                    "help" => 'Disable this field if you want to customize the date with javascript',
                ],
                [
                    "section" => "basic",
                    "order" => 3,
                    "type" => "text",
                    "name" => "input_format",
                    "label" => "Format",
                    "default" => "H:i:s",
                    "help" => 'If the input option is set to string, this option specifies the format of the time. This must be a valid <a href="https://php.net/manual/en/function.date.php" target="blank">PHP time format</a>.',
                ]
            ],
            "order" => 35,
            "keywords" => "time,clock",
            "common" => false
        ],
        'birthday' => [
            "label" => "Birthday",
            "class" => Type\BirthdayType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/birthday.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "select",
                    "name" => "widget",
                    "label" => "Widget",
                    "options" => [
                        "choice" => "Choice",
                        "text" => "Text",
                        "single_text" => "Single Text",
                    ],
                    "default" => "single_text",
                    "help" => 'The basic way in which this field should be rendered. Can be one of the following:<br/>
                    choice: renders three select inputs. The order of the selects is defined in the format option.<br/>
                    text: renders a three field input of type text (month, day, year).<br/>
                    single_text: renders a single input of type date. ',
                ]
            ],
            "order" => 35,
            "keywords" => "date,calendar,time",
            "common" => false
        ],
        'checkbox' => [
            "label" => "Checkbox",
            "class" => Type\CheckboxType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/checkbox.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "checkbox",
                    "name" => "checked",
                    "label" => "Checked",
                    "default" => false,
                    "help" => 'If checked, the checkbox will be checked by default.',
                ]
            ],
            "order" => 4,
            "keywords" => "checkbox",
            "common" => true
        ],
        'file' => [
            "label" => "File",
            "class" => Type\FileType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/file.html",
            "inherited" => ["label", "help", "data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 5,
                    "type" => "checkbox",
                    "name" => "multiple",
                    "label" => "Multiple",
                    "help" => "Whether the file allows multiple uploads",
                ]
            ],
            "order" => 7,
            "keywords" => "file",
            "common" => true
        ],
        'radio' => [
            "label" => "Radio",
            "class" => Type\RadioType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/radio.html",
            "inherited" => ["label", "help", "data", "empty_data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [
                [
                    "section" => "basic",
                    "order" => 1,
                    "type" => "checkbox",
                    "name" => "checked",
                    "label" => "Checked",
                    "default" => false,
                    "help" => 'If checked, the radio will be checked by default.',
                ]
            ],
            "order" => 4,
            "keywords" => "radio",
            "common" => true
        ],
        'uuid' => [
            "label" => "UUID",
            "class" => Type\UuidType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/uuid.html",
            "inherited" => ["label", "help", "data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [],
            "order" => 36,
            "keywords" => "uuid",
            "common" => false
        ],
        'ulid' => [
            "label" => "ULID",
            "class" => Type\UlidType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/ulid.html",
            "inherited" => ["label", "help", "data", "required", "disabled", "attr", "help_attr", "help_html", "label_attr", "label_html", "row_attr", "sanitize_html", "trim", "invalid_message"],
            "additional" => [],
            "order" => 37,
            "keywords" => "ulid",
            "common" => false
        ],
        'collection' => [
            "label" => "Collection",
            "class" => Type\CollectionType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/collection.html",
            "inherited" => ["label", "help", "empty_data", "attr", "help_attr", "help_html", "label_attr", "label_html", "invalid_message"],
            "additional" => [],
            "order" => 38,
            "keywords" => "collection",
            "common" => false
        ],
        'repeater' => [
            "label" => "Repeater",
            "class" => Type\RepeatedType::class,
            "docs" => "",
            "order" => 38,
            "keywords" => "collection,repeat",
            "common" => false
        ],
        'hidden' => [
            "label" => "Hidden",
            "class" => Type\HiddenType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/hidden.html",
            "inherited" => ["data",  "empty_data", "required", "attr", "row_attr", "invalid_message"],
            "additional" => [],
            "order" => 39,
            "keywords" => "hidden",
            "common" => false
        ],
        'button' => [
            "label" => "Button",
            "class" => Type\ButtonType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/button.html",
            "inherited" => ["label",  "label_html", "disabled", "attr", "row_attr"],
            "order" => 10,
            "keywords" => "button",
            "common" => true
        ],
        'reset' => [
            "label" => "Reset Button",
            "class" => Type\ResetType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/reset.html",
            "inherited" => ["label",  "label_html", "disabled", "attr", "row_attr"],
            "order" => 37,
            "keywords" => "button,reset",
            "common" => false
        ],
        'submit' => [
            "label" => "Submit Button",
            "class" => Type\SubmitType::class,
            "docs" => "https://symfony.com/doc/current/reference/forms/types/submit.html",
            "inherited" => ["label",  "label_html", "disabled", "attr", "row_attr"],
            "order" => 10,
            "keywords" => "button,submit",
            "common" => true
        ],
        'html' => [
            "label" => "HTML",
            "class" => Fields\HtmlType::class,
            "docs" => false,
            "inherited" => [],
            "additional" => [[
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
        ],
    );

    static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Fields();
        }
        return self::$instance;
    }

    public function get($name, $default = "text")
    {
        return isset($this->fields[$name]) ? $this->fields[$name] : ($default ? $this->fields[$default] : null);
    }

    public function set($name, $class)
    {
        $this->fields[$name] = $class;
    }

    public function has($name)
    {
        return isset($this->fields[$name]);
    }

    public function remove($name)
    {
        unset($this->fields[$name]);
    }

    public function clear()
    {
        $this->fields = array();
    }

    public function get_fields()
    {
        return $this->fields;
    }


    public function get_inherited_properties($name, $section)
    {
        $field = $this->fields[$name];
        if ($field) {
            $inherited = $field['inherited'] ?? [];
            if (!empty($inherited)) {
                $values = \array_filter(\array_map(function ($value) {
                    return $this->inherited_properties[$value] ?? null;
                }, $inherited), function ($value) use ($section) {
                    return $value !== null && $value['section'] === $section;
                });



                return $values;
            }
        }
        return array();
    }

    public function get_all_properties()
    {
        $output = [];

        $fields = $this->get_fields();
        foreach ($fields as $name => $field) {
            unset($field["class"]);
            $properties = $this->sections;

            foreach ($properties as $key => &$section) {
                $values = array_merge($this->get_inherited_properties($name, $key), \array_filter($field['additional'] ?? [], function ($value) use ($key) {
                    return $value['section'] === $key;
                }));
                \array_multisort(\array_column($values, 'order'), SORT_ASC, $values);
                $section['fields'] = $values;
            }
            $output[$name] = ["name" => $name, "label" => \ucwords($name), "properties" => $properties, "docs" => $field['docs'] ?? null, "keywords" => $field['keywords'] ?? null];
        }

        return $output;
    }
}
