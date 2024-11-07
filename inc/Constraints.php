<?php

namespace Ajaxy\Forms\Inc;

use Isolated\Symfony\Component\Validator\Constraints as Assert;

class Constraints
{
    private $constraints = array(
        'abstract_comparison' => [
            "class" => Assert\AbstractComparison::class,
            "fields" => [],
            "private" => true,
            "inherited" => ["message"]
        ],
        'all' => [
            "class" => Assert\All::class,
            "docs" => "https://symfony.com/doc/current/reference/constraints/All.html",
            "fields" => [],
            "private" => true,
            "inherited" => []
        ],
        'bic' => [
            "class" => Assert\Bic::class,
            "label" => "Business Identifier Code (BIC)",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Bic.html",
            "fields" => [
                [
                    "name" => "iban",
                    "label" => "IBAN",
                    "type" => "text",
                    "help" => "An IBAN value to validate that its country code is the same as the BIC's one."
                ],
                [
                    "name" => "ibanMessage",
                    "label" => "IBAN message",
                    "type" => "text",
                    "help" => "The message to display when the IBAN is not valid."
                ]
            ],
            "inherited" => ["message"],
            "help" => 'This constraint is used to ensure that a value has the proper format of a <a target="_blank" href="https://en.wikipedia.org/wiki/Business_Identifier_Code">Business Identifier Code (BIC)</a>.'
        ],
        'blank' => [
            "class" => Assert\Blank::class,
            "label" => "Blank",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Blank.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "Validates that a value is blank - meaning equal to an empty string or null"
        ],
        'callback' => [
            "class" => Assert\Callback::class,
            "label" => "Callback",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Callback.html",
            "fields" => [],
            "private" => true,
            "inherited" => ["message"],
            "help" => "create completely custom validation rules and to assign any validation errors to specific fields on your object"
        ],
        'card_scheme' => [
            "class" => Assert\CardScheme::class,
            "label" => "Credit Card Scheme",
            "docs" => "https://symfony.com/doc/current/reference/constraints/CardScheme.html",
            "fields" => [
                [
                    "name" => "schemes",
                    "label" => "Schemes",
                    "type" => "select",
                    "multiple" => true,
                    "options" => [
                        "AMEX" => "AMEX",
                        "CHINA_UNIONPAY" => "CHINA_UNIONPAY",
                        "DINERS" => "DINERS",
                        "DISCOVER" => "DISCOVER",
                        "INSTAPAYMENT" => "INSTAPAYMENT",
                        "JCB" => "JCB",
                        "LASER" => "LASER",
                        "MAESTRO" => "MAESTRO",
                        "MASTERCARD" => "MASTERCARD",
                        "MIR" => "MIR",
                        "UATP" => "UATP",
                        "VISA" => "VISA",
                    ],
                    "help" => "A list of credit card schemes to validate."
                ]
            ],
            "inherited" => ["message"],
            "help" => "This constraint ensures that a credit card number is valid for a given credit card company"
        ],
        'choice' => [
            "class" => Assert\Choice::class,
            "label" => "Choice",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Choice.html",
            "fields" => [
                [
                    "label" => "Choices",
                    "name" => "choices",
                    "type" => "repeater",
                    "fields" => [
                        [
                            "label" => "Value",
                            "type" => "text",
                        ]
                    ],
                    "help" => "A required option - this is the array of options that should be considered in the valid set. The input value will be matched against this array"
                ],
                [
                    "label" => "Max",
                    "name" => "max",
                    "type" => "number",
                    "help" => "If the multiple option is true, then you can use the max option to force no more than XX number of values to be selected"
                ],
                [
                    "label" => "Max Message",
                    "name" => "maxMessage",
                    "type" => "text",
                    "help" => "This is the validation error message that's displayed when the user chooses too many options per the max option.<br/>Default: You must select at most {{ limit }} choices"
                ],
                [
                    "label" => "Match",
                    "name" => "match",
                    "type" => "checkbox",
                    "help" => "When this option is false, the constraint checks that the given value is not one of the values defined in the choices option. In practice, it makes the Choice constraint behave like a NotChoice constraint.",
                    "default" => true
                ],
                [
                    "label" => "Min",
                    "name" => "min",
                    "type" => "number",
                    "help" => "If the multiple option is true, then you can use the min option to force at least XX number of values to be selected"
                ],
                [
                    "label" => "Min Message",
                    "name" => "minMessage",
                    "type" => "text",
                    "help" => "This is the validation error message that's displayed when the user chooses too few choices per the min option.<br/>Default: You must select at least {{ limit }} choices"
                ],
                [
                    "label" => "Multiple",
                    "name" => "multiple",
                    "type" => "checkbox",
                    "help" => "If this option is true, the input value is expected to be an array instead of a single, scalar value. The constraint will check that each value of the input array can be found in the array of valid choices. If even one of the input values cannot be found, the validation will fail.",
                ],
                [
                    "label" => "Multiple Message",
                    "name" => "multipleMessage",
                    "type" => "text",
                    "help" => "This is the message that you will receive if the multiple option is set to true and one of the values on the underlying array being checked is not in the array of valid choices.<br/>Default: One or more of the given values is invalid"
                ]
            ],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is one of a set of choices"
        ],
        'collection' => [
            "class" => Assert\Collection::class,
            "docs" => "https://symfony.com/doc/current/reference/constraints/Collection.html",
            "fields" => [],
            "private" => true,
            "inherited" => []
        ],
        'composite' => [
            "class" => Assert\Composite::class,
            "fields" => [],
            "private" => true,
            "inherited" => ["message"]
        ],
        'count' => [
            "class" => Assert\Count::class,
            "label" => "Count",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Count.html",
            "fields" => [
                [
                    "label" => "Divisible By",
                    "name" => "divisibleBy",
                    "type" => "number",
                    "help" => "Validates that the number of elements of the given collection is divisible by a certain number."
                ],
                [
                    "label" => "Divisible By Message",
                    "name" => "divisibleByMessage",
                    "type" => "text",
                    "help" => "The message that will be shown if the number of elements of the given collection is not divisible by the number defined in the divisibleBy option."
                ],
                [
                    "label" => "Exact Message",
                    "name" => "exactMessage",
                    "type" => "text",
                    "help" => "The message that will be shown if min and max values are equal and the underlying collection elements count is not exactly this value.<br/>Default: This collection should contain exactly {{ limit }} elements."
                ],
                [
                    "label" => "Max",
                    "name" => "max",
                    "type" => "number",
                    "help" => "This option is the \"max\" count value. Validation will fail if the given collection elements count is greater than this max value."
                ],
                [
                    "label" => "Max Message",
                    "name" => "maxMessage",
                    "type" => "text",
                    "help" => "The message that will be shown if the underlying collection elements count is more than the max option.."
                ],
                [
                    "label" => "Min",
                    "name" => "min",
                    "type" => "number",
                    "help" => "This option is the \"min\" count value. Validation will fail if the given collection elements count is less than this min value."
                ],
                [
                    "label" => "Min Message",
                    "name" => "minMessage",
                    "type" => "text",
                    "help" => "The message that will be shown if the underlying collection elements count is less than the min option."
                ]
            ],
            "inherited" => [],
            "help" => "This constraint is used to ensure that the number of elements in a collection is between a certain minimum and maximum value"
        ],
        'country' => [
            "class" => Assert\Country::class,
            "label" => "Country",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Country.html",
            "fields" => [
                [
                    "label" => "Alpha 3",
                    "name" => "alpha3",
                    "type" => "checkbox",
                    "default" => true,
                    "help" => 'If this option is true, the constraint checks that the value is a <a target="_blank" href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3#Current_codes">ISO 3166-1 alpha-3</a> three-letter code (e.g. France = FRA) instead of the default <a target="_blank" href="https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes">ISO 3166-1 alpha-2</a> two-letter code (e.g. France = FR).'
                ]
            ],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is a valid country code"
        ],
        'currency' => [
            "class" => Assert\Currency::class,
            "label" => "Currency",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Currency.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is a valid currency"
        ],
        'date' => [
            "class" => Assert\Date::class,
            "docs" => "https://symfony.com/doc/current/reference/constraints/Date.html",
            "fields" => [],
            "label" => "Date",
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is a valid date"
        ],
        'date_time' => [
            "class" => Assert\DateTime::class,
            "label" => "Date Time",
            "docs" => "https://symfony.com/doc/current/reference/constraints/DateTime.html",
            "fields" => [
                [
                    "label" => "Format",
                    "name" => "format",
                    "type" => "text",
                    "help" => "This option allows you to validate a custom date format<br/>Default: Y-m-d H:i:s"
                ]
            ],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is a valid date and time"
        ],
        'email' => [
            "class" => Assert\Email::class,
            "label" => "Email",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Email.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is a valid email"
        ],
        'equal_to' => [
            "class" => Assert\EqualTo::class,
            "label" => "Equal To",
            "docs" => "https://symfony.com/doc/current/reference/constraints/EqualTo.html",
            "fields" => [
                [
                    "name" => "value",
                    "label" => "Value",
                    "type" => "text",
                    "help" => "This option is required. It defines the comparison value. It can be a string, number or object."
                ]
            ],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is equal to another value"
        ],
        'existence' => [
            "label" => "Existence",
            "class" => Assert\Existence::class,
            "fields" => [],
            "private" => true,
            "inherited" => []
        ],
        'expression' => [
            "class" => Assert\Expression::class,
            "label" => "Expression",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Expression.html",
            "fields" => [
                [
                    "name" => 'expression',
                    "label" => "Expression",
                    "type" => "text",
                    "help" => 'The expression that will be evaluated. If the expression evaluates to a false value (using ==, not ===), validation will fail. Learn more about the <a href="https://symfony.com/doc/current/reference/formats/expression_language.html" target="_blank">expression language syntax</a>.'
                ],
                [
                    "name" => "negate",
                    "label" => "Negate",
                    "type" => "checkbox",
                    "default" => true,
                    "help" => "If false, the validation fails when expression returns true."
                ],
                [
                    "name" => "values",
                    "label" => "Values",
                    "type" => "repeater",
                    "fields" => [
                        [
                            "name" => "key",
                            "label" => "Field",
                            "type" => "text",
                        ], [
                            "name" => "value",
                            "label" => "Value",
                            "type" => "text",
                        ]
                    ],
                    "help" => "The values of the custom variables used in the expression. Values can be of any type (numeric, boolean, strings, null, etc.)"
                ],
            ],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that an expression evaluates to true"
        ],
        'file' => [
            "class" => Assert\File::class,
            "label" => "File",
            "docs" => "https://symfony.com/doc/current/reference/constraints/File.html",
            "fields" => [
                [
                    "name" => "extensions",
                    "label" => "Extensions",
                    "type" => "repeater",
                    "fields" => [
                        [
                            "name" => "key",
                            "label" => "Extension",
                            "type" => "text",
                        ],
                        [
                            "name" => "value",
                            "label" => "Mime Types",
                            "type" => "text",
                            "help" => "This option allows you to validate a custom mime type<br/>Default: keep empty to use only extension"
                        ]
                    ]
                ],
                [
                    "name" => "extensionsMessage",
                    "label" => "Extensions Message",
                    "type" => "text",
                    "help" => "The message displayed if the extension of the file is not a valid extension per the extensions option.<br/>Default: The extension of the file is invalid ({{ extension }}). Allowed extensions are {{ extensions }}"
                ],
                [
                    "name" => "uploadErrorMessage",
                    "label" => "Upload Error Message",
                    "type" => "text",
                    "help" => "The message that is displayed if the uploaded file could not be uploaded for some unknown reason.<br/>Default: The file could not be uploaded."
                ],
                [
                    "name" => "maxSize",
                    "label" => "Max Size",
                    "type" => "text",
                    "help" => "If set, the size of the underlying file must be below this file size in order to be valid. <br/>Default: Configured by system"
                ],
                [
                    "name" => "maxSizeMessage",
                    "label" => "Max Size Message",
                    "type" => "text",
                    "help" => "The message displayed if the file is larger than the maxSize option."
                ],
                [
                    "name" => "filenameMaxLength",
                    "label" => "Filename Max Length",
                    "type" => "text",
                    "help" => "If set, the validator will check that the filename of the underlying file doesn't exceed a certain length."
                ],
                [
                    "name" => "filenameTooLongMessage",
                    "label" => "Filename Too Long Message",
                    "type" => "text",
                    "help" => "The message displayed if the filename of the file exceeds the limit set with the filenameMaxLength option."
                ],
                [
                    "name" => "notFoundMessage",
                    "label" => "Not Found Message",
                    "type" => "text",
                    "help" => "The message displayed if no file can be found at the given path.<br/>Default: The file could not be found."
                ],
                [
                    "name" => "notReadableMessage",
                    "label" => "Not Readable Message",
                    "type" => "text",
                    "help" => "The message displayed if the file is not readable.<br/>Default: The file is not readable."
                ],
                [
                    "name" => "uploadCantWriteErrorMessage",
                    "label" => "Upload Can't Write Error Message",
                    "type" => "text",
                    "help" => "The message that is displayed if the uploaded file can't be stored in the temporary folder.<br/>Default: Cannot write temporary file to disk."
                ],
                [
                    "name" => "disallowEmptyMessage",
                    "label" => "Disallow Empty Message",
                    "type" => "text",
                    "help" => "This constraint checks if the uploaded file is empty (i.e. 0 bytes). If it is, this message is displayed<br/>Default: An empty file is not allowed"
                ],
                [
                    "name" => "uploadFormSizeErrorMessage",
                    "label" => "Upload Form Size Error Message",
                    "type" => "text",
                    "help" => "The message that is displayed if the uploaded file is larger than allowed by the HTML file input field.<br/>Default: The file is too large"
                ],
                [
                    "name" => "uploadIniSizeErrorMessage",
                    "label" => "Upload Ini Size Error Message",
                    "type" => "text",
                    "help" => "The message that is displayed if the uploaded file is larger than the upload_max_filesize php.ini setting.<br/>Default: The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}."
                ],
                [
                    "name" => "uploadNoFileErrorMessage",
                    "label" => "Upload No File Error Message",
                    "type" => "text",
                    "help" => "The message that is displayed if no file was uploaded.<br/>Default: No file was uploaded."
                ],
                [
                    "name" => "uploadPartialErrorMessage",
                    "label" => "Upload Partial Error Message",
                    "type" => "text",
                    "help" => "The message that is displayed if the uploaded file was only partially uploaded.<br/>Default: The uploaded file was only partially uploaded."
                ]
            ],
            "inherited" => [],
            "help" => "This constraint is used to ensure that the uploaded file is valid"
        ],
        'greater_than' => [
            "class" => Assert\GreaterThan::class,
            "label" => "Greater Than",
            "docs" => "https://symfony.com/doc/current/reference/constraints/GreaterThan.html",
            "fields" => [
                [
                    "name" => "value",
                    "label" => "Value",
                    "type" => "text",
                    "help" => "This option is required. It defines the comparison value. It can be a string, number or object."
                ]
            ],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is greater than a given value"
        ],
        'greater_than_or_equal' => [
            "class" => Assert\GreaterThanOrEqual::class,
            "label" => "Greater Than Or Equal",
            "docs" => "https://symfony.com/doc/current/reference/constraints/GreaterThanOrEqual.html",
            "fields" => [
                [
                    "name" => "value",
                    "label" => "Value",
                    "type" => "text",
                    "help" => "This option is required. It defines the comparison value. It can be a string, number or object."
                ]
            ],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is greater than or equal to a given value"
        ],
        'group_sequence' => [
            "class" => Assert\GroupSequence::class,
            "label" => "Group Sequence",
            "fields" => [],
            "private" => true,
            "inherited" => []
        ],
        'group_sequence_provider' => [
            "class" => Assert\GroupSequenceProvider::class,
            "label" => "Group Sequence Provider",
            "fields" => [],
            "private" => true,
            "inherited" => []
        ],
        'iban' => [
            "class" => Assert\Iban::class,
            "label" => "International Bank Account Number (IBAN)",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Iban.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a string is an International Bank Account Number (IBAN)"
        ],
        'identical_to' => [
            "class" => Assert\IdenticalTo::class,
            "label" => "Identical To",
            "docs" => "https://symfony.com/doc/current/reference/constraints/IdenticalTo.html",
            "fields" => [
                [
                    "name" => "value",
                    "label" => "Value",
                    "type" => "text",
                    "help" => "This option is required. It defines the comparison value. It can be a string, number or object."
                ]
            ],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a value is identical to a given value"
        ],
        'image' => [
            "class" => Assert\Image::class,
            "label" => "Image",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Image.html",
            "fields" => [

                [
                    "name" => "mimeTypes",
                    "label" => "Mime Types",
                    "type" => "repeater",
                    "fields" => [
                        [
                            "label" => "Mime Type",
                            "type" => "text",
                            "help" => "The mime type of the image file."
                        ]
                    ],
                    "help" => 'If set, the mime type of the image file must be one of the given mime types. You can find a list of existing image mime types on the <a href="https://www.iana.org/assignments/media-types/media-types.xhtml" target="_blank">IANA website</a>.'
                ],
                [
                    "name" => "mimeTypesMessage",
                    "label" => "Mime Types Message",
                    "type" => "text",
                    "help" => "The message displayed if the mime type of the image file is not one of the given mime types."
                ],
                [
                    "name" => "minHeight",
                    "label" => "Min Height",
                    "type" => "number",
                    "help" => "If set, the height of the image file must be greater than or equal to this value in pixels."
                ],
                [
                    "name" => "minHeightMessage",
                    "label" => "Min Height Message",
                    "type" => "text",
                    "help" => "The message displayed if the height of the image is less than the minHeight option."
                ],
                [
                    "name" => "maxHeight",
                    "label" => "Max Height",
                    "type" => "number",
                    "help" => "If set, the height of the image file must be less than or equal to this value in pixels."
                ],
                [
                    "name" => "maxHeightMessage",
                    "label" => "Max Height Message",
                    "type" => "text",
                    "help" => "The message displayed if the height of the image exceeds the maxHeight option."
                ],
                [
                    "name" => "minWidth",
                    "label" => "Min Width",
                    "type" => "number",
                    "help" => "If set, the width of the image file must be greater than or equal to this value in pixels."
                ],
                [
                    "name" => "minWidthMessage",
                    "label" => "Min Width Message",
                    "type" => "text",
                    "help" => "The message displayed if the width of the image is less than the minWidth option."
                ],
                [
                    "name" => "maxWidth",
                    "label" => "Max Width",
                    "type" => "number",
                    "help" => "If set, the width of the image file must be less than or equal to this value in pixels."
                ],
                [
                    "name" => "maxWidthMessage",
                    "label" => "Max Width Message",
                    "type" => "text",
                    "help" => "The message displayed if the width of the image exceeds the maxWidth option."
                ],
                [
                    "name" => "minPixels",
                    "label" => "Min Pixels",
                    "type" => "number",
                    "help" => "If set, the number of pixels of the image file must be greater than or equal to this value."
                ],
                [
                    "name" => "minPixelsMessage",
                    "label" => "Min Pixels Message",
                    "type" => "text",
                    "help" => "The message displayed if the number of pixels of the image file is less than the minPixels option."
                ],
                [
                    "name" => "maxPixels",
                    "label" => "Max Pixels",
                    "type" => "number",
                    "help" => "If set, the number of pixels of the image file must be less than or equal to this value."
                ],
                [
                    "name" => "maxPixelsMessage",
                    "label" => "Max Pixels Message",
                    "type" => "text",
                    "help" => "The message displayed if the number of pixels exceeds the maxPixels option."
                ],
                [
                    "name" => "minRatio",
                    "label" => "Min Ratio",
                    "type" => "number",
                    "help" => "If set, the ratio of the width and height of the image file must be greater than or equal to this value."
                ],
                [
                    "name" => "minRatioMessage",
                    "label" => "Min Ratio Message",
                    "type" => "text",
                    "help" => "The message displayed if the ratio of the width and height of the image file is less than the minRatio option."
                ],
                [
                    "name" => "maxRatio",
                    "label" => "Max Ratio",
                    "type" => "number",
                    "help" => "If set, the ratio of the width and height of the image file must be less than or equal to this value."
                ],
                [
                    "name" => "maxRatioMessage",
                    "label" => "Max Ratio Message",
                    "type" => "text",
                    "help" => "The message displayed if the ratio of the width and height exceeds the maxRatio option."
                ],
                [
                    "name" => "sizeNotDetectedMessage",
                    "label" => "Size Not Detected Message",
                    "type" => "text",
                    "help" => "The message displayed if the size of the image file could not be detected."
                ],
                [
                    "name" => "allowLandscape",
                    "label" => "Allow Landscape",
                    "type" => "checkbox",
                    "default" => true,
                    "help" => "If this option is false, the image cannot be landscape oriented."
                ],
                [
                    "name" => "allowLandscapeMessage",
                    "label" => "Allow Landscape Message",
                    "type" => "text",
                    "help" => "The message displayed if the image is not landscape oriented."
                ],
                [
                    "name" => "allowPortrait",
                    "label" => "Allow Portrait",
                    "type" => "checkbox",
                    "default" => true,
                    "help" => "If this option is false, the image cannot be portrait oriented."
                ],
                [
                    "name" => "allowPortraitMessage",
                    "label" => "Allow Portrait Message",
                    "type" => "text",
                    "help" => "The message displayed if the image is not portrait oriented."
                ],
                [
                    "name" => "allowSquare",
                    "label" => "Allow Square",
                    "type" => "checkbox",
                    "default" => true,
                    "help" => "If this option is false, the image cannot be square."
                ],
                [
                    "name" => "allowSquareMessage",
                    "label" => "Allow Square Message",
                    "type" => "text",
                    "help" => "The message displayed if the image is not square."
                ],
                [
                    "name" => "detectCorrupted",
                    "label" => "Detect Corrupted",
                    "type" => "checkbox",
                    "help" => "If this option is false, the image is not detected as corrupted."
                ],
                [
                    "name" => "corruptedMessage",
                    "label" => "Corrupted Message",
                    "type" => "text",
                    "help" => "The message displayed if the image is corrupted."
                ],
            ],
            "inherited" => [],
            "help" => "The image validator is used to validate an image file."
        ],
        'ip' => [
            "class" => Assert\Ip::class,
            "label" => "IP",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Ip.html",
            "fields" => [
                [
                    "name" => "version",
                    "label" => "Version",
                    "type" => "select",
                    "options" => [
                        "4" => "IPv4",
                        "6" => "IPv6",
                        "all" => "All"
                    ],
                    "default" => "4",
                ]
            ],
            "inherited" => ["message"],
            "help" => "The IP validator is used to validate an IP address."
        ],
        'is_false' => [
            "class" => Assert\IsFalse::class,
            "label" => "Is False",
            "docs" => "https://symfony.com/doc/current/reference/constraints/IsFalse.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "The Is False validator is used to validate a boolean value."
        ],
        'is_null' => [
            "class" => Assert\IsNull::class,
            "label" => "Is Null",
            "docs" => "https://symfony.com/doc/current/reference/constraints/IsNull.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "The Is Null validator is used to validate a null value."
        ],
        'is_true' => [
            "class" => Assert\IsTrue::class,
            "label" => "Is True",
            "docs" => "https://symfony.com/doc/current/reference/constraints/IsTrue.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "The Is True validator is used to validate a boolean value."
        ],
        'isbn' => [
            "class" => Assert\Isbn::class,
            "label" => "ISBN",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Isbn.html",
            "fields" => [
                [
                    "name" => "bothIsbnMessage",
                    "label" => "Both ISBN Message",
                    "type" => "text",
                    "help" => "The message displayed if both ISBNs are invalid."
                ]
            ],
            "help" => 'This constraint validates that an <a target="_blank" href="https://en.wikipedia.org/wiki/Isbn">International Standard Book Number (ISBN)</a> is either a valid ISBN-10 or a valid ISBN-13.',
            "inherited" => ["message"]
        ],
        'issn' => [
            "class" => Assert\Issn::class,
            "label" => "ISSN",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Issn.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => 'This constraint validates that an <a target="_blank" href="https://en.wikipedia.org/wiki/ISSN">International Standard Serial Number (ISSN)</a> is a valid ISSN.'
        ],
        'language' => [
            "class" => Assert\Language::class,
            "label" => "Language",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Language.html",
            "fields" => [
                [
                    "label" => "Alpha 3",
                    "name" => "alpha3",
                    "type" => "checkbox",
                    "default" => true,
                    "help" => 'If this option is true, the constraint checks that the value is a <a target="_blank" href="https://en.wikipedia.org/wiki/List_of_ISO_639-2_codes">ISO 639-2 (2T)</a> three-letter code (e.g. French = fra) instead of the default <a target="_blank" href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes">ISO 639-1</a> two-letter code (e.g. French = fr).'
                ]
            ],
            "inherited" => ["message"],
            "help" => "The Language validator is used to validate a language."
        ],
        'length' => [
            "class" => Assert\Length::class,
            "label" => "Length",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Length.html",
            "fields" => [
                [
                    "name" => "exactly",
                    "label" => "Exactly",
                    "type" => "number",
                    "help" => "If set, the length of the value must be equal to this value."
                ],
                [
                    "name" => "exactMessage",
                    "label" => "Exact Message",
                    "type" => "text",
                    "help" => "The message displayed if the length of the value is not equal to the exact option."
                ],
                [
                    "name" => "max",
                    "label" => "Max",
                    "type" => "number",
                    "help" => "If set, the length of the value must be less than or equal to this value."
                ],
                [
                    "name" => "maxMessage",
                    "label" => "Max Message",
                    "type" => "text",
                    "help" => "The message displayed if the length of the value is greater than the max option."
                ],
                [
                    "name" => "min",
                    "label" => "Min",
                    "type" => "number",
                    "help" => "If set, the length of the value must be greater than or equal to this value."
                ],
                [
                    "name" => "minMessage",
                    "label" => "Min Message",
                    "type" => "text",
                    "help" => "The message displayed if the length of the value is less than the min option."
                ]
            ],
            "inherited" => [],
            "help" => "The Length validator is used to validate a string length."
        ],
        'less_than' => [
            "class" => Assert\LessThan::class,
            "label" => "Less Than",
            "docs" => "https://symfony.com/doc/current/reference/constraints/LessThan.html",
            "fields" => [
                [
                    "name" => "value",
                    "label" => "Value",
                    "type" => "text",
                    "help" => "This option is required. It defines the comparison value. It can be a string, number or object."
                ]
            ],
            "inherited" => ["message"],
            "help" => "The Less Than validator is used to validate that a value is less than another value."
        ],
        'less_than_or_equal' => [
            "class" => Assert\LessThanOrEqual::class,
            "label" => "Less Than Or Equal",
            "docs" => "https://symfony.com/doc/current/reference/constraints/LessThanOrEqual.html",
            "fields" => [
                [
                    "name" => "value",
                    "label" => "Value",
                    "type" => "text",
                    "help" => "This option is required. It defines the comparison value. It can be a string, number or object."
                ]
            ],
            "inherited" => ["message"],
            "help" => "The Less Than Or Equal validator is used to validate that a value is less than or equal to another value."
        ],
        'locale' => [
            "class" => Assert\Locale::class,
            "label" => "Locale",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Locale.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "The Locale validator is used to validate a locale."
        ],
        'luhn' => [
            "class" => Assert\Luhn::class,
            "label" => "Luhn",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Luhn.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "This constraint is used to ensure that a credit card number passes the <a target=\"_blank\" href=\"https://en.wikipedia.org/wiki/Luhn_algorithm\">Luhn algorithm</a>."
        ],
        'not_blank' => [
            "class" => Assert\NotBlank::class,
            "label" => "Not Blank",
            "docs" => "https://symfony.com/doc/current/reference/constraints/NotBlank.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "The Not Blank validator is used to validate that a value is not blank."
        ],
        'not_equal_to' => [
            "class" => Assert\NotEqualTo::class,
            "label" => "Not Equal To",
            "docs" => "https://symfony.com/doc/current/reference/constraints/NotEqualTo.html",
            "fields" => [
                [
                    "name" => "value",
                    "label" => "Value",
                    "type" => "text",
                    "help" => "This option is required. It defines the comparison value. It can be a string, number or object."
                ]
            ],
            "inherited" => ["message"],
            "help" => "The Not Equal To validator is used to validate that a value is not equal to another value."
        ],
        'not_identical_to' => [
            "class" => Assert\NotIdenticalTo::class,
            "label" => "Not Identical To",
            "docs" => "https://symfony.com/doc/current/reference/constraints/NotIdenticalTo.html",
            "fields" => [
                [
                    "name" => "value",
                    "label" => "Value",
                    "type" => "text",
                    "help" => "This option is required. It defines the comparison value. It can be a string, number or object."
                ]
            ],
            "inherited" => ["message"],
            "help" => "The Not Identical To validator is used to validate that a value is not identical to another value."
        ],
        'not_null' => [
            "class" => Assert\NotNull::class,
            "label" => "Not Null",
            "docs" => "https://symfony.com/doc/current/reference/constraints/NotNull.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "The Not Null validator is used to validate that a value is not null."
        ],
        'optional' => [
            "class" => Assert\Optional::class,
            "private" => true,
            "label" => "Optional",
            "fields" => [],
            "inherited" => ["message"]
        ],
        'range' => [
            "class" => Assert\Range::class,
            "label" => "Range",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Range.html",
            "fields" => [
                [
                    [
                        "name" => "invalidMessage",
                        "label" => "Invalid Message",
                        "type" => "text",
                        "help" => "The message displayed if the value is not valid."
                    ],
                    [
                        "name" => "min",
                        "label" => "Min",
                        "type" => "number",
                        "help" => "If set, the value must be greater than or equal to this value."
                    ],
                    [
                        "name" => "minMessage",
                        "label" => "Min Message",
                        "type" => "text",
                        "help" => "The message displayed if the value is less than the min option."
                    ],
                    [
                        "name" => "max",
                        "label" => "Max",
                        "type" => "number",
                        "help" => "If set, the value must be less than or equal to this value."
                    ],
                    [
                        "name" => "maxMessage",
                        "label" => "Max Message",
                        "type" => "text",
                        "help" => "The message displayed if the value is greater than the max option."
                    ],
                    [
                        "name" => "notInRangeMessage",
                        "label" => "Not In Range Message",
                        "type" => "text",
                        "help" => "The message displayed if the value is not in range."
                    ]
                ]
            ],
            "inherited" => [],
            "help" => "The Range validator is used to validate that a value is in a given range."
        ],
        'regex' => [
            "class" => Assert\Regex::class,
            "label" => "Regex",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Regex.html",
            "fields" => [
                [
                    "name" => "pattern",
                    "label" => "Pattern",
                    "type" => "text",
                    "required" => true,
                    "help" => "This option is required. It defines the regular expression pattern."
                ],
                [
                    "name" => "htmlPattern",
                    "label" => "HTML Pattern",
                    "type" => "text",
                    "help" => "This option is required. It defines the regular expression pattern."
                ],
                [
                    "label" => "Match",
                    "name" => "match",
                    "type" => "checkbox",
                    "help" => "If true (or not set), this validator will pass if the given string matches the given pattern regular expression. However, when this option is set to false, the opposite will occur: validation will pass only if the given string does not match the pattern regular expression.",
                    "default" => true
                ]
            ],
            "inherited" => ["message"],
            "help" => "The Regex validator is used to validate that a string matches a given regular expression."
        ],
        'required' => [
            "class" => Assert\Required::class,
            "label" => "Required",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "The Required validator is used to validate that a value is not null or empty."
        ],
        'time' => [
            "class" => Assert\Time::class,
            "label" => "Time",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Time.html",
            "fields" => [
                [
                    "name" => "withSeconds",
                    "label" => "With Seconds",
                    "type" => "checkbox",
                    "help" => "If true (or not set), this validator will pass if the given value is a valid time with seconds.",
                    "default" => true
                ]
            ],
            "inherited" => ["message"],
            "help" => "The Time validator is used to validate a time."
        ],
        'traverse' => [
            "class" => Assert\Traverse::class,
            "label" => "Traverse",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Traverse.html",
            "fields" => [],
            "private" => true,
            "inherited" => []
        ],
        'type' => [
            "class" => Assert\Type::class,
            "label" => "Type",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Type.html",
            "fields" => [],
            "private" => true,
            "inherited" => ["message"],
            "help" => "The Type validator is used to validate that a value is of a specific data type."
        ],
        'url' => [
            "class" => Assert\Url::class,
            "label" => "URL",
            "docs" => "https://symfony.com/doc/current/reference/constraints/Url.html",
            "fields" => [
                [
                    "name" => "protocols",
                    "label" => "Protocols",
                    "type" => "repeater",
                    "fields" => [
                        [
                            "label" => "Protocol",
                            "type" => "text",
                            "help" => "The protocol of the URL."
                        ]
                    ],
                    "help" => "If set, the URL must start with one of the given protocols."
                ],
                [
                    "name" => "relativeProtocol",
                    "label" => "Relative Protocol",
                    "type" => "checkbox",
                    "help" => "If true, the URL must be a relative URL."
                ]
            ],
            "inherited" => ["message"],
            "help" => "The URL validator is used to validate that a string is a valid URL."
        ],
        'uuid' => [
            "class" => Assert\Uuid::class,
            "label" => "UUID",
            "private" => true,
            "docs" => "https://symfony.com/doc/current/reference/constraints/Uuid.html",
            "fields" => [],
            "inherited" => ["message"],
            "help" => "The UUID validator is used to validate that a string is a valid UUID."
        ],
        'valid' => [
            "class" => Assert\Valid::class,
            "label" => "Valid",
            "private" => true,
            "docs" => "https://symfony.com/doc/current/reference/constraints/Valid.html",
            "fields" => [],
            "inherited" => [],
            "help" => "The Valid validator is used to validate that a value is valid."
        ],
    );
    private $inherited_properties = [
        "message" => [
            "name" => "message",
            "type" => "text",
            "label" => "Message",
            "help" => "This is the default error message",
            "required" => false,
            "order" => 0
        ]
    ];

    static $instance = null;

    /**
     * Create a new constraints instance
     *
     * @date 2024-05-24
     *
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Constraints();
        }
        return self::$instance;
    }

    /**
     * Get constraint by name
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param string $default
     *
     * @return array
     */
    public function get($name, $default = "required")
    {
        return isset($this->constraints[$name]) ? $this->constraints[$name] : ($default ? $this->constraints[$default] : null);
    }

    /**
     * Set a constraint
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param array $values
     *
     * @return void
     */
    public function set($name, $values)
    {
        $this->constraints[$name] = $values;
    }

    /**
     * Check if constraint exist
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->constraints[$name]);
    }

    /**
     * Remove a constraint
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return void
     */
    public function remove($name)
    {
        unset($this->constraints[$name]);
    }

    /**
     * Get all constraints
     *
     * @date 2024-05-24
     *
     * @return array
     */
    public function get_constraints()
    {
        return $this->constraints;
    }

    /**
     * Get inherited properties of a constraint by name
     *
     * @date 2024-05-24
     *
     * @param string $name
     *
     * @return array
     */
    public function get_inherited_properties($name)
    {
        $field = $this->constraints[$name];
        if ($field) {
            $inherited = $field['inherited'] ?? [];
            if (!empty($inherited)) {
                $values = \array_filter(\array_map(function ($value) {
                    return $this->inherited_properties[$value] ?? null;
                }, $inherited), function ($value) {
                    return $value !== null;
                });
                return $values;
            }
        }
        return array();
    }

    /**
     * Get all properties of all constraints
     *
     * @date 2024-05-24
     *
     * @return array
     */
    public function get_all_properties()
    {
        $output = [];

        $fields = $this->get_constraints();
        foreach ($fields as $name => $field) {
            if (isset($field['private']) && $field['private']) {
                continue;
            }

            $values = $this->get_inherited_properties($name);

            unset($field["class"]);
            unset($field['inherited']);
            \array_multisort(\array_column($values, 'order'), SORT_ASC, $values);
            $field['fields'] += $values;
            $output[$name] = $field;
        }

        return $output;
    }

    /**
     * Create a new constraint
     *
     * @date 2024-05-24
     *
     * @param string $constraint
     *
     * @return \Symfony\Component\Validator\Constraint
     * 
     * @throws Exception if constraint is not found
     */
    public function create_constraint($constraint)
    {
        $options = null;
        $message = null;
        $mode = null;
        if (\is_array($constraint)) {
            $type = $constraint['type'] ?? 'not_blank';
            $options = $constraint['options'] ?? null;
            $message = $constraint['message'] ?? null;
            $mode = $constraint['mode'] ?? null;
        } else {
            $type = $constraint;
        }
        $data = preg_split('/(?=[A-Z])/', $type);
        $type = \strtolower(implode('_', array_filter($data)));
        if (isset($this->constraints[$type])) {
            return new $this->constraints[$type]['class']($options, $message, $mode);
        }
        throw new \Exception(\esc_html(sprintf('Constraint %s not found', $constraint)));
    }
}
