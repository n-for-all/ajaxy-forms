<?php

namespace Ajaxy\Forms\Inc\Types\Data;

class Terms
{
    /**
     * Get all the posts with their titles as choices.
     *
     * @date 2024-08-21
     *
     * @param string $args
     *
     * @return array
     */
    public static function getAll($args)
    {
        $args = [
            'taxonomy' => $args['taxonomy'],
            'object_ids' => $args['object_ids'],
            'orderby' => $args['orderby'],
            'order' => $args['order'],
            'include' => $args['include'],
            'exclude' => $args['exclude'],
            'exclude_tree' => $args['exclude_tree'],
            'number' => $args['number'],
            'offset' => $args['offset'],
            'child_of' => $args['child_of'],
            'meta_key' => $args['meta_key'],
            'meta_value' => $args['meta_value'],
            'meta_compare' => $args['meta_compare'],
            'hide_empty' => $args['hide_empty'],
            'default_option' => $args['default_option'],
        ];

        $terms = get_terms($args);
        $choices = [];
        if (isset($args['default_option']) && !empty($args['default_option'])) {
            if (\is_numeric($args['default_option'])) {
                $default = array_filter($terms, function ($term) use ($args) {
                    return $term->term_id === (int) $args['default_option'];
                });
                if (empty($default)) {
                    $choices[$args['default_option']] = '';
                } else {
                    $choices[$default[0]->post_title] = $args['default_option'];
                }
            } else {
                $choices[$args['default_option']] = '';
            }
        }
        if (\is_array($terms)) {
            \array_map(function ($term) use (&$choices) {
                $choices[$term->name] = $term->term_id;
            }, $terms);
        }
        return $choices;
    }

    public static function getTaxonomies()
    {
        $taxonomies = get_taxonomies(['public' => true], 'objects');

        $choices = [];
        \array_map(function ($taxonomy) use (&$choices) {
            $choices[$taxonomy->name] = $taxonomy->label;
        }, $taxonomies);
        return $choices;
    }
}
