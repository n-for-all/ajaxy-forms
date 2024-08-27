<?php

namespace Ajaxy\Forms\Admin\Inc\Entry;

use Ajaxy\Forms\Inc\Helper;

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
        add_submenu_page('ajaxy-forms', __('Entries', "ajaxy-forms"), __('Entries', "ajaxy-forms"), 'activate_plugins', 'ajaxy-form-entries', [$this, "page_handler"]);
        add_submenu_page('ajaxy-forms-entry', __('Entries', "ajaxy-forms"), __('Entries', "ajaxy-forms"), 'activate_plugins', 'ajaxy-forms-entry', [$this, "form_page_handler"]);
    }

    function admin_init()
    {
        if (isset($_REQUEST['export_action'])) {
            \Ajaxy\Forms\Inc\Data::export($_REQUEST['export_action'] == 'all' ? null : $_REQUEST['export_action']);
        }
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
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Forms deleted: %d', "ajaxy-forms"), count((array)$_REQUEST['id'])) . '</p></div>';
        }
?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h1 class="wp-heading-inline"><?php esc_html_e('Entries', 'ajaxy-forms') ?></h1>
            <?php echo wp_kses($message, ['div' => ['id' => [], 'class' => []], 'p' => [], 'a' => []]); ?>
            <form id="ajaxy-forms-table" method="GET">
                <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']) ?>" />
                <?php $table->display() ?>
            </form>

        </div>
    <?php
    }

    function form_page_handler()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'form_entries'; // do not forget about tables prefix

        $message = '';
        $notice = '';

        // this is default $item which will be used for new records
        $default = array(
            'id' => 0,
            'name' => '',
            'email' => '',
            'age' => null,
        );

        // here we are verifying does this request is post back and have correct nonce
        if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
            // combine our default item with request params
            $item = shortcode_atts($default, $_REQUEST);
            // validate data, and if all ok save item to database
            // if id is zero insert otherwise update
            $item_valid = $this->validate($item);
            if ($item_valid === true) {
                if ($item['id'] == 0) {

                    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
                    $result = $wpdb->insert($table_name, $item);
                    $item['id'] = $wpdb->insert_id;
                    if ($result) {
                        $message = __('Item was successfully saved', "ajaxy-forms");
                    } else {
                        $notice = __('There was an error while saving item', "ajaxy-forms");
                    }
                } else {

                    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                    if ($result) {
                        $message = __('Item was successfully updated', "ajaxy-forms");
                    } else {
                        $notice = __('There was an error while updating item', "ajaxy-forms");
                    }
                }
            } else {
                // if $item_valid not true it contains error message(s)
                $notice = $item_valid;
            }
        } else {
            // if this is not post back we load item to edit or give new one to create
            $item = $default;
            if (isset($_REQUEST['id'])) {

                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM %i WHERE id = %s", $table_name, $_REQUEST['id']), ARRAY_A);
                if (!$item) {
                    $item = $default;
                    $notice = __('Item not found', "ajaxy-forms");
                }
            }
        }

        // here we adding our custom meta box
        add_meta_box('ajaxy_forms_entries_meta_box', 'Data', [$this, 'form_meta_box_handler'], 'ajaxy_form_entry', 'normal', 'default');

    ?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h1 class="wp-heading-inline"><?php esc_html_e('Entry', 'ajaxy-forms') ?></h1>

            <?php if (!empty($notice)) : ?>
                <div id="notice" class="error">
                    <p><?php echo esc_html($notice) ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($message)) : ?>
                <div id="message" class="updated">
                    <p><?php echo esc_html($message) ?></p>
                </div>
            <?php endif; ?>

            <form id="form" method="POST">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce(basename(__FILE__))) ?>" />
                <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
                <input type="hidden" name="id" value="<?php echo esc_attr($item['id']) ?>" />

                <div class="metabox-holder" id="poststuff">
                    <div id="post-body">
                        <div id="post-body-content">
                            <?php /* And here we call our custom meta box */ ?>
                            <?php do_meta_boxes('ajaxy_form_entry', 'normal', $item); ?>
                            <!-- <input type="submit" value="<?php esc_html_e('Save', 'ajaxy-forms') ?>" id="submit" class="button-primary" name="submit"> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php
    }


    function validate($item)
    {
        $messages = array();

        // if (empty($item['name'])) $messages[] = __('Name is required', "ajaxy-forms");
        // if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', "ajaxy-forms");
        // if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', "ajaxy-forms");

        if (empty($messages)) return true;
        return implode('<br />', $messages);
    }


    function form_meta_box_handler($item)
    {
    ?>

        <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
            <tbody>
                <tr class="form-field">
                    <th valign="top" scope="row">
                        <label for="name"><?php esc_html_e('Form', 'ajaxy-forms') ?></label>
                    </th>
                    <td>
                        <?php echo esc_html($item['name'] ?? '') ?>
                    </td>
                </tr>
                <tr class="form-field">
                    <th valign="top" scope="row">
                        <label for="email"><?php esc_html_e('Data', 'ajaxy-forms') ?></label>
                    </th>
                    <td>
                        <?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo isset($item['data']) ? Helper::convert_to_fields_table(json_decode($item['data'], true)) : '';
                        ?> 
                    </td>
                </tr>
                <tr class="form-field">
                    <th valign="top" scope="row">
                        <label for="age"><?php esc_html_e('Meta Data', 'ajaxy-forms') ?></label>
                    </th>
                    <td>
                        <?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo isset($item['metadata']) ? Table::convert_to_table(json_decode($item['metadata'], true)) : '';
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
<?php
    }
}
