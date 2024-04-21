<?php

namespace Ajaxy\Forms\Admin\Inc\Form;

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

        add_action('wp_ajax_af-form-save', [$this, 'save_form'], 10, 2);
    }

    function admin_menu()
    {
        add_submenu_page('ajaxy-forms', __('Add New Form', AJAXY_FORMS_TEXT_DOMAIN), __('Add New Form', AJAXY_FORMS_TEXT_DOMAIN), 'activate_plugins', 'ajaxy-form', [$this, "form_page_handler"]);
    }

    function admin_init()
    {
        $this->metaboxes();
    }

    function scripts()
    {
        \wp_enqueue_script(AJAXY_FORMS_TEXT_DOMAIN . "-admin-script-dragdrop", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/js/dragdrop.js', ['jquery'], AJAXY_FORMS_VERSION, true);
        \wp_enqueue_script(AJAXY_FORMS_TEXT_DOMAIN . "-admin-script", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/js/builder.js', [AJAXY_FORMS_TEXT_DOMAIN . "-admin-script-dragdrop", 'jquery', 'backbone'], AJAXY_FORMS_VERSION, true);
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
        $count = 10;
        $select_id = 1;
        $add_new_screen = (isset($_GET['form']) && 0 === (int) $_GET['form']) ? true : false;
        $add_new_screen = false;

        $action = isset($_GET['action']) ? $_GET['action'] : '';
    ?>
        <div class="wrap af-form-wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Form'); ?></h1>
            <hr class="wp-header-end">

            <nav class="nav-tab-wrapper wp-clearfix">
                <a href="<?php esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'edit',
                                        'form'   => 0,
                                    ),
                                    admin_url('admin.php?page=ajaxy-form')
                                )
                            ); ?>
                            " class="nav-tab<?php echo $action == 'edit' || $action == '' ? ' nav-tab-active' : ''; ?>"><?php esc_html_e('Edit Form'); ?></a>
                <a href="<?php esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'actions',
                                        'form'   => 0,
                                    ),
                                    admin_url('admin.php?page=ajaxy-form')
                                )
                            ); ?>" class="nav-tab<?php echo $action == 'actions' ? ' nav-tab-active' : ''; ?>"><?php esc_html_e('Actions'); ?></a>
            </nav>
            <div class="af-manage">
                <span>
                    <?php
                    printf(
                        __('Edit your form below, or <a href="%s">create a new form</a>. Do not forget to save your changes!'),
                        esc_url(
                            add_query_arg(
                                array(
                                    'action' => 'edit',
                                    'form'   => 0,
                                ),
                                admin_url('admin.php?page=ajaxy-form')
                            )
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
            <div class="wp-clearfix af-frame">
                <div class="settings-column">
                    <div class="clear"></div>
                    <h2><?php _e('Add Fields'); ?></h2>
                    <?php do_accordion_sections('form-fields', 'side', null); ?>
                </div>
                <div class="af-management-liquid">
                    <div class="af-management">
                        <h2><?php _e('Form Structure'); ?></h2>
                        <form action="<?php echo admin_url('admin-ajax.php'); ?>" class="af-edit">
                            <input type="hidden" name="action" value="af-form-save" />
                            <?php
                            ?>
                            <div class="af-header">
                                <div class="major-publishing-actions wp-clearfix">
                                    <label for="form-name"><?php _e('Form Name'); ?></label>
                                    <input name="name" id="form-name" type="text" class="regular-text form-required" required="required" />
                                </div>
                            </div>
                            <div id="post-body">
                                <div id="post-body-content" class="wp-clearfix">
                                    <?php if (!$add_new_screen) : ?>
                                        <?php
                                        $starter_copy = __('Drag the items into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.');
                                        ?>
                                        <div class="drag-instructions post-body-plain">
                                            <p><?php echo $starter_copy; ?></p>
                                        </div>
                                    <?php endif;  ?>
                                    <ul class="ui-sortable droppable"></ul>

                                    <div class="af-settings">
                                        <h3><?php _e('Form Settings'); ?></h3>
                                        <fieldset class="af-settings-group">
                                            <legend class="af-settings-group-name howto"><?php _e('Extra fields'); ?></legend>
                                            <div class="af-settings-input checkbox-input">
                                                <input name="allow_extra_fields" type="checkbox" value="1" /> <label for="auto-add-pages"><?php _e('Allow extra fields'); ?></label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="af-settings-group">
                                            <legend class="af-settings-group-name howto"><?php _e('No Validate'); ?></legend>
                                            <div class="af-settings-input checkbox-input">
                                                <input name="attr[novalidate]" type="checkbox" value="1" /> <label for="auto-add-pages"><?php _e('Disable client side validation'); ?></label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="af-settings-group">
                                            <legend class="af-settings-group-name howto"><?php _e('Class Name'); ?></legend>
                                            <div class="af-settings-input text-input">
                                                <input name="attr[class]" type="text" value="" />
                                            </div>
                                        </fieldset>
                                        <fieldset class="af-settings-group">
                                            <legend class="af-settings-group-name howto"><?php _e('Method'); ?></legend>
                                            <div class="af-settings-input text-input">
                                                <select name="method">
                                                    <option value="post">POST</option>
                                                    <option value="get">GET</option>
                                                    <option value="put">PUT</option>
                                                    <option value="delete">DELETE</option>
                                                    <option value="patch">PATCH</option>
                                                </select>
                                            </div>
                                        </fieldset>
                                        <fieldset class="af-settings-group">
                                            <legend class="af-settings-group-name howto"><?php _e('Submission'); ?></legend>
                                            <div class="af-settings-input text-input">
                                                <select name="submission">
                                                    <option value="1">Ajax</option>
                                                    <option value="0">Normal</option>
                                                </select>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="af-footer">
                                <div class="major-publishing-actions">
                                    <div class="publishing-action">
                                        <?php submit_button(empty($select_id) ? __('Create Form') : __('Save Form'), 'primary large form-save', 'save_form', false); ?>
                                    </div>
                                    <?php if ($count > 0) : ?>
                                        <?php if (!$add_new_screen) : ?>
                                            <span class="delete-action">
                                                <a class="submitdelete deletion af-delete" href="
									<?php
                                            echo esc_url(
                                                wp_nonce_url(
                                                    add_query_arg(
                                                        array(
                                                            'action' => 'delete',
                                                            'form' => $select_id,
                                                        ),
                                                        admin_url('admin.php?page=ajaxy-form')
                                                    ),
                                                    'delete-nav_form-' . $select_id
                                                )
                                            );
                                    ?>
									"><?php _e('Delete Form'); ?></a>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            jQuery(function(jQuery) {
                jQuery('.af-edit').on('submit', function(e) {
                    e.preventDefault();
                    var data = jQuery(this).serialize();
                    jQuery.post(ajaxurl, data, function(response) {
                        console.log(response);
                    });
                });
            });
        </script>
<?php
    }

    public function save_form()
    {
        \print_r($_POST);

        die();
    }
}
