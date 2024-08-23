<?php

namespace Ajaxy\Forms\Admin\Inc\Form;

class Settings
{
    /**
     * Initialize the class and set its properties.
     */
    public function __construct()
    {
        // add_action('admin_init', [$this, 'admin_init']);
        add_action('admin_menu', [$this, 'admin_menu'], 9);
        add_action('admin_enqueue_scripts', array(&$this, 'scripts'));
    }

    function admin_menu()
    {
        add_menu_page(__('Forms', "ajaxy-forms"), __('Forms', "ajaxy-forms"), 'activate_plugins', 'ajaxy-forms', [$this, "page_handler"]);
        add_submenu_page('ajaxy-forms', __('Forms', "ajaxy-forms"), __('Forms', "ajaxy-forms"), 'activate_plugins', 'ajaxy-forms', [$this, "page_handler"]);
    }

    function scripts()
    {
        wp_enqueue_style("ajaxy-forms" . "-admin-style", AJAXY_FORMS_PLUGIN_URL . '/admin/assets/css/styles.css', [], "1.0");
    }

    function page_handler()
    {
        $table = new Table();
        $table->prepare_items();

        $message = '';
        if ('delete' === $table->current_action()) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Entries deleted: %d', "ajaxy-forms"), count($_REQUEST['id'])) . '</p></div>';
        }
?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h1 class="wp-heading-inline"><?php esc_html_e('Forms', "ajaxy-forms") ?>
                <a class="add-new-h2" href="<?php echo esc_attr(get_admin_url(get_current_blog_id(), 'admin.php?page=ajaxy-form&action=new')); ?>"><?php esc_html_e('Add new', "ajaxy-forms") ?></a>
            </h1>
            <?php echo esc_html($message); ?>

            <form id="ajaxy-forms-table" method="GET">
                <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']) ?>" />
                <?php $table->display() ?>
            </form>
        </div>
<?php
    }
}
