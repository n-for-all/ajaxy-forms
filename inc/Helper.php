<?php

namespace Ajaxy\Forms\Inc;

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
        $loader = new \Twig\Loader\ArrayLoader([]);
        $twig = new \Twig\Environment($loader, ['autoescape' => false]);
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

    public static function parse_submit_data($data, Form $form, $exclude = ['_message', '_token'], $exclude_types = ['submit', 'button', 'recaptcha', 'html'])
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

            if (!$value) {
                continue;
            }

            switch ($field['type']) {
                case 'posts':
                    if (\is_array($data[$field['name']])) {
                        foreach ($data[$field['name']] as $post_id) {
                            $pst = get_post($post_id);
                            $nData[$field['name']]['value_label'][] = $pst && !is_wp_error($pst) ? $pst->post_title : '';
                        }
                    } else {
                        $pst = get_post($data[$field['name']]);
                        $nData[$field['name']]['value_label'] = $pst && !\is_wp_error($pst) ? $pst->post_title : '';
                    }
                    break;
                case 'terms':
                    if (\is_array($data[$field['name']])) {
                        foreach ($data[$field['name']] as $term_id) {
                            $trm = get_term($term_id, $field['taxonomy']);
                            $nData[$field['name']]['value_label'][] = $trm && !\is_wp_error($trm) ? $trm->name : '';
                        }
                    } else {
                        $trm = get_term($data[$field['name']], $field['taxonomy']);
                        $nData[$field['name']]['value_label'] = $trm && !\is_wp_error($trm) ? $trm->name : '';
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

                    $nData[$field['name']]['value_label'] = \implode(' - ', $values);

                    break;
            }
        }

        return $nData;
    }
}
