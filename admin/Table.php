<?php


namespace Ajaxy\Forms\Admin;

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
            'name' => __('Form', AJAXY_FORMS_TEXT_DOMAIN),
            'data' => __('Data', AJAXY_FORMS_TEXT_DOMAIN),
            // 'metadata' => __('Metadata', AJAXY_FORMS_TEXT_DOMAIN),
            'created' => __('Date', AJAXY_FORMS_TEXT_DOMAIN),
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

        echo '<div class="alignleft actions">';

        $output = ob_get_clean();

        if (!empty($output) && $this->has_items()) {
            echo $output;
            echo \sprintf('<a href="%s" target="_blank" class="button apply">%s</a>', esc_url(add_query_arg('export_action', 1)), __('Export to Excel'));
        }
        echo '</div>';
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
            'edit' => sprintf('<a href="?page=ajaxy_forms_form&id=%s">%s</a>', $item['id'], __('Edit', AJAXY_FORMS_TEXT_DOMAIN)),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', AJAXY_FORMS_TEXT_DOMAIN)),
        );

        return sprintf(
            '%s %s',
            $item['name'],
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

        return self::convert_to_table(json_decode($item['data'], true));
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
            __('%1$s at %2$s', \AJAXY_FORMS_TEXT_DOMAIN),
            /* translators: date format, see https://www.php.net/date */
            $datetime->format(__('Y/m/d', \AJAXY_FORMS_TEXT_DOMAIN)),
            /* translators: time format, see https://www.php.net/date */
            $datetime->format(__('g:i a', \AJAXY_FORMS_TEXT_DOMAIN))
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
        global $wpdb;
        $table_name = $wpdb->prefix . 'form_entries'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    /**
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'form_entries'; // do not forget about tables prefix

        $per_page = 5; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        if ($total_items === null || \is_wp_error($total_items)) {
            echo $wpdb->last_error;

            add_action('admin_notices', function () {
                $class = 'notice notice-error';
                $message = __('Irks! An error has occurred.', 'sample-text-domain');

                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
            });

            \printf('<div id="message" class="notice notice-error"><p>Error: %s - %s</p></div>', $wpdb->last_error, 'Please activate and deactivate the plugin to reinstall the tables');
            // die();
        }

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);


        // [REQUIRED] configure pagination
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
        $table = '
    <table class="widefat fixed striped ajaxy-data-table">
    ';
        foreach ($data as $key => $value) {
            $table .= '
        <tr valign="top">
        ';
            if (!is_numeric($key)) {
                $table .= '
            <td>
                <strong>' . $key . ':</strong>
            </td>
            <td>
            ';
            } else {
                $table .= '
            <td colspan="2">
            ';
            }
            if (is_object($value) || is_array($value)) {
                $table .= self::convert_to_table($value);
            } else {
                $table .= $value;
            }
            $table .= '
            </td>
        </tr>
        ';
        }
        $table .= '
    </table>
    ';
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
