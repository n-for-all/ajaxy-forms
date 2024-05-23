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
        add_submenu_page('ajaxy-forms', __('Form Builder', AJAXY_FORMS_TEXT_DOMAIN), __('New Form', AJAXY_FORMS_TEXT_DOMAIN), 'activate_plugins', 'ajaxy-form', [$this, "form_page_handler"]);
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
            \wp_redirect(admin_url(sprintf('admin.php?page=ajaxy-forms&message=%s', urlencode(__('Form deleted successfully', AJAXY_FORMS_TEXT_DOMAIN)))));
            exit();
        }

        $this->metaboxes();
    }

    function scripts()
    {
        \wp_enqueue_script(AJAXY_FORMS_TEXT_DOMAIN . "-admin-script", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/js/builder.js', ['jquery', 'backbone', 'jquery-ui-draggable'], AJAXY_FORMS_VERSION, true);
        // \wp_enqueue_script(AJAXY_FORMS_TEXT_DOMAIN . "-admin-actions-script", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/js/actions.js', ['jquery', 'backbone', 'jquery-ui-draggable'], AJAXY_FORMS_VERSION, true);
        wp_localize_script(
            AJAXY_FORMS_TEXT_DOMAIN . "-admin-script",
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
        <ul class="af-fields">
            <?php foreach ($commonFields as $key => $field) :
                $value = \explode('\\', trim($field['label']));
                $nlabel = str_replace('Type', '', $value[count($value) - 1]);
            ?>
                <li class="draggable" data-type="<?php echo $key; ?>">
                    <span><?php echo $nlabel; ?></span>
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 12H12M12 12H18M12 12V18M12 12V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </li>
            <?php endforeach; ?>
            <li>
                <ul class="af-all-fields">
                    <?php foreach ($fields as $key => $field) :
                        if ($field['common']) continue;
                        $value = \explode('\\', trim($field['label']));
                        $nlabel = str_replace('Type', '', $value[count($value) - 1]);
                    ?>
                        <li class="draggable" data-type="<?php echo $key; ?>">
                            <span><?php echo $nlabel; ?></span>
                            <a href="#">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 12H12M12 12H18M12 12V18M12 12V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li class="more"><span>Load More</span></li>
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
            \wp_redirect(admin_url(sprintf('admin.php?page=ajaxy-form&message=%s', urlencode(__('Form deleted successfully', AJAXY_FORMS_TEXT_DOMAIN)))));
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
                echo '<script>var form_metadata = ' . $form['metadata'] . '</script>';
                $form['metadata'] = $metadata;
            } catch (\Exception $e) {
                $metadata = null;
            }
        } else {
            $action = 'new';
        }

        $nonce = 'save_form_' . ($select_id ? $select_id : 'new');
        if (isset($_POST['save_form'])) {
            if (!wp_verify_nonce($_REQUEST['_wpnonce'] ?? '', $nonce)) {
                $message = [
                    'type' => 'error',
                    'message' => __('Nonce verification failed, Please try again.', AJAXY_FORMS_TEXT_DOMAIN),
                ];
            } else {
                $fields = [];
                if (isset($_POST['fields'])) {
                    $fields = $_POST['fields'];

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
                            if (isset($field[$property['name']]) && ($field[$property['name']] === $default || intval($field[$property['name']]) === $default)) {
                                unset($field[$property['name']]);
                            }
                        }

                        return $field;
                    }, $fields);
                }

                $metadata = [
                    'fields' =>  $fields,
                    'options' => $_POST['options'] ?? [],
                    'initial_data' => [],
                ];

                $name = \sanitize_title($_POST['name'] ?? '');
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

                if ($select_id) {
                    \wp_redirect(
                        add_query_arg(
                            array(
                                'action' => 'edit',
                                'tab'   => $tab,
                                'form'   => $select_id,
                                'message' => urlencode(__('Form saved successfully', AJAXY_FORMS_TEXT_DOMAIN)),
                            ),
                            admin_url('admin.php?page=ajaxy-form')
                        )
                    );
                }
            }
        }


    ?>
        <div class="wrap af-form-wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Form'); ?></h1>
            <hr class="wp-header-end">
            <?php if ($message) : ?>
                <div id="message" class="notice notice-<?php echo $message['type']; ?>">
                    <p><?php echo $message['message']; ?></p>
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
            </nav>
            <?php switch ($tab) {
                case '1':
                    include(AJAXY_FORMS_PLUGIN_DIR . '/admin/inc/form/templates/actions.tpl.php');
                    break;
                default:
                    if ($action == 'edit') :
            ?>
                        <div class="af-manage">
                            <span>
                                <?php
                                printf(
                                    __('Edit your form below, or <a href="%s">create a new form</a>. Do not forget to save your changes!'),
                                    esc_url(
                                        admin_url('admin.php?page=ajaxy-form')
                                    )
                                );
                                ?>
                                <span class="screen-reader-text">
                                    <?php
                                    /* translators: Hidden accessibility text. */
                                    _e('Click the Save Form button to save your changes.');
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
        $name = $_POST['name'] ?? '';

        if (!$name) {
            wp_send_json([
                'status' => 'error',
                'message' => __('Invalid action name', AJAXY_FORMS_TEXT_DOMAIN),
            ], 200);
            return;
        }

        $form = Data::get_form($_GET['form_id'] ?? '');
        if (!$form) {
            wp_send_json([
                'status' => 'error',
                'message' => __('Form doesn\'t exist or haven\'t been saved yet', AJAXY_FORMS_TEXT_DOMAIN),
            ], 200);
            return;
        }

        if (!wp_verify_nonce($_POST['_wpnonce'], 'ajaxy_forms_action_' . $name)) {
            wp_send_json([
                'status' => 'error',
                'message' => __('Nonce verification failed, Please try again.', AJAXY_FORMS_TEXT_DOMAIN),
            ], 200);
            return;
        }

        // Process your form data (e.g., save it to database, send an email)
        $data = $_POST;  // Access form data
        $saved = Data::update_form_action($form['id'], $name, $data['form_action'] ?? []);
        if ($saved === false) {
            wp_send_json([
                'status' => 'error',
                'message' => __('Failed to save the action', AJAXY_FORMS_TEXT_DOMAIN)
            ], 200);
            return;
        }
        // Return a response (optional)
        $response = [
            'status' => 'success',
            'message' => \sprintf(__('Action "%s" is saved!', AJAXY_FORMS_TEXT_DOMAIN), $name),
        ];
        wp_send_json($response);
    }
}
