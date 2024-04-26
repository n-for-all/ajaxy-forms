<?php

namespace Ajaxy\Forms\Admin\Inc\Form;

class Settings
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
        add_menu_page(__('Forms', AJAXY_FORMS_TEXT_DOMAIN), __('Forms', AJAXY_FORMS_TEXT_DOMAIN), 'activate_plugins', 'ajaxy-forms', [$this, "page_handler"]);
        add_submenu_page('ajaxy-forms', __('Forms', AJAXY_FORMS_TEXT_DOMAIN), __('Forms', AJAXY_FORMS_TEXT_DOMAIN), 'activate_plugins', 'ajaxy-forms', [$this, "page_handler"]);
    }

    function admin_init()
    {
        if (isset($_REQUEST['export_action'])) {
            \Ajaxy\Forms\Inc\Data::export('contact');
        }
    }

    function scripts()
    {
        wp_enqueue_style(AJAXY_FORMS_TEXT_DOMAIN . "-admin-style", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/css/styles.css');
    }

    function page_handler()
    {
        $table = new Table();
        $table->prepare_items();

        $message = '';
        if ('delete' === $table->current_action()) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Entries deleted: %d', AJAXY_FORMS_TEXT_DOMAIN), count($_REQUEST['id'])) . '</p></div>';
        }
?>
        <div class="wrap">

            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h1 class="wp-heading-inline"><?php _e('Forms', AJAXY_FORMS_TEXT_DOMAIN) ?> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ajaxy-form&action=new'); ?>"><?php _e('Add new', AJAXY_FORMS_TEXT_DOMAIN) ?></a>
            </h1>
            <?php echo $message; ?>

            <form id="ajaxy-forms-table" method="GET">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <?php $table->display() ?>
            </form>

        </div>
    <?php
    }

}
