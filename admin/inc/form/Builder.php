<?php

namespace Ajaxy\Forms\Admin\Inc\Form;

use Ajaxy\Forms\Inc\Data;

class Builder
{
    /**
     * Initialize the class and set its properties.
     */
    public function __construct()
    {
        add_action('admin_init', [$this, 'admin_init']);
        add_action('admin_menu', [$this, 'admin_menu'], 9);
        add_action('admin_enqueue_scripts', array(&$this, 'scripts'));
        add_action('wp_ajax_ajaxy_forms_action', [$this, 'action_ajax_handler']); // For non-logged-in users
    }

    function admin_menu()
    {
        add_submenu_page('ajaxy-forms', __('Form Builder', "ajaxy-forms"), __('New Form', "ajaxy-forms"), 'activate_plugins', 'ajaxy-form', [$this, "form_page_handler"]);
    }

    function admin_init()
    {
        $page = $_GET['page'] ?? '';
        if ($page !== 'ajaxy-form') return;

        $select_id = isset($_GET['form']) ? (int)$_GET['form'] : null;
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if ($action == 'delete') {
            if (!wp_verify_nonce($_REQUEST['_wpnonce'] ?? '', 'delete-form-' . $select_id)) {
                wp_die('You already submitted this delete request, please reload and try again.');
            }

            Data::delete_form($select_id);
            \wp_redirect(admin_url(sprintf('admin.php?page=ajaxy-forms&message=%s', urlencode(__('Form deleted successfully', "ajaxy-forms")))));
            exit();
        }

        $this->metaboxes();
    }

    function scripts()
    {
        \wp_enqueue_script("ajaxy-forms-admin-script", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/js/builder.js', ['jquery', 'backbone', 'jquery-ui-draggable', 'jquery-ui-sortable'], AJAXY_FORMS_VERSION, true);
        // \wp_enqueue_script("ajaxy-forms" . "-admin-actions-script", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/js/actions.js', ['jquery', 'backbone', 'jquery-ui-draggable'], AJAXY_FORMS_VERSION, true);
        wp_localize_script(
            "ajaxy-forms-admin-script",
            'ajaxyFormsBuilder',
            array(
                'fields' => \Ajaxy\Forms\Inc\Fields::getInstance()->get_all_properties(),
                "constraints" => \Ajaxy\Forms\Inc\Constraints::getInstance()->get_all_properties(),
            )
        );
    }

    function metaboxes()
    {
        add_meta_box(
            'form-fields',
            __('Fields'),
            [$this, 'render_metabox'],
            'form-fields',
            'side'
        );
    }

    function render_metabox()
    {
        $fields = \Ajaxy\Forms\Inc\Fields::getInstance()->get_fields();
        // $fields = \array_unique($fields);
        $commonFields = \array_filter($fields, function ($field) {
            return $field['common'];
        });

?>
        <input class="widefat af-search" type="text" placeholder="<?php \esc_html_e('Find a field...', 'ajaxy-forms'); ?>" />
        <ul class="af-fields">
            <?php foreach ($commonFields as $key => $field) :
                $value = \explode('\\', trim($field['label']));
                $label = str_replace('Type', '', $value[count($value) - 1]);
                $keywords = $field['label'] . ',' . $field['keywords'];
            ?>
                <li class="draggable" data-group="common" data-type="<?php echo esc_attr($key); ?>" data-keywords="<?php echo esc_attr($keywords); ?>">
                    <span><?php echo \esc_html($label); ?></span>
                </li>
            <?php endforeach; ?>
            <li class="more"><span><?php \esc_html_e('Show Advanced Fields', 'ajaxy-forms'); ?></span></li>
            <?php foreach ($fields as $key => $field) :
                if ($field['common']) continue;
                $value = \explode('\\', trim($field['label']));
                $label = str_replace('Type', '', $value[count($value) - 1]);
                $keywords = $field['label'] . ',' . $field['keywords'];
            ?>
                <li class="draggable" style="display: none" data-group="advanced" data-type="<?php echo esc_attr($key); ?>" data-keywords="<?php echo esc_attr($keywords); ?>">
                    <span><?php echo \esc_html($label); ?></span>
                </li>
            <?php endforeach; ?>

        </ul>
    <?php
    }

    function form_page_handler()
    {
        $select_id = isset($_GET['form']) ? (int)$_GET['form'] : null;
        $message = isset($_GET['message']) ? ["type" => "success", "message" => $_GET['message']] : '';
        $message = isset($_GET['error']) ? ["type" => "error", "message" => $_GET['error']] : $message;
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if ($action == 'delete') {
            if (!wp_verify_nonce($_REQUEST['_wpnonce'] ?? '', 'delete-form-' . $select_id)) {
                wp_die('You already submitted this delete request, please reload and try again.');
            }

            Data::delete_form($select_id);
            \wp_redirect(admin_url(sprintf('admin.php?page=ajaxy-form&message=%s', urlencode(__('Form deleted successfully', "ajaxy-forms")))));
            exit();
        }

        if (!\in_array($action, ['edit', 'actions', 'new', 'add'])) {
            $action = '';
        }

        $tab = isset($_GET['tab']) ? $_GET['tab'] : 0;

        $form = null;
        $metadata = null;
        if ($select_id) {
            $form = Data::get_form($select_id);
            if (!$form) {
                wp_die('Form not found.');
            }

            try {
                $metadata = \json_decode($form['metadata'], true);
                // echo '<script>var form_metadata = ' . \esc($form['metadata']) . '</script>';
                wp_add_inline_script('ajaxy-forms-admin-script', 'var form_metadata = ' . $form['metadata']);
                $form['metadata'] = $metadata;
            } catch (\Exception $e) {
                $metadata = null;
            }
        } else {
            $action = 'new';
        }

        $nonce = 'save_form_' . ($select_id ? $select_id : 'new');
        if (isset($_POST['save_form'])) {
            $post_vars = array_map('stripslashes_deep', $_POST);
            if (!wp_verify_nonce($_REQUEST['_wpnonce'] ?? '', $nonce)) {
                $message = [
                    'type' => 'error',
                    'message' => __('Nonce verification failed, Please try again.', "ajaxy-forms"),
                ];
            } else {
                $fields = [];
                if (isset($post_vars['fields'])) {
                    $fields = $post_vars['fields'];

                    \usort($fields, function ($a, $b) {
                        return $a['_sort'] <=> $b['_sort'];
                    });

                    $names = [];
                    $index = 1;
                    $fields = \array_map(function ($field) use (&$names, &$index) {
                        unset($field['_sort']);

                        $name = $field['name'] ?? '';
                        if (\trim($name) === '') {
                            $field['name'] = \sanitize_title($field['label']);
                        } else {
                            $field['name'] = \sanitize_title($field['name']);
                        }

                        if (\trim($field['name']) === '') {
                            $field['name'] = 'field_' . ($index++);
                        }

                        if (\in_array($field['name'], $names)) {
                            $field['name'] = 'field_' . \uniqid();
                        }

                        $names[] = $field['name'];

                        $properties = \Ajaxy\Forms\Inc\Fields::getInstance()->get_properties($field['type']);
                        //remove default properties to reduce the settings size
                        foreach ($properties as $key => $property) {
                            $default = $property['default'] ?? '';
                            switch ($property['type']) {
                                case 'radio':
                                case 'checkbox':
                                    $default = intval($property['default'] ?? 0);
                                    break;
                                default:
                                    $default = $property['default'] ?? '';
                                    break;
                            }
                            if ($property['name'] != 'type' && isset($field[$property['name']]) && ($field[$property['name']] === $default || intval($field[$property['name']]) === $default)) {
                                unset($field[$property['name']]);
                            }
                        }

                        return $field;
                    }, $fields);
                }

                $metadata = [
                    'fields' =>  $fields,
                    'options' => $post_vars['options'] ?? [],
                    'initial_data' => [],
                    'theme' => $post_vars['theme'] ?? '',
                ];

                $name = \sanitize_title($post_vars['name'] ?? '');
                if (\trim($name) === '') {
                    $name = 'form_' . \uniqid();
                } else {
                    $old_form = Data::get_form_by_name($name);
                    if ($old_form && $old_form['id'] != $select_id) {
                        $name = 'form_' . \uniqid();
                    }
                }

                if ($select_id) {
                    Data::update_form($select_id, $name, $metadata);
                } else {
                    $select_id = Data::add_form($name, $metadata);
                }

                if (\is_wp_error($select_id)) {
                    $message = [
                        'type' => 'error',
                        'message' => $select_id->get_error_message(),
                    ];
                } else if ($select_id) {
                    \wp_redirect(
                        add_query_arg(
                            array(
                                'action' => 'edit',
                                'tab'   => $tab,
                                'form'   => $select_id,
                                'message' => urlencode(__('Form saved successfully', "ajaxy-forms")),
                            ),
                            admin_url('admin.php?page=ajaxy-form')
                        )
                    );
                } else {
                    $message = [
                        'type' => 'error',
                        'message' => __('Failed to save the form', "ajaxy-forms")
                    ];
                }
            }
        }

    ?>
        <div class="wrap af-form-wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Form', "ajaxy-forms"); ?> <?php echo $form ? sprintf('(%s)', $form['name']) : ''; ?></h1>
            <hr class="wp-header-end">
            <?php if ($message) : ?>
                <div id="message" class="notice notice-<?php echo esc_attr($message['type']); ?>">
                    <p><?php echo \esc_html($message['message']); ?></p>
                </div>
            <?php endif; ?>
            <nav class="nav-tab-wrapper wp-clearfix">
                <a href="<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'tab' => '0',
                                        'action' => $action,
                                        'form'   => $select_id,
                                    ),
                                    admin_url('admin.php?page=ajaxy-form')
                                )
                            ); ?>" class="nav-tab<?php echo !$tab || $tab == 0 ? ' nav-tab-active' : ''; ?>"><?php esc_html_e('Edit Form'); ?></a>
                <?php if ($form): ?>
                    <a href="<?php echo esc_url(
                                    add_query_arg(
                                        array(
                                            'tab' => '1',
                                            'action' => $action,
                                            'form'   => $select_id,
                                        ),
                                        admin_url('admin.php?page=ajaxy-form')
                                    )
                                ); ?>" class="nav-tab<?php echo $tab == 1 ? ' nav-tab-active' : ''; ?>"><?php esc_html_e('Actions'); ?></a>

                    <a href="<?php echo esc_url(
                                    add_query_arg(
                                        array(
                                            'tab' => '2',
                                            'action' => $action,
                                            'form'   => $select_id,
                                        ),
                                        admin_url('admin.php?page=ajaxy-form')
                                    )
                                ); ?>" class="nav-tab<?php echo $tab == 2 ? ' nav-tab-active' : ''; ?>"><?php esc_html_e('Export/Import'); ?></a>
                <?php endif; ?>
            </nav>
            <?php switch ($tab) {
                case '1':
                    if (!$form) {
                        wp_die('Form not found.');
                    }
                    include(AJAXY_FORMS_PLUGIN_DIR . '/admin/inc/form/templates/actions.tpl.php');
                    break;
                case '2':
                    if (!$form) {
                        wp_die('Form not found.');
                    }
                    $isPost = isset($_POST['af-import']);
                    if ($isPost) {
                        try {
                            $json = stripslashes_deep($_POST['json']);
                            $form_json = \json_decode($json, true);
                            if (!$form_json || !is_array($form_json) || !$form_json['fields'] || !\is_array($form_json['fields'])) {
                                throw new \Exception('Invalid JSON');
                            }

                            Data::update_form($select_id, $form['name'], $form_json);

                            $message = [
                                'type' => 'success',
                                'message' => __('Form imported successfully', "ajaxy-forms"),
                            ];
                        } catch (\Exception $e) {
                            $message = [
                                'type' => 'error',
                                'message' => sprintf(__('Failed to import the form. Please make sure the JSON is valid: %s', "ajaxy-forms"), $e->getMessage()),
                            ];
                        }

                        if ($message) : ?>
                            <div id="message" class="notice notice-<?php echo esc_attr($message['type']); ?>">
                                <p><?php echo \esc_html($message['message']); ?></p>
                            </div>
                        <?php endif;
                    }

                    include(AJAXY_FORMS_PLUGIN_DIR . '/admin/inc/form/templates/export.tpl.php');
                    break;
                default:
                    if ($action == 'edit') :
                        ?>
                        <div class="af-manage">
                            <span>
                                <?php
                                echo wp_kses(sprintf(
                                    __('Edit your form below, or <a href="%s">create a new form</a>. Do not forget to save your changes!', "ajaxy-forms"),
                                    esc_url(
                                        admin_url('admin.php?page=ajaxy-form&action=add')
                                    )
                                ), ['a' => ['href' => '']]);
                                ?>
                                <span class="screen-reader-text">
                                    <?php
                                    /* translators: Hidden accessibility text. */
                                    \esc_html_e('Click the Save Form button to save your changes.');
                                    ?>
                                </span>
                            </span>
                        </div>
            <?php
                    endif;
                    include(AJAXY_FORMS_PLUGIN_DIR . '/admin/inc/form/templates/edit.tpl.php');
                    break;
            } ?>

            <div class="af-toast"></div>
        </div>
<?php
    }


    public function action_ajax_handler()
    {
        $post_vars = array_map('stripslashes_deep', $_POST);
        $name = $post_vars['name'] ?? '';

        if (!$name) {
            wp_send_json([
                'status' => 'error',
                'message' => __('Invalid action name', "ajaxy-forms"),
            ], 200);
            return;
        }

        $form = Data::get_form($_GET['form_id'] ?? '');
        if (!$form) {
            wp_send_json([
                'status' => 'error',
                'message' => __('Form doesn\'t exist or haven\'t been saved yet', "ajaxy-forms"),
            ], 200);
            return;
        }

        if (!wp_verify_nonce($post_vars['_wpnonce'], 'ajaxy_forms_action_' . $name)) {
            wp_send_json([
                'status' => 'error',
                'message' => __('Nonce verification failed, Please try again.', "ajaxy-forms"),
            ], 200);
            return;
        }

        $saved = Data::update_form_action($form['id'], $name, $post_vars['form_action'] ?? []);
        if ($saved === false) {
            wp_send_json([
                'status' => 'error',
                'message' => __('Failed to save the action', "ajaxy-forms")
            ], 200);
            return;
        }

        $response = [
            'status' => 'success',
            'message' => \sprintf(__('Action "%s" is saved!', "ajaxy-forms"), $name),
        ];
        wp_send_json($response);
    }
}
