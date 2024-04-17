<?php

namespace Ajaxy\Forms\Inc;

class Data
{

    static $table_name = 'form_entries';
    public static function install()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . self::$table_name;
        $sql = "CREATE TABLE " . $table_name . " (
            id INT NOT NULL AUTO_INCREMENT,  
            name tinytext NOT NULL,
            data text NOT NULL,
            metadata text NOT NULL,
            created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        add_option('ajaxy_forms_db_version', \Ajaxy\Forms\Plugin::DB_VERSION);

        //we will deal with the upgrades later
        // $installed_ver = get_option('ajaxy_forms_db_version');
        // if ($installed_ver != \Ajaxy\Forms\Plugin::DB_VERSION) {
        //     $sql = "CREATE TABLE " . $table_name . " (
        //         id int(11) NOT NULL AUTO_INCREMENT,
        //         name tinytext NOT NULL,
        //         data text NOT NULL,
        //         created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        //         PRIMARY KEY  (id)
        //     );";

        //     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //     dbDelta($sql);

        //     // notice that we are updating option, rather than adding it
        //     update_option('ajaxy_forms_db_version', \Ajaxy\Forms\Plugin::DB_VERSION);
        // }
    }

    public static function get_table_name()
    {
        global $wpdb;
        return $wpdb->prefix . self::$table_name;
    }

    public static function add($name, $data)
    {
        global $wpdb;
        $metadata = array(
            'ip' => self::get_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'referer' => $_SERVER['HTTP_REFERER'],
            'user' => null
        );
        $user = wp_get_current_user();
        if ($user) {
            $metadata['user'] = [
                'id' => $user->ID,
                'display_name' => !empty($user->display_name) ? $user->display_name : $user->user_login,
            ];
        }


        $table_name = self::get_table_name();
        return $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'data' => \json_encode($data),
                'metadata' => \json_encode($metadata),
                'created' => current_time('mysql'),
            )
        );
    }

    public static function update($id, $data)
    {
        global $wpdb;
        $table_name = self::get_table_name();
        return $wpdb->update(
            $table_name,
            array(
                'data' => \json_encode($data),
            ),
            array('id' => $id)
        );
    }

    public static function delete($id)
    {
        global $wpdb;
        $table_name = self::get_table_name();
        return $wpdb->delete(
            $table_name,
            array('id' => $id)
        );
    }

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

    public static function get_entries($form)
    {
        global $wpdb;
        $table_name = self::get_table_name();
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE name = %s",
                $form
            ),
            ARRAY_A
        );
    }

    public static function export($form)
    {
        $entries = self::get_entries($form);
        $data = [];
        foreach ($entries as $entry) {
            $array = self::flatten(isset($entry['data']) ? \json_decode($entry['data'], true) : []);
            $output = '';
            foreach ($array as $key => $value) {
                $output .= $key . ': ' . $value . "\n";
            }

            $metarray = self::flatten(isset($entry['metadata']) ? \json_decode($entry['metadata'], true) : []);
            $metadata = '';
            foreach ($metarray as $key => $value) {
                $metadata .= $key . ': ' . $value . "\n";
            }
            $data[] = [
                'name' => $entry['name'],
                'data' => $output,
                'metadata' => $metadata,
                'created' => $entry['created'],
            ];
        }

        $csv = new Export\Csv([
            'Name', 'Data', 'Metadata', 'Date'
        ], $data, sprintf('%s-form-entries-%s.csv', $form, \date('Y-m-d')));
        return $csv->download();
    }


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
}
