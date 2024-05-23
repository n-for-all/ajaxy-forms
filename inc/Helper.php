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
        $twig = new \Twig\Environment($loader);
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

    public static function create_table($data, Form $form)
    {
        $fields = $form->get_fields();
        $output = [];

        foreach ($fields as $field) {
            $label = $field['label'] && !empty($field['label']) ? $field['label'] : '';
            $output[] = [
                $label ? $label : $field['attr']['placeholder'] ?? '', $data[$field['name']] ?? ''
            ];
        }

        return Helper::to_html($output);
    }
}
