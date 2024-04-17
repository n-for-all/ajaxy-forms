<?php

namespace Ajaxy\Forms\Inc\Email;

class Handler
{
    public $registerForm;
    public $triggers = [];
    public static $instance = null;
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
            
            add_action('init', array(self::$instance,  'register'), 0);
            add_filter('manage_email-template_posts_columns', array(self::$instance, 'columns'), 20);
            add_action('manage_email-template_posts_custom_column', array(self::$instance, 'fill_columns'), 10, 2);

            add_filter('enter_title_here', function ($text, $post) {
                if ($post->post_type == 'email-template') return __('Add Email Subject');
                return $text;
            }, 10, 2);

            $instance = self::$instance;
            add_action(
                'edit_form_after_title',
    
                function () use ($instance) {
                    global $post;
                    if ($post->post_type == 'email-template') {
                        $options = [];
    
                        $o_trigger = get_post_meta($post->ID, '_email_trigger', true);
                        foreach ((array)$instance->triggers as $key => $trigger) {
                            $options[] = sprintf('<option value="%s" %s>%s</option>', $key, $o_trigger == $key ? 'selected="selected"' : '', $trigger);
                        }
    
                        $fields = [];
                        $fields[] = sprintf('<div class="aaa-field aaa-field-%s"><label>%s</label><select name="trigger">%s</select></div>', 'select', __('Send email on:'), implode('', $options));
    
                        $recipients = get_post_meta($post->ID, '_email_recipients', true);
                        $fields[] = sprintf('<div class="aaa-field aaa-field-%s"><label>%s</label><textarea name="recipients">%s</textarea><small>%s</small></div>', 'textarea', __('recipients:'), $recipients, __('*comma separated list of emails'));
    
                        printf(
                            '<div class="aaa-fields-meta">%s</div>',
                            implode('', $fields)
                        );
                    }
                }
            );
            add_action('save_post', array(self::$instance, 'save_post'));
        }
        return self::$instance;
    }
    public function init($triggers)
    {
        $this->triggers = array_merge($this->triggers, $triggers);
    }
    public function register()
    {
        $labels = array(
            'name'                  => _x('Email Templates', 'Post type general name', AJAXY_FORMS_TEXT_DOMAIN),
            'singular_name'         => _x('Email Template', 'Post type singular name', AJAXY_FORMS_TEXT_DOMAIN),
            'menu_name'             => _x('Email Templates', 'Admin Menu text', AJAXY_FORMS_TEXT_DOMAIN),
            'name_admin_bar'        => _x('Email Template', 'Add New on Toolbar', AJAXY_FORMS_TEXT_DOMAIN),
            'add_new'               => __('Add New', AJAXY_FORMS_TEXT_DOMAIN),
            'add_new_item'          => __('Add New Email Template', AJAXY_FORMS_TEXT_DOMAIN),
            'new_item'              => __('New Email Template', AJAXY_FORMS_TEXT_DOMAIN),
            'edit_item'             => __('Edit Email Template', AJAXY_FORMS_TEXT_DOMAIN),
            'view_item'             => __('View Email Template', AJAXY_FORMS_TEXT_DOMAIN),
            'all_items'             => __('Email Templates', AJAXY_FORMS_TEXT_DOMAIN),
            'search_items'          => __('Search Email Templates', AJAXY_FORMS_TEXT_DOMAIN),
            'parent_item_colon'     => __('Parent Email Templates:', AJAXY_FORMS_TEXT_DOMAIN),
            'not_found'             => __('No templates found.', AJAXY_FORMS_TEXT_DOMAIN),
            'not_found_in_trash'    => __('No templates found in Trash.', AJAXY_FORMS_TEXT_DOMAIN),
            'featured_image'        => _x('Email Template Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', AJAXY_FORMS_TEXT_DOMAIN),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', AJAXY_FORMS_TEXT_DOMAIN),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', AJAXY_FORMS_TEXT_DOMAIN),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', AJAXY_FORMS_TEXT_DOMAIN),
            'archives'              => _x('Email Template archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', AJAXY_FORMS_TEXT_DOMAIN),
            'insert_into_item'      => _x('Insert into Email Template', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', AJAXY_FORMS_TEXT_DOMAIN),
            'uploaded_to_this_item' => _x('Uploaded to this Email Template', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', AJAXY_FORMS_TEXT_DOMAIN),
            'filter_items_list'     => _x('Filter templates list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', AJAXY_FORMS_TEXT_DOMAIN),
            'items_list_navigation' => _x('Email Templates list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', AJAXY_FORMS_TEXT_DOMAIN),
            'items_list'            => _x('Email Templates list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', AJAXY_FORMS_TEXT_DOMAIN),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'ajaxy_forms',
            'show_in_rest'       => false,
            'query_var'          => true,
            'has_archive'        => true,
            "menu_position" => 100,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'           => 'dashicons-businesswoman',
            'supports'           => array('title', 'editor', 'author'),
            'capability_type'     => array('post', 'posts'),
            'map_meta_cap' => true
        );  

        register_post_type('email-template', $args);
    }


    public function columns($columns)
    {
        // Remove Author and Comments from Columns and Add custom column 1, custom column 2 and Post Id
        unset(
            $columns['wpseo-score'],
            $columns['wpseo-title'],
            $columns['wpseo-metadesc'],
            $columns['wpseo-focuskw']
        );
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Subject'),
            'trigger' => __('Trigger'),
            'recipients' => __('Recipients'),
            'date' => __('Date')
        );
        //return $columns;
    }

    public function fill_columns($column, $post_id)
    {
        // Fill in the columns with meta box info associated with each post
        switch ($column) {
            case 'recipients':
                $recipients = get_post_meta($post_id, '_email_recipients', true);
                if ($recipients) {
                    echo sprintf('<span class="aaa-email-recipients">%s</span>', $recipients);
                }
                break;
            case 'trigger':
                $trigger = get_post_meta($post_id, '_email_trigger', true);
                if ($trigger) {
                    echo sprintf('<span class="aaa-email-trigger">%s</span>', $trigger);
                }
                break;
            default:
                $data = get_post_meta($post_id, '$column', true);
                if ($data) {
                    echo $data ?? '';
                }
                break;
        }
    }

    function save_post($post_id)
    {
        global $post;
        if (is_single() && $post->post_type != 'email-template') {
            return;
        }

        $trigger = $_POST['trigger'] ?? '';
        $recipients = $_POST['recipients'] ?? '';
        update_post_meta($post_id, '_email_trigger', $trigger);
        update_post_meta($post_id, '_email_recipients', $recipients);
    }
}
