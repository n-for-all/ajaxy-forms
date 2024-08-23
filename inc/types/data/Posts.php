<?php

namespace Ajaxy\Forms\Inc\Types\Data;

class Posts
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
            'numberposts' => $args['numberposts'],
            'category' => $args['category'],
            'orderby' => $args['orderby'],
            'order' => $args['order'],
            'include' => $args['include'],
            'exclude' => $args['exclude'],
            'meta_key' => $args['meta_key'],
            'meta_value' => $args['meta_value'],
            'post_type' => $args['post_type'],
            'post_status' => $args['post_status'],
            'suppress_filters' => $args['suppress_filters'],
            'default_option' => $args['default_option'],
        ];

        $posts = get_posts($args);
        $choices = [];
        if (isset($args['default_option']) && !empty($args['default_option'])) {
            if (\is_numeric($args['default_option'])) {
                $default = array_filter($posts, function ($post) use ($args) {
                    return $post->ID === (int) $args['default_option'];
                });
                if (empty($default)) {
                    $choices[$args['default_option']] = '';
                }else{
                    $choices[$default[0]->post_title] = $args['default_option'];
                }
            } else {
                $choices[$args['default_option']] = '';
            }
        }
        \array_map(function ($post) use (&$choices) {
            $choices[$post->post_title] = $post->ID;
        }, $posts);
        return $choices;
    }

    public static function getPostTypes()
    {
        $post_types = get_post_types([], 'objects');
        $choices = [];
        \array_map(function ($post_type) use (&$choices) {
            $choices[$post_type->name] = $post_type->label;
        }, $post_types);
        return $choices;
    }
}
