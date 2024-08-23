<?php

namespace Ajaxy\Forms\Inc;

class Data
{

    private static $forms = [];

    private static $entries_table_name = 'form_entries';
    private static $table_name = 'forms';

    /**
     * Create the tables in the database
     *
     * @date 2024-04-21
     *
     * @return void
     */
    public static function install()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $entries_table_name = $wpdb->prefix . self::$entries_table_name;

        $sql = "CREATE TABLE IF NOT EXISTS " . $entries_table_name . " (
            id INT NOT NULL AUTO_INCREMENT,  
            name tinytext NOT NULL,
            data text NOT NULL,
            metadata text NOT NULL,
            created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_name = $wpdb->prefix . self::$table_name;
        $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
            id INT NOT NULL AUTO_INCREMENT,  
            name tinytext NOT NULL,
            metadata text NOT NULL,
            actions text NOT NULL,
            created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);

        add_option('ajaxy_forms_db_version', \Ajaxy\Forms\Plugin::DB_VERSION);
    }


    /**
     * Get the forms table name
     *
     * @date 2024-04-21
     *
     * @return string
     */
    public static function get_table_name()
    {
        global $wpdb;
        return $wpdb->prefix . self::$table_name;
    }

    /**
     * Add a form to the database
     *
     * @date 2024-04-21
     *
     * @param string $name
     * @param array $metadata
     *
     * @return int|false|\WP_Error — The number of rows inserted, or false on error.
     */
    public static function add_form($name, $metadata)
    {
        global $wpdb;
        $table_name = self::get_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $success = $wpdb->insert($table_name, array('name' => $name, 'metadata' => \wp_json_encode($metadata), 'created' => current_time('mysql'),));
        if ($success) {
            return $wpdb->insert_id;
        }

        $error = $wpdb->last_error;
        return $error ? new \WP_Error($error) : false;
    }

    /**
     * Update a form in the database
     *
     * @date 2024-04-21
     *
     * @param int $id
     * @param array $metadata
     *
     * @return int|false — The number of rows updated, or false on error.
     */
    public static function update_form($id, $name, $metadata)
    {
        global $wpdb;
        $table_name = self::get_table_name();
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->update(
            $table_name,
            array(
                'metadata' => \wp_json_encode($metadata),
                'name' => $name,
            ),
            array('id' => $id)
        );
    }

    /**
     * Update a form actions in the database
     *
     * @date 2024-04-21
     *
     * @param int $id
     * @param array $actions
     *
     * @return int|false — The number of rows updated, or false on error.
     */
    public static function update_form_actions($id, $actions)
    {
        global $wpdb;
        $table_name = self::get_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->update(
            $table_name,
            array(
                'actions' => \wp_json_encode($actions),
            ),
            array('id' => $id)
        );
    }

    /**
     * Update a form action in the database
     *
     * @date 2024-05-21
     *
     * @param string $id
     * @param string $action_name
     * @param array $action
     *
     * @return int|false — The number of rows updated, or false on error.
     */
    public static function update_form_action($id, $action_name, $action)
    {
        $form = self::get_form($id);
        $actions = [];
        try {
            $actions = \json_decode($form['actions'], true);
        } catch (\Exception $e) {
        }

        $actions[$action_name] = $action;
        return self::update_form_actions($id, $actions);
    }

    /**
     * Delete a form from the database
     *
     * @date 2024-04-21
     *
     * @param int $id
     *
     * @return int|false — The number of rows deleted, or false on error.
     */
    public static function delete_form($id)
    {
        global $wpdb;
        $table_name = self::get_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->delete(
            $table_name,
            array('id' => $id)
        );
    }

    /**
     * Get all forms from the database
     *
     * @date 2024-04-21
     *
     * @return array|object|null — Database query results.
     */
    public static function get_database_forms($page = 1, $order_by = 'created', $order = 'desc', $per_page = 10)
    {
        global $wpdb;
        $table_name = self::get_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare(
                // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
                "SELECT * FROM %i ORDER BY %i %1s LIMIT %d OFFSET %d",
                $table_name,
                $order_by,
                $order,
                $per_page,
                ($page - 1) * $per_page
            ),
            ARRAY_A
        );
    }

    public static function get_forms()
    {
        return self::$forms;
    }

    /**
     * Get a form from the database by name
     *
     * @date 2024-04-21
     *
     * @param string $name
     *
     * @return object|null — Database query results.
     */
    public static function get_form_by_name($name)
    {
        global $wpdb;
        $table_name = self::get_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM %i WHERE name = %s", $table_name, $name), ARRAY_A);
    }

    /**
     * Get a form from the database by id
     *
     * @date 2024-04-21
     *
     * @param string $id
     *
     * @return object|null — Database query results.
     */
    public static function get_form($id)
    {
        global $wpdb;
        $table_name = self::get_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM %i WHERE id = %s", $table_name, absint($id)), ARRAY_A);
    }

    /**
     * Parses a form from the database or from the registered forms to be executed
     *
     * @date 2024-04-21
     *
     * @param string $name
     *
     * @return Form|null — Database query results.
     */
    public static function parse_form($name)
    {
        if (!isset(self::$forms[$name])) {
            $form = self::get_form_by_name($name);

            if (!$form) {
                return null;
            }
            $metadata = isset($form['metadata']) ? \json_decode($form['metadata'], true) : [];
            $actions = isset($form['actions']) ? \json_decode($form['actions'], true) : [];

            self::$forms[$name] = new Form($name, $metadata['fields'] ?? [], $metadata['options'] ?? [], $actions, null, $metadata['theme'] ?? null);
        }
        return self::$forms[$name];
    }



    /**
     * Get entries table name
     *
     * @date 2024-04-21
     *
     * @return string
     */
    public static function get_entries_table_name()
    {
        global $wpdb;
        return $wpdb->prefix . self::$entries_table_name;
    }

    /**
     * Add an entry to the database
     *
     * @date 2024-04-21
     *
     * @param string $name the form name
     * @param array $data
     * @return int|false — The number of rows inserted, or false on error.
     */
    public static function add($name, $data)
    {
        global $wpdb;
        $metadata = array(
            'ip' => self::get_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'referer' => $_SERVER['HTTP_REFERER']
        );

        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $metadata['user'] = [
                'id' => $user->ID,
                'display_name' => !empty($user->display_name) ? $user->display_name : $user->user_login,
            ];
        }

        $entries_table_name = self::get_entries_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->insert(
            $entries_table_name,
            array(
                'name' => $name,
                'data' => \wp_json_encode($data),
                'metadata' => \wp_json_encode($metadata),
                'created' => current_time('mysql'),
            )
        );
    }


    /**
     * Update an entry in the database
     * 
     * @date 2024-04-21
     * @param int $id
     * @param array $data
     * @return int|false — The number of rows updated, or false on error.
     */
    public static function update($id, $data)
    {
        global $wpdb;
        $entries_table_name = self::get_entries_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->update(
            $entries_table_name,
            array(
                'data' => \wp_json_encode($data),
            ),
            array('id' => $id)
        );
    }

    /**
     * Delete an entry from the database
     *
     * @date 2024-04-21
     *
     * @param string|int $id
     *
     * @return int|false — The number of rows deleted, or false on error.
     */
    public static function delete($id)
    {
        global $wpdb;
        $entries_table_name = self::get_entries_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->delete(
            $entries_table_name,
            array('id' => $id)
        );
    }

    /**
     * Get an entry
     *
     * @date 2024-04-21
     *
     * @param string $id
     *
     * @return array|object|null|void Database query result in format specified by $output or null on failure
     */
    public static function get($id)
    {
        global $wpdb;
        $entries_table_name = self::get_entries_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM %i WHERE id = %s", $entries_table_name, absint($id)), ARRAY_A);
    }

    /**
     * Delete bulk entries by id
     *
     * @date 2024-04-21
     *
     * @param array $ids
     *
     * @return int|bool Boolean true for CREATE, ALTER, TRUNCATE and DROP queries. Number of rows affected/selected for all other queries. Boolean false on error
     */
    public static function delete_entries($ids)
    {
        global $wpdb;
        $ids = implode(',', array_map('absint', (array)$ids));
        $entries_table_name = self::get_entries_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->query($wpdb->prepare("DELETE FROM %i WHERE ID IN (%s)", $entries_table_name, $ids));
    }


    public static function delete_forms($ids)
    {
        global $wpdb;
        $ids = implode(',', array_map('absint', (array)$ids));
        $table_name = self::get_table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->query($wpdb->prepare("DELETE FROM %i WHERE ID IN (%s)", $table_name, $ids));
    }

    /**
     * Get the ip address of the submitter
     *
     * @date 2024-04-21
     *
     * @return string
     */
    public static function get_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Get all entries for a given form
     *
     * @date 2024-04-21
     * @param string $form
     *
     * @return array|object|null — Database query results.
     */
    public static function get_entries($form = null, $page = 1, $order_by = 'created', $order = 'desc', $per_page = 10)
    {
        if ($page <= 0) {
            $page = 1;
        }
        global $wpdb;
        $entries_table_name = self::get_entries_table_name();
        if ($form) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
            return $wpdb->get_results(
                $wpdb->prepare(
                    // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
                    "SELECT * FROM %i WHERE name = %s ORDER BY %i %1s LIMIT %d OFFSET %d",
                    $entries_table_name,
                    $form,
                    $order_by,
                    $order,
                    $per_page,
                    max(0, intval($page - 1) * $per_page)
                ),
                ARRAY_A
            );
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare(
                // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
                "SELECT * FROM %i ORDER BY %i %1s LIMIT %d OFFSET %d",
                $entries_table_name,
                $order_by,
                $order,
                $per_page,
                ($page - 1) * $per_page
            ),
            ARRAY_A
        );
    }


    /**
     * Count entries for a given form
     *
     * @date 2024-04-21
     *
     * @param string $form
     *
     * @return string|null — Database query result (as string), or null on failure.
     */
    public static function count_entries($form = null)
    {
        global $wpdb;
        $entries_table_name = self::get_entries_table_name();
        if ($form) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM %i WHERE name = %s", $entries_table_name, $form));
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM %i", $entries_table_name));
    }

    /**
     * Export all entries
     *
     * @date 2024-04-21
     *
     * @param string $form
     *
     * @return void
     */
    public static function export($form)
    {
        $entries = self::get_entries($form, 1, 'created', 'desc', 100000);
        $headers = [
            '_name' => 'Name',
            '_metadata' => 'Metadata',
            '_created' => 'Date',
        ];
        $data = [];
        foreach ($entries as $entry) {
            $array = isset($entry['data']) ? \json_decode($entry['data'], true) : [];
            $output = '';
            foreach ($array as $key => $value) {
                $output .= $key . ': ' . $value . "\n";
            }

            $metarray = self::flatten(isset($entry['metadata']) ? \json_decode($entry['metadata'], true) : []);
            $metadata = '';
            foreach ($metarray as $key => $value) {
                $metadata .= $key . ': ' . $value . "\n";
            }

            $values = [];
            foreach ($array as $name => $field) {
                if (!is_array($field)) {
                    $values[$name] = $field;
                    $headers[$name] = $headers[$name] ?? $name;
                    continue;
                }
                $values[$name] = is_array($field['value_label']) ? implode(', ', $field['value_label']) : $field['value_label'];
                $headers[$name] = $headers[$name] ?? $field['label'];
            }
            $data[] = array_merge([
                'name' => $entry['name'],
                'metadata' => $metadata,
                'created' => $entry['created'],
            ], $values);
        }

        $csv = new Export\Csv($headers, $data, sprintf('%s-form-entries-%s.csv', $form ? $form : 'all', \gmdate('Y-m-d')));
        return $csv->download();
    }


    /**
     * Flatter an array for export
     *
     * @date 2024-04-21
     *
     * @param array $array
     * @param string $prefix
     *
     * @return array
     */
    public static function flatten($array, $prefix = '')
    {
        $flat = array();
        $sep = ".";

        if (!is_array($array)) $array = (array)$array;

        foreach ($array as $key => $value) {
            $_key = ltrim($prefix . $sep . $key, ".");

            if (is_array($value) || is_object($value)) {
                // Iterate this one too
                $flat = array_merge($flat, self::flatten($value, $_key));
            } else {
                $flat[$_key] = $value;
            }
        }

        return $flat;
    }

    /**
     * Register a form from code
     *
     * @date 2024-05-24
     *
     * @param string $name
     * @param array $fields
     * @param array  $options
     * @param array|null $initial_data
     *
     * @return void
     */
    public static function register_form($name, $fields, $options = [], $actions = [], $initial_data = null)
    {
        self::$forms[$name] = new Form($name, $fields, $options, $actions, $initial_data);
    }
}
