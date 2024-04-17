<?php

namespace Ajaxy\Forms\Admin;

class License
{
    public $lic;
    public $server = 'http://www.ajaxy.org';
    public $api_key = '56d4131a60f6c8.69447567';
    private $wp_option  = '_ajaxy_woo_github_license';
    private $product_id = 'AJAXY-WOO-GITHUB';
    public $err;

    public function __construct()
    {
        if (!$this->is_licensed()) {
            add_action('admin_menu', array(&$this, 'license_menu'));
        }
    }
    public function check($lic = false)
    {
        if ($this->is_licensed())
            $this->lic = get_option($this->wp_option);
        else
            $this->lic = $lic;
    }

    public function is_licensed()
    {
        $lic = get_option($this->wp_option);
        if (!empty($lic)) {
            return true;
        }
        return false;
    }


    public function license_menu()
    {
        add_submenu_page('wpcf7', 'License', 'License', 'manage_options', 'ajaxy-github-field', array($this, 'license_page'));
    }
    public function license_page()
    {
        printf('<div class="wrap">');
        printf('<h2>%s</h2>', __('License'));
        printf('<hr/>');

        if (isset($_REQUEST['activate_license'])) {
            $license_key = $_REQUEST['license_key'];
            $this->check($license_key);
            if ($this->active()) {
                printf('<div class="updated"><p>%s</p></div>', __('Your license Activated successfully'));
            } else {
                printf('<div class="error"><p>%s</p></div>', $this->err);
            }
        }
        if ($this->is_licensed()) {
            printf('<div class="updated"><p>%s</p></div>', __('Thank you for purchasing!'));
        } else {
?>
            <form action="" method="post">
                <table class="form-table">
                    <tr>
                        <th style="width:100px;"><label for="license_key"><?php _e('License Key'); ?></label></th>
                        <td><input class="regular-text" type="text" id="license_key" name="license_key" value="<?php echo get_option('license_key'); ?>"></td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="activate_license" value="Activate" class="button-primary" />
                </p>
            </form>
<?php
        }


        printf('</div>');
    }

    /**
     * send query to server and try to active lisence
     * @return boolean
     */
    public function active()
    {
        $url = 'http://www.ajaxy.org' . '/?secret_key=' . $this->api_key . '&slm_action=slm_activate&license_key=' . $this->lic . '&registered_domain=' . get_bloginfo('url') . '&item_reference=' . $this->product_id;
        $response = wp_remote_get($url, array('timeout' => 20, 'sslverify' => false));

        $license_data = null;
        if (is_array($response)) {
            $json = $response['body']; // use the content
            $json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', utf8_encode($json));
            $license_data = json_decode($json);
        }
        if ($license_data && $license_data->result == 'success') {
            update_option($this->wp_option, $this->lic);
            return true;
        } else {
            $this->err = $license_data ? $license_data->message : __('Failed to retrieve licensing information, Please try again');
            return false;
        }
    }
}
