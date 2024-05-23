<?php


namespace Ajaxy\Forms\Admin\Inc\Form;

use Ajaxy\Forms\Inc\Data;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Table extends \WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'form',
            'plural' => 'forms',
        ));

        add_action('admin_footer', array($this, '_script'));
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
            'shortcode' => __('Shortcode', AJAXY_FORMS_TEXT_DOMAIN),
            'entries' => __('Entries', AJAXY_FORMS_TEXT_DOMAIN),
            'metadata' => __('Metadata', AJAXY_FORMS_TEXT_DOMAIN),
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
        return $item[$column_name] ?? '';
    }

    function column_entries($item)
    {
        return \sprintf('(%s) - <a href="%s">View Entries</a>', Data::count_entries($item['name']), admin_url('admin.php?page=ajaxy-form-entries&form=' . $item['name']));
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
        $edit_url = esc_url(
            add_query_arg(
                array(
                    'action' => 'edit',
                    'form'   => $item['id'],
                ),
                admin_url('admin.php?page=ajaxy-form')
            )
        );

        $delete_url = esc_url(
            wp_nonce_url(
                add_query_arg(
                    array(
                        'action' => 'delete',
                        'form' => $item['id'],
                    ),
                    admin_url('admin.php?page=ajaxy-form')
                ),
                'delete-form-' . $item['id']
            )
        );

        $actions = array(
            'edit' => sprintf('<a href="%s">%s</a>', $edit_url, __('Edit', AJAXY_FORMS_TEXT_DOMAIN)),
            'delete' => sprintf('<a href="%s">%s</a>', $delete_url, __('Delete', AJAXY_FORMS_TEXT_DOMAIN)),
        );

        return sprintf(
            '%s %s',
            ucwords($item['name']),
            $this->row_actions($actions)
        );
    }
    /**
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_shortcode($item)
    {
        if (!isset($item['name'])) {
            return '';
        }

        return sprintf('<span class="af-shortcode">
            <input type="text" onfocus="this.select();" readonly="readonly" value="%s" class="large-text code">
            </span>
        ', sprintf('[form name=&quot;%s&quot;]', \esc_attr($item['name'])));
    }

    function column_metadata($item)
    {
        if (!isset($item['metadata'])) {
            return '';
        }

        return $this->createOLList(json_decode($item['metadata'], true));
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
        if ('delete' === $this->current_action() && \wp_verify_nonce($_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'])) {
            $ids = (array)(isset($_REQUEST['id']) ? $_REQUEST['id'] : array());
            if (!empty($ids)) {
                Data::delete_forms($ids);
            }
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

        // will be used in pagination settings
        $total_items = Data::count_entries();
        if ($total_items === null || \is_wp_error($total_items)) {
            global $wpdb;
            add_action('admin_notices', function () {
                $class = 'notice notice-error';
                $message = __('An error has occurred.', \AJAXY_FORMS_TEXT_DOMAIN);

                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
            });

            \printf('<div id="message" class="notice notice-error"><p>Error: %s - %s</p></div>', $wpdb->last_error, 'Please activate and deactivate the plugin to reinstall the tables');
        }

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']) : 1;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'created';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

        $this->items = Data::get_database_forms($paged, $orderby, $order, $per_page);

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
    <table class="fixed widefat striped ajaxy-data-table">
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

    public function _script()
    {
?>
        <script type="text/javascript">
            jQuery(function() {
                jQuery(".row-actions .delete > a").click(function(event) {
                    if (!confirm("Are you sure you want to delete this form?")) {
                        event.preventDefault();
                    }
                });
            });
        </script>
<?php
    }

    function createOLList($array)
    {
        $list = '<ul style="list-style-type: none; margin-left: 26px; padding-left: 0px;">';
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $list .= "<li style=\"position: relative;margin:0;\"><strong>$key</strong>: { <span style=\"position: absolute; cursor: pointer; top: 1px; left: -15px;\">-</span>" . $this->createOLList($value) . "}</li>";
            } else {
                $list .= "<li style=\"position: relative;margin:0;\"><strong>$key</strong>: $value</li>";
            }
        }
        $list .= '</ul>';
        return $list;
    }
}
