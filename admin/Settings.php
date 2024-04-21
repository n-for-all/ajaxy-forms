<?php

namespace Ajaxy\Forms\Admin;

use Ajaxy\Forms\Inc\Email\Handler as EmailHandler;

class Settings
{
    /**
     * Initialize the class and set its properties.
     */
    public function __construct($licensed)
    {
        add_action('admin_init', [$this, 'admin_init']);
        add_action('admin_menu', [$this, 'admin_menu'], 9);
        add_action('admin_enqueue_scripts', array(&$this, 'scripts'));

        // EmailHandler::getInstance(); 
    }

    function admin_menu()
    {
        add_menu_page(__('Forms', AJAXY_FORMS_TEXT_DOMAIN), __('Forms', AJAXY_FORMS_TEXT_DOMAIN), 'activate_plugins', 'ajaxy-forms', [$this, "page_handler"]);
        add_submenu_page('ajaxy-forms', __('Forms', AJAXY_FORMS_TEXT_DOMAIN), __('Forms', AJAXY_FORMS_TEXT_DOMAIN), 'activate_plugins', 'ajaxy-forms', [$this, "page_handler"]);
        // // add new will be described in next part
        // add_submenu_page('ajaxy-forms', __('Add new', AJAXY_FORMS_TEXT_DOMAIN), __('Add new', AJAXY_FORMS_TEXT_DOMAIN), 'activate_plugins', 'ajaxy-forms_form', [$this, 'form_page_handler']);

        
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
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', AJAXY_FORMS_TEXT_DOMAIN), count($_REQUEST['id'])) . '</p></div>';
        }
?>
        <div class="wrap">

            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php _e('Forms', AJAXY_FORMS_TEXT_DOMAIN) ?> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ajaxy-forms'); ?>"><?php _e('Add new', AJAXY_FORMS_TEXT_DOMAIN) ?></a>
            </h2>
            <?php echo $message; ?>

            <form id="ajaxy-forms-table" method="GET">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
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
                    $result = $wpdb->insert($table_name, $item);
                    $item['id'] = $wpdb->insert_id;
                    if ($result) {
                        $message = __('Item was successfully saved', AJAXY_FORMS_TEXT_DOMAIN);
                    } else {
                        $notice = __('There was an error while saving item', AJAXY_FORMS_TEXT_DOMAIN);
                    }
                } else {
                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                    if ($result) {
                        $message = __('Item was successfully updated', AJAXY_FORMS_TEXT_DOMAIN);
                    } else {
                        $notice = __('There was an error while updating item', AJAXY_FORMS_TEXT_DOMAIN);
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
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
                if (!$item) {
                    $item = $default;
                    $notice = __('Item not found', AJAXY_FORMS_TEXT_DOMAIN);
                }
            }
        }

        // here we adding our custom meta box
        add_meta_box('ajaxy_forms_entries_meta_box', 'Data', [$this, 'form_meta_box_handler'], 'ajaxy_forms_form', 'normal', 'default');

    ?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php _e('Form', AJAXY_FORMS_TEXT_DOMAIN) ?> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ajaxy-forms'); ?>"><?php _e('back to list', AJAXY_FORMS_TEXT_DOMAIN) ?></a>
            </h2>

            <?php if (!empty($notice)) : ?>
                <div id="notice" class="error">
                    <p><?php echo $notice ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($message)) : ?>
                <div id="message" class="updated">
                    <p><?php echo $message ?></p>
                </div>
            <?php endif; ?>

            <form id="form" method="POST">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>" />
                <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
                <input type="hidden" name="id" value="<?php echo $item['id'] ?>" />

                <div class="metabox-holder" id="poststuff">
                    <div id="post-body">
                        <div id="post-body-content">
                            <?php /* And here we call our custom meta box */ ?>
                            <?php do_meta_boxes('ajaxy_forms_form', 'normal', $item); ?>
                            <!-- <input type="submit" value="<?php _e('Save', AJAXY_FORMS_TEXT_DOMAIN) ?>" id="submit" class="button-primary" name="submit"> -->
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

        if (empty($item['name'])) $messages[] = __('Name is required', AJAXY_FORMS_TEXT_DOMAIN);
        if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', AJAXY_FORMS_TEXT_DOMAIN);
        if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', AJAXY_FORMS_TEXT_DOMAIN);
        //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
        //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
        //...

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
                        <label for="name"><?php _e('Form', AJAXY_FORMS_TEXT_DOMAIN) ?></label>
                    </th>
                    <td>
                        <?php echo $item['name'] ?? '' ?>
                    </td>
                </tr>
                <tr class="form-field">
                    <th valign="top" scope="row">
                        <label for="email"><?php _e('Data', AJAXY_FORMS_TEXT_DOMAIN) ?></label>
                    </th>
                    <td>
                        <?php echo isset($item['data']) ? Table::convert_to_table(json_decode($item['data'], true)) : ''; ?>
                    </td>
                </tr>
                <tr class="form-field">
                    <th valign="top" scope="row">
                        <label for="age"><?php _e('Meta Data', AJAXY_FORMS_TEXT_DOMAIN) ?></label>
                    </th>
                    <td>
                        <?php echo isset($item['metadata']) ? Table::convert_to_table(json_decode($item['metadata'], true)) : ''; ?>
                    </td>
                </tr>
            </tbody>
        </table>
<?php
    }
}
