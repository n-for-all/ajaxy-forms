<?php

namespace Ajaxy\Forms\Inc;

use Ajaxy\Forms\Inc\Types\Transformer\UploadedFilesTransformer;
use Ajaxy\Forms\Inc\Types\Transformer\UploadedFileTransformer;
use Isolated\Symfony\Component\Form\CallbackTransformer;
use Isolated\Symfony\Component\Form\Extension\Core\Type\FileType;
use Isolated\Symfony\Component\Form\FormError;
use Isolated\Symfony\Component\Form\FormEvent;
use Isolated\Symfony\Component\Form\FormEvents;
use Isolated\Symfony\Component\Validator\Constraints\All;
use Isolated\Symfony\Component\Validator\Constraints\File;
use Isolated\Twig\Environment;
use Isolated\Twig\Loader\ArrayLoader;

class Helper
{
    /**
     * Create a twig template from a string and render it with parameters
     *
     * @date 2024-05-23
     *
     * @param string $string
     * @param array $parameters
     *
     * @return string
     */
    public static function create_twig_template($string, $parameters)
    {
        $loader = new ArrayLoader([]);
        $twig = new Environment($loader, ['autoescape' => false]);
        $template = $twig->createTemplate($string);
        return $template->render($parameters);
    }

    /**
     * Create a html table from a multi dimensional array of rows and cells
     *
     * @param array $data
     *
     * @return string
     */
    public static function to_html($data = array())
    {
        $rows = array();
        foreach ($data as $row) {
            $cells = array();
            if (!is_array($row)) {
                continue;
            }
            foreach ($row as $cell) {
                if (is_array($cell)) {
                    $cell = implode(', ', $cell);
                }
                $cells[] = "<td style='padding:5px;line-height:22px;'>{$cell}</td>";
            }
            $rows[] = "<tr style='" . (sizeof($rows) % 2 == 0 ? 'background-color:#fafbfc' : '') . "'>" . implode('', $cells) . '</tr>';
        }

        return "<table style='font-size:16px;min-width:300px;width:100%;white-space:pre-wrap;'>" . implode('', $rows) . '</table>';
    }

    /**
     * Create an html table from a multi dimensional array of rows and cells
     *
     * @date 2024-05-23
     *
     * @param array $data
     * @param Form   $form
     *
     * @return string
     */
    public static function create_table($data, Form $form)
    {
        $output = [];

        foreach ($data as $field) {
            if ($field['type'] == 'repeater_start') {
                $innerTables = array_map(function ($item) {
                    return self::convert_to_fields_table($item);
                }, $field['value_label']);
                $output[] = [
                    $field['label'] ?? '',
                    implode('', $innerTables)
                ];
                continue;
            }
            $output[] = [
                $field['label'] ?? '',
                $field['value_label'] ?? $field['value'] ?? ''
            ];
        }

        return Helper::to_html($output);
    }

    public static function is_list(array $arr)
    {
        if ($arr === []) {
            return true;
        }
        return array_keys($arr) === range(0, count($arr) - 1);
    }

    public static function create_tree_list($array, $class = "af-tree")
    {
        $list = '<ul class="' . $class . '">';
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $is_list = self::is_list($value);
                $list .= \sprintf('<li><span class="af-key">%s: </span><span class="af-start-tick">%s</span><span class="af-tick">...</span><span class="af-handle"></span>%s<span class="af-end-tick">%s</span></li>', $key, $is_list ? "[" : "{", self::create_tree_list($value, ""), $is_list ? "]" : "}");
            } else {
                $list .= \sprintf('<li>%s: <span class="af-value">%s</span></li>', $key, htmlentities($value));
            }
        }
        $list .= '</ul>';
        return $list;
    }

    public static function convert_to_file_table($data, $field)
    {

        if (!$data) {
            return '';
        }

        $table = '<div>';
        if (\is_array($data) && !isset($data['url'])) {

            foreach ($data as $file) {
                $table .= '<a style="margin:0 0.2rem 0.2rem" href="' . $file['url'] . '" class="button button-secondary" target="_blank">' . $file['name'] . '</a>';
            }
        } else {
            $table .= '<a href="' . $data['url'] . '" class="button button-secondary" target="_blank">' . $data['name'] . '</a>';
        }

        $table .= '</div>';
        return $table;
    }
    public static function convert_to_fields_table($data)
    {
        if (!$data) {
            return '';
        }

        $table = '<table class="fixed widefat striped ajaxy-data-table">';
        foreach ($data as $key => $field) {
            $table .= '<tr valign="top">';
            $table .= '<td><strong>' . (!is_array($field) ? $key : $field['label']) . ':</strong></td>';
            if (!is_array($field)) {
                $table .= '<td>' . $field . '</td>';
            } elseif (is_object($field['value_label']) || is_array($field['value_label'])) {
                if ($field['type'] == 'repeater_start') {
                    $innerTables = array_map(function ($item) {
                        return self::convert_to_fields_table($item);
                    }, $field['value_label']);
                    $table .= sprintf('<td>%s</td>', implode('', $innerTables));
                } else if ($field['type'] == 'file') {
                    $table .= sprintf('<td>%s</td>', self::convert_to_file_table($field['value'], $field));
                } else {
                    $table .= sprintf('<td>%s</td>', \implode(', ', $field['value_label']));
                }
            } else {
                $table .=  sprintf('<td>%s</td>', $field['value_label']);
            }
            $table .= '</tr>';
        }
        $table .= '</table>';
        return $table;
    }

    public static function get_repeater_fields($name, Form $form, $exclude, $exclude_types)
    {
        $fields = $form->get_fields();
        $start_repeater = false;
        $repeater_fields = [];
        foreach ($fields as $field) {
            if ((in_array($field['type'], $exclude_types) || in_array($field['name'], $exclude)) && !in_array($field['type'], ['repeater_start', 'repeater_end'])) {
                continue;
            }
            if ($field['type'] === 'repeater_start' && $field['name'] == $name) {
                $start_repeater = true;
                continue;
            }
            if ($start_repeater) {
                if ($field['type'] == 'repeater_end') {
                    $start_repeater = false;
                    return $repeater_fields;
                }
                $repeater_fields[] = $field;
            }
        }

        return $repeater_fields;
    }

    public static function get_field_value_label($field, $data)
    {
        $value = isset($data[$field['name']]) ? $data[$field['name']] : $field['default'];
        switch ($field['type']) {
                // case 'file':
                //     if (\is_array($data[$field['name']]) && !isset($data[$field['name']]['url'])) {
                //         $values = [];
                //         foreach ($data[$field['name']] as $file) {
                //             $values[] = [
                //                 $file['url'] ?? ''
                //             ];
                //         }

                //         return $values;
                //     } else {
                //         return $data[$field['name']]['url'] ?? '';
                //     }
            case 'posts':
                if (\is_array($data[$field['name']])) {
                    $values = [];
                    foreach ($data[$field['name']] as $post_id) {
                        $pst = get_post($post_id);
                        $values[] = $pst && !is_wp_error($pst) ? $pst->post_title : '';
                    }

                    return $values;
                } else {
                    $pst = get_post($data[$field['name']]);
                    return $pst && !\is_wp_error($pst) ? $pst->post_title : '';
                }
            case 'terms':
                if (\is_array($data[$field['name']])) {
                    $values = [];
                    foreach ($data[$field['name']] as $term_id) {
                        $trm = get_term($term_id, $field['taxonomy']);
                        $values[] = $trm && !\is_wp_error($trm) ? $trm->name : '';
                    }
                    return $values;
                } else {
                    $trm = get_term($data[$field['name']], $field['taxonomy']);
                    return $trm && !\is_wp_error($trm) ? $trm->name : '';
                }
            case 'term_posts':
                $values = [];
                if (isset($data[$field['name']]['terms'])) {
                    $trm = get_term($data[$field['name']]['terms'], $field['taxonomy']);
                    $values[] = $trm && !\is_wp_error($trm) ? $trm->name : '';
                }

                if (isset($data[$field['name']]['posts'])) {
                    $pst = get_post($data[$field['name']]['posts']);
                    $values[] = $pst && !\is_wp_error($pst) ? $pst->post_title : '';
                }

                return \implode(' - ', $values);
            default:
                return $value;
        }
    }

    public static function parse_submit_data($data, Form $form, $exclude = ['_message', '_token'], $exclude_types = ['submit', 'button', 'recaptcha', 'html', 'repeater_end'])
    {
        $nData = [];
        $fields = $form->get_fields();
        foreach ($fields as $field) {
            if (in_array($field['type'], $exclude_types) || in_array($field['name'], $exclude)) {
                continue;
            }

            $value = isset($data[$field['name']]) ? $data[$field['name']] : $field['default'];
            $label = $field['label'] && !empty($field['label']) ? $field['label'] : null;

            $nData[$field['name']] = [
                'type' => $field['type'],
                'value' => $value,
                'value_label' => $value,
                'label' => $label ? $label : $field['attr']['placeholder'] ?? '',
            ];

            if ($field['type'] == 'repeater_start') {
                $repeater_fields = self::get_repeater_fields($field['name'], $form, $exclude, $exclude_types);
                $indexes = [];
                foreach ($data[$field['name']] as $key => $value) {
                    $index = (int)preg_replace('/.*--(\d+)$/', '$1', $key);
                    $indexes[(string)$index] = $key;
                }

                $keys = array_keys($indexes);
                $values_label = [];
                foreach ($keys as $key) {
                    $innerValues = [];
                    foreach ($repeater_fields as $repeater_field) {
                        $exclude[] = $repeater_field['name'];
                        $repeater_field['name'] = $repeater_field['name'] . '--' . $key;
                        $innerValues[] = [
                            'label' => $repeater_field['label'],
                            'value' => isset($data[$field['name']][$repeater_field['name']]) ? $data[$field['name']][$repeater_field['name']] : $repeater_field['default'],
                            'value_label' => self::get_field_value_label($repeater_field, $data[$field['name']])
                        ];
                    }

                    $values_label[] = $innerValues;
                }

                $nData[$field['name']]['value_label'] = $values_label;
                continue;
            }

            if (!$value) {
                continue;
            }

            $nData[$field['name']]['value_label'] = self::get_field_value_label($field, $data);
        }

        return $nData;
    }

    public static function parse_file_field_options($field_options, $validation = true)
    {
        $mime_types = [];
        if (isset($field_options['mime_types']) && !empty($field_options['mime_types'])) {
            $mime_types = \array_map(function ($mime_type) {
                return trim($mime_type['value']);
            }, $field_options['mime_types']);
        } else {
            $mime_types = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        }


        $field_options = array_filter($field_options, function ($key) {
            return !in_array($key, ['max_size', 'max_size_message', 'extensions', 'extensions_message', 'not_found_message', 'not_readable_message', 'upload_cant_write_error_message', 'upload_error_message', 'upload_form_size_error_message', 'upload_ini_size_error_message', 'upload_no_file_error_message', 'upload_partial_error_message', 'mime_types', 'mime_types_message']);
        }, ARRAY_FILTER_USE_KEY);

        $multiple = $field_options['multiple'] ?? false;

        if ($validation) {
            $constraint = new File(
                [],
                $field_options['max_size'] ? $field_options['max_size'] : null,
                null,
                $mime_types,
                $field_options['not_found_message'] ?? null,
                $field_options['not_readable_message'] ?? null,
                $field_options['max_size_message'] ?? null,
                $field_options['mime_types_message'] ?? null,
                null,
                $field_options['upload_ini_size_error_message'] ?? null,
                $field_options['upload_form_size_error_message'] ?? null,
                $field_options['upload_partial_error_message'] ?? null,
                $field_options['upload_no_file_error_message'] ?? null,
                null,
                $field_options['upload_cant_write_error_message'] ?? null,
                null,
                $field_options['upload_error_message'] ?? null

            );
            if ($multiple) {
                $field_options['constraints'][] = $constraint;
                $field_options['constraints'] = new All($field_options['constraints']);
            } else {
                $field_options['constraints'] = $constraint;
            }
        } else {
            unset($field_options['constraints']);
            unset($field_options['required']);
        }
        return $field_options;
    }
    public static function validate_upload($form, $file, $extensions, $extensions_message, $invalid_message, $required = true)
    {
        if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            if ($extensions && \count($extensions) > 0) {
                if (!in_array(strtolower($file->getClientOriginalExtension()), $extensions)) {
                    $message = \str_replace(['{{ extension }}', '{{ extensions }}'], [$file->getClientOriginalExtension(), implode(', ', $extensions)], $extensions_message ? $extensions_message : 'The extension of the file is invalid ({{ extension }}). Allowed extensions are {{ extensions }}');
                    $form->addError(new FormError($message));
                }
            }
        } else if ($required) {
            $form->addError(new FormError($invalid_message));
        }
    }
    public static function create_file_field($builder, $field, $field_options, $validation = true)
    {
        $field_options = self::parse_file_field_options($field_options, $validation);

        $extensions = $field_options['extensions'] ?? null;
        $extensions_message = $field_options['extensions_message'] ?? null;
        $multiple = $field_options['multiple'] ?? false;
        $required = $field_options['required'] ?? false;

        $invalid_message = $field_options['invalid_message'] ?? __('The file is invalid.', 'ajaxy-forms');
        $builder->add($field['name'], FileType::class, $field_options);
        if ($multiple) {
            $builder->get($field['name'])->addModelTransformer(new UploadedFilesTransformer());
            $builder->get($field['name'])->addEventListener(FormEvents::POST_SUBMIT, function ($event) use ($extensions, $extensions_message, $invalid_message, $required) {
                $form = $event->getForm();
                $data = $form->getData();
                if ($extensions) {
                    $extensions = \array_map(function ($ext) {
                        return trim(strtolower($ext['value']));
                    }, (array)$extensions);
                }
                if ($data && !empty($data)) {
                    foreach ($data as $file) {
                        self::validate_upload($form, $file, $extensions, $extensions_message, $invalid_message, $required);
                    }
                } else if ($required) {
                    $form->addError(new FormError($invalid_message));
                }
            });
        } else {
            $builder->get($field['name'])->addModelTransformer(new UploadedFileTransformer());
            $builder->get($field['name'])->addEventListener(FormEvents::POST_SUBMIT, function ($event) use ($extensions, $extensions_message, $invalid_message, $required) {
                $form = $event->getForm();
                $data = $form->getData();

                self::validate_upload($form, $data, $extensions, $extensions_message, $invalid_message, $required);
            });
        }
    }

    public static function handle_files(&$data)
    {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
        }

        foreach ($data as $key => &$val) {
            if ($val instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {

                $file = array(
                    'name' => $val->getClientOriginalName(),
                    'type' => 'file',
                    'mime' => $val->getClientMimeType(),
                    'tmp_name' => $val->getPathname(),
                    'error' => $val->getError(),
                    'size' => $val->getSize()
                );

                $uploaded_file = wp_handle_upload($file, ['test_form' => false, 'test_size' => false, 'test_type' => false], null);

                if (!isset($uploaded_file['error'])) {
                    unset($file['tmp_name']);
                    unset($file['error']);
                    $file['url'] = $uploaded_file['url'];
                    $data[$key] = $file;
                } else {
                    \error_log($uploaded_file['error']);
                }
            } else if (\is_array($val)) {
                $data[$key] = self::handle_files($val);
            }
        }

        return $data;
    }
}
