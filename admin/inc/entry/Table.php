<?php


namespace Ajaxy\Forms\Admin\Inc\Entry;

use Ajaxy\Forms\Inc\Data;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Table extends \WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'entry',
            'plural' => 'entries',
        ));
    }

    /**
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Form', "ajaxy-forms"),
            'data' => __('Data', "ajaxy-forms"),
            // 'metadata' => __('Metadata', "ajaxy-forms"),
            'created' => __('Date', "ajaxy-forms"),
        );
        return $columns;
    }

    /**
     * @global string $comment_status
     * @global string $comment_type
     *
     * @param string $which
     */
    protected function extra_tablenav($which)
    {
        static $has_items;

        if (!isset($has_items)) {
            $has_items = $this->has_items();
        }

        switch ($which) {
            case 'top':
                $forms = Data::get_database_forms(1, 'created', 'desc', 100000);
                $selected = isset($_GET['form']) ? $_GET['form'] : 0;

                $registered_forms = array_keys(Data::get_forms());
?>
                <div class="alignleft actions">
                    <select name="form" id="filter-by-form">
                        <option <?php selected($selected, 0); ?> value=""><?php esc_html_e('Select a Form', 'ajaxy-forms'); ?></option>
                            <?php
                            if ($forms) {
                            ?>
                                <optgroup label="Database">
                                    <?php
                                    foreach ($forms as $form) {
                                        printf(
                                            "<option %s value='%s'>%s</option>\n",
                                            selected($selected, $form['name'], false),
                                            esc_attr($form['name']),
                                            esc_html(\ucwords($form['name']))
                                        );
                                    }
                                    ?>
                                </optgroup>
                            <?php
                            }
                            ?>
                            <?php
                            if ($registered_forms && count($registered_forms) > 0) {
                            ?>
                                <optgroup label="Hardcoded">
                                    <?php
                                    foreach ($registered_forms as $form) {
                                        printf(
                                            "<option %s value='%s'>%s</option>\n",
                                            selected($selected, $form, false),
                                            esc_attr($form),
                                            \esc_html(\ucwords($form))
                                        );
                                    }
                                    ?>
                                </optgroup>
                            <?php
                            }
                            ?>
                    </select>
                    <button class="button" type="submit"><?php esc_html_e('Filter', 'ajaxy-forms'); ?></button>
                    <?php
                    if ($this->has_items()) {
                        echo \sprintf('<a href="%s" target="_blank" class="button apply">%s</a>', esc_url(add_query_arg('export_action', $_GET['form'] ?? 'all')), esc_html(__('Export to Excel', 'ajaxy-forms')));
                    }
                    ?>
                </div>
<?php
                break;
            default:
                break;
        }
    }

    /**
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
            'created' => array('created', true),
        );
        return $sortable_columns;
    }

    /**
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
        $actions = array(
            'view' => sprintf('<a href="?page=ajaxy-forms-entry&id=%s">%s</a>', $item['id'], __('View', "ajaxy-forms")),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', "ajaxy-forms")),
        );

        return sprintf(
            '%s %s',
            \ucwords($item['name']),
            $this->row_actions($actions)
        );
    }
    /**
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_data($item)
    {
        if (!isset($item['data'])) {
            return '';
        }

        return self::convert_to_fields_table(json_decode($item['data'], true));
    }

    function column_metadata($item)
    {
        if (!isset($item['metadata'])) {
            return '';
        }

        return self::convert_to_table(json_decode($item['metadata'], true));
    }

    public function column_created($item)
    {
        $output = '';
        $metadata = $item['metadata'] ?? null;
        $default = 'Submitted By Anonymous';
        if ($metadata) {
            $metadata = json_decode($metadata, true);
            if (isset($metadata['user'])) {
                $user = $metadata['user'];
                if (!empty($user)) {
                    $output .= sprintf(
                        'Submitted By <a href="%1$s">%2$s</a>',
                        esc_url(
                            add_query_arg(
                                'wp_http_referer',
                                urlencode(wp_unslash($_SERVER['REQUEST_URI'])),
                                self_admin_url('user-edit.php?user_id=' . $user['id'])
                            )
                        ),
                        ucwords($user['display_name'])
                    );
                } else {
                    $output .= $default;
                }
            } else {
                $output .= $default;
            }

            if (isset($metadata['ip'])) {
                $output .= sprintf('<br/>IP: <a target="_blank" href="https://whatismyipaddress.com/ip/%1$s">%1$s</a>', $metadata['ip']);
            }
        } else {
            $output .= $default;
        }

        $output .= "<br/>";
        $datetime = $this->parse_datetime($item['created']);

        if (false === $datetime) {
            return $output;
        }

        $t_time = sprintf(
            /* translators: 1: date, 2: time */
            __('%1$s at %2$s', "ajaxy-forms"),
            /* translators: date format, see https://www.php.net/date */
            $datetime->format(__('Y/m/d', "ajaxy-forms")),
            /* translators: time format, see https://www.php.net/date */
            $datetime->format(__('g:i a', "ajaxy-forms"))
        );

        return $output . $t_time;
    }

    /**
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function process_bulk_action()
    {
        if ('delete' === $this->current_action()) {
            $ids = (array)isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            $table_name = Data::delete_entries($ids);
        }
    }

    /**
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        $per_page = 10; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();

        $form = isset($_REQUEST['form']) ? \sanitize_text_field($_REQUEST['form']) : null;
        // will be used in pagination settings
        $total_items = Data::count_entries($form);
        if ($total_items === null || \is_wp_error($total_items)) {
            global $wpdb;
            add_action('admin_notices', function () {
                $class = 'notice notice-error';
                $message = __('An error has occurred.', "ajaxy-forms");

                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
            });

            \printf('<div id="message" class="notice notice-error"><p>Error: %s - %s</p></div>', esc_html($wpdb->last_error), 'Please activate and deactivate the plugin to reinstall the tables');
        }

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']) : 1;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'created';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        
        
        $this->items = Data::get_entries($form, $paged, $orderby, $order, $per_page);
        $this->set_pagination_args(array(
            'total_items' => intval($total_items), // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }

    public static function convert_to_table($data)
    {
        if (!$data) {
            return '';
        }
        $table = '<table class="fixed widefat striped ajaxy-data-table">';
        foreach ($data as $key => $value) {
            $table .= '<tr valign="top">';
            if (!is_numeric($key)) {
                $table .= '<td><strong>' . $key . ':</strong></td><td>';
            } else {
                $table .= '<td colspan="2">';
            }
            if (is_object($value) || is_array($value)) {
                $table .= self::convert_to_table($value);
            } else {
                $table .= $value;
            }
            $table .= '</td></tr>';
        }
        $table .= '</table>';
        return $table;
    }

    public static function convert_to_fields_table($data)
    {
        if (!$data) {
            return '';
        }

        $table = '<table class="fixed widefat striped ajaxy-data-table">';
        foreach ($data as $key => $field) {
            $table .= '<tr valign="top">';
            $table .= '<td><strong>' . (!is_array($field) ? $key : $field['label']) . ':</strong></td>';
            if (!is_array($field)) {
                $table .= '<td>' . $field . '</td>';
            } elseif (is_object($field['value_label']) || is_array($field['value_label'])) {
                $table .= sprintf('<td>%s</td>', \implode(', ', $field['value_label']));
            } else {
                $table .=  sprintf('<td>%s</td>', $field['value_label']);
            }
            $table .= '</tr>';
        }
        $table .= '</table>';
        return $table;
    }

    function parse_datetime($time)
    {
        $wp_timezone = wp_timezone();;
        $timezone = $wp_timezone;


        if (empty($time) || '0000-00-00 00:00:00' === $time) {
            return false;
        }

        $datetime = date_create_immutable_from_format('Y-m-d H:i:s', $time, $timezone);

        if (false === $datetime) {
            return false;
        }

        return $datetime->setTimezone($wp_timezone);
    }
}
