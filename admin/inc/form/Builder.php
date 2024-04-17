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
    }

    function admin_menu()
    {
        add_submenu_page('ajaxy_forms', __('Add New Form', AJAXY_FORMS_TEXT_DOMAIN), __('Add New Form', AJAXY_FORMS_TEXT_DOMAIN), 'activate_plugins', 'ajaxy_form_new', [$this, "form_page_handler"]);
    }

    function admin_init()
    {
        $this->metaboxes();
    }

    function scripts()
    {
        \wp_enqueue_script(AJAXY_FORMS_TEXT_DOMAIN . "-admin-script-dragdrop", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/js/dragdrop.js', ['jquery'], AJAXY_FORMS_VERSION, true);
        \wp_enqueue_script(AJAXY_FORMS_TEXT_DOMAIN . "-admin-script", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/js/builder.js', [AJAXY_FORMS_TEXT_DOMAIN . "-admin-script-dragdrop", 'jquery', 'backbone'], AJAXY_FORMS_VERSION, true);
        wp_localize_script(AJAXY_FORMS_TEXT_DOMAIN . "-admin-script", 'ajaxyFormsBuilder', array('fields' => \Ajaxy\Forms\Inc\Fields::getInstance()->get_all_properties()));
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
        $fields = \array_unique($fields);
?>
        <ul class="af-fields">
            <?php foreach ($fields as $key => $label) :
                $value = \explode('\\', trim($label));
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
    <?php
    }

    function form_page_handler()
    {

        $menu_count = 10;

        $nav_menu_selected_id = 1;

        $add_new_screen = (isset($_GET['menu']) && 0 === (int) $_GET['menu']) ? true : false;
        $add_new_screen = false;
        $locations_screen = (isset($_GET['action']) && 'locations' === $_GET['action']) ? true : false;

        $num_locations = 0;
        $nav_tab_active_class = '';
        $nav_aria_current     = '';

        $locations      = get_registered_nav_menus();

        if (!isset($_GET['action']) || isset($_GET['action']) && 'locations' !== $_GET['action']) {
            $nav_tab_active_class = ' nav-tab-active';
            $nav_aria_current     = ' aria-current="page"';
        }
    ?>
        <div class="wrap af-form-wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Form'); ?></h1>
            <hr class="wp-header-end">

            <nav class="nav-tab-wrapper wp-clearfix" aria-label="<?php esc_attr_e('Secondary menu'); ?>">
                <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="nav-tab<?php echo $nav_tab_active_class; ?>" <?php echo $nav_aria_current; ?>><?php esc_html_e('Edit Form'); ?></a>
                <?php
                if ($num_locations && $menu_count) {
                    $active_tab_class = '';
                    $aria_current     = '';

                    if ($locations_screen) {
                        $active_tab_class = ' nav-tab-active';
                        $aria_current     = ' aria-current="page"';
                    }
                ?>
                    <a href="<?php echo esc_url(add_query_arg(array('action' => 'locations'), admin_url('nav-menus.php'))); ?>" class="nav-tab<?php echo $active_tab_class; ?>" <?php echo $aria_current; ?>><?php esc_html_e('Manage Locations'); ?></a>
                <?php
                }
                ?>
            </nav>
            <div class="manage-menus">
                <span class="add-edit-menu-action">
                    <?php
                    printf(
                        /* translators: %s: URL to create a new menu. */
                        __('Edit your form below, or <a href="%s">create a new form</a>. Do not forget to save your changes!'),
                        esc_url(
                            add_query_arg(
                                array(
                                    'action' => 'edit',
                                    'menu'   => 0,
                                ),
                                admin_url('nav-menus.php')
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
                <?php

                $metabox_holder_disabled_class = '';

                if (isset($_GET['menu']) && 0 === (int) $_GET['menu']) {
                    $metabox_holder_disabled_class = ' metabox-holder-disabled';
                }
                ?>
            </div><!-- /manage-menus -->
            <div id="nav-menus-frame" class="wp-clearfix">
                <div id="menu-settings-column" class="metabox-holder<?php echo $metabox_holder_disabled_class; ?>">

                    <div class="clear"></div>

                    <form id="nav-menu-meta" class="nav-menu-meta" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="menu" id="nav-menu-meta-object-id" value="<?php echo esc_attr($nav_menu_selected_id); ?>" />
                        <input type="hidden" name="action" value="add-menu-item" />
                        <?php wp_nonce_field('add-menu_item', 'menu-settings-column-nonce'); ?>
                        <h2><?php _e('Add fields'); ?></h2>
                        <?php do_accordion_sections('form-fields', 'side', null); ?>
                    </form>

                </div><!-- /#menu-settings-column -->
                <div id="menu-management-liquid">
                    <div id="menu-management">
                        <form id="update-nav-menu" method="post" enctype="multipart/form-data">
                            <h2><?php _e('Form structure'); ?></h2>
                            <div class="menu-edit">
                                <input type="hidden" name="nav-menu-data">
                                <?php
                                wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
                                wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
                                wp_nonce_field('update-form', 'update-form-nonce');

                                $menu_name_aria_desc = $add_new_screen ? ' aria-describedby="menu-name-desc"' : '';

                                $menu_name_val = 'Test form';
                                ?>
                                <div id="nav-menu-header">
                                    <div class="major-publishing-actions wp-clearfix">
                                        <label class="menu-name-label" for="menu-name"><?php _e('Form Name'); ?></label>
                                        <input name="menu-name" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox form-required" required="required" <?php echo $menu_name_val . $menu_name_aria_desc; ?> />
                                        <div class="publishing-action">
                                            <?php submit_button(empty($nav_menu_selected_id) ? __('Create Form') : __('Save Form'), 'primary large menu-save', 'save_menu', false, array('id' => 'save_menu_header')); ?>
                                        </div><!-- END .publishing-action -->
                                    </div><!-- END .major-publishing-actions -->
                                </div><!-- END .nav-menu-header -->
                                <div id="post-body">
                                    <div id="post-body-content" class="wp-clearfix">
                                        <?php if (!$add_new_screen) : ?>
                                            <?php
                                            $hide_style = '';

                                            if (isset($menu_items) && 0 === count($menu_items)) {
                                                $hide_style = 'style="display: none;"';
                                            }

                                            $starter_copy = __('Drag the items into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.');
                                            ?>
                                            <div class="drag-instructions post-body-plain" <?php echo $hide_style; ?>>
                                                <p><?php echo $starter_copy; ?></p>
                                            </div>
                                        <?php endif;  ?>
                                        <?php $this->fields(); ?>

                                        <div class="menu-settings">
                                            <h3><?php _e('Form Settings'); ?></h3>
                                            <?php
                                            if (!isset($auto_add)) {
                                                $auto_add = get_option('nav_menu_options');

                                                if (!isset($auto_add['auto_add'])) {
                                                    $auto_add = false;
                                                } elseif (false !== array_search($nav_menu_selected_id, $auto_add['auto_add'], true)) {
                                                    $auto_add = true;
                                                } else {
                                                    $auto_add = false;
                                                }
                                            }
                                            ?>

                                            <fieldset class="menu-settings-group auto-add-pages">
                                                <legend class="menu-settings-group-name howto"><?php _e('Auto add pages'); ?></legend>
                                                <div class="menu-settings-input checkbox-input">
                                                    <input type="checkbox" <?php checked($auto_add); ?> name="auto-add-pages" id="auto-add-pages" value="1" /> <label for="auto-add-pages"><?php printf(__('Automatically add new top-level pages to this menu'), esc_url(admin_url('edit.php?post_type=page'))); ?></label>
                                                </div>
                                            </fieldset>


                                            <fieldset class="menu-settings-group menu-theme-locations">
                                                <legend class="menu-settings-group-name howto"><?php _e('Display location'); ?></legend>
                                                <?php
                                                foreach ($locations as $location => $description) :
                                                    $checked = false;

                                                    if (
                                                        isset($menu_locations[$location])
                                                        && 0 !== $nav_menu_selected_id
                                                        && $menu_locations[$location] === $nav_menu_selected_id
                                                    ) {
                                                        $checked = true;
                                                    }
                                                ?>
                                                    <div class="menu-settings-input checkbox-input">
                                                        <input type="checkbox" <?php checked($checked); ?> name="menu-locations[<?php echo esc_attr($location); ?>]" id="locations-<?php echo esc_attr($location); ?>" value="<?php echo esc_attr($nav_menu_selected_id); ?>" />
                                                        <label for="locations-<?php echo esc_attr($location); ?>"><?php echo $description; ?></label>
                                                        <?php if (!empty($menu_locations[$location]) && $menu_locations[$location] !== $nav_menu_selected_id) : ?>
                                                            <span class="theme-location-set">
                                                                <?php
                                                                printf(
                                                                    /* translators: %s: Form name. */
                                                                    _x('(Currently set to: %s)', 'menu location'),
                                                                    wp_get_nav_menu_object($menu_locations[$location])->name
                                                                );
                                                                ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </fieldset>


                                        </div>
                                    </div><!-- /#post-body-content -->
                                </div><!-- /#post-body -->
                                <div id="nav-menu-footer">
                                    <div class="major-publishing-actions">
                                        <div class="publishing-action">
                                            <?php submit_button(empty($nav_menu_selected_id) ? __('Create Form') : __('Save Form'), 'primary large menu-save', 'save_menu', false, array('id' => 'save_menu_footer')); ?>
                                        </div><!-- END .publishing-action -->
                                        <?php if ($menu_count > 0) : ?>

                                            <?php if ($add_new_screen) : ?>
                                                <span class="cancel-action">
                                                    <a class="submitcancel cancellation menu-cancel" href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php _e('Cancel'); ?></a>
                                                </span><!-- END .cancel-action -->
                                            <?php else : ?>
                                                <span class="delete-action">
                                                    <a class="submitdelete deletion menu-delete" href="
									<?php
                                                echo esc_url(
                                                    wp_nonce_url(
                                                        add_query_arg(
                                                            array(
                                                                'action' => 'delete',
                                                                'menu' => $nav_menu_selected_id,
                                                            ),
                                                            admin_url('nav-menus.php')
                                                        ),
                                                        'delete-nav_menu-' . $nav_menu_selected_id
                                                    )
                                                );
                                    ?>
									"><?php _e('Delete Form'); ?></a>
                                                </span><!-- END .delete-action -->
                                            <?php endif; ?>

                                        <?php endif; ?>
                                    </div><!-- END .major-publishing-actions -->
                                </div><!-- /#nav-menu-footer -->
                            </div><!-- /.menu-edit -->
                        </form><!-- /#update-nav-menu -->
                    </div><!-- /#menu-management -->
                </div><!-- /#menu-management-liquid -->
            </div><!-- /#nav-menus-frame -->
        </div><!-- /#nav-menus-frame -->
    <?php
    }


    function fields()
    {
    ?>
        <ul class="ui-sortable droppable" style="min-height: 150px;padding-bottom:1rem">
        </ul>
<?php
    }


    function validate($item)
    {
        $messages = array();

        if (empty($item['name'])) $messages[] = __('Name is required', AJAXY_FORMS_TEXT_DOMAIN);
        if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', AJAXY_FORMS_TEXT_DOMAIN);
        if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', AJAXY_FORMS_TEXT_DOMAIN);
        //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
        //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
        //...

        if (empty($messages)) return true;
        return implode('<br />', $messages);
    }
}
