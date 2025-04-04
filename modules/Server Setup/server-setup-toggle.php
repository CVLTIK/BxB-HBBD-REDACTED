<?php
/**
 * Server Setup Toggle
 * 
 * @package BxB Dashboard
 * @subpackage Server Setup
 */

if (!defined('ABSPATH')) {
    exit;
}

class BxB_Server_Setup_Toggle {
    private $options;

    public function __construct() {
        $this->options = get_option('bxb_server_setup_toggle', array('enabled' => true));
        $this->init();
    }

    public function init() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu() {
        add_submenu_page(
            'bxb-dashboard',
            __('Server Setup Toggle', 'bxb-dashboard'),
            __('Server Setup Toggle', 'bxb-dashboard'),
            'manage_options',
            'bxb-server-setup-toggle',
            array($this, 'render_page')
        );
    }

    public function register_settings() {
        register_setting(
            'bxb_server_setup_toggle',
            'bxb_server_setup_toggle',
            array($this, 'sanitize_settings')
        );
    }

    public function sanitize_settings($input) {
        $sanitized = array();
        $sanitized['enabled'] = isset($input['enabled']) ? true : false;
        return $sanitized;
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Server Setup Toggle', 'bxb-dashboard'); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('bxb_server_setup_toggle'); ?>
                
                <div class="bxb-toggle-container">
                    <div class="bxb-toggle-section">
                        <h2><?php _e('Module Status', 'bxb-dashboard'); ?></h2>
                        <p><?php _e('Enable or disable the Server Setup functionality. When disabled, the Server Setup page will be hidden from the admin menu.', 'bxb-dashboard'); ?></p>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Server Setup Module', 'bxb-dashboard'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="bxb_server_setup_toggle[enabled]" 
                                               <?php checked($this->options['enabled']); ?>>
                                        <?php _e('Enable Server Setup functionality', 'bxb-dashboard'); ?>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="bxb-toggle-section">
                        <h2><?php _e('Security Settings', 'bxb-dashboard'); ?></h2>
                        <p><?php _e('Additional security settings for the Server Setup module.', 'bxb-dashboard'); ?></p>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Minimum User Role', 'bxb-dashboard'); ?></th>
                                <td>
                                    <select name="bxb_server_setup_toggle[min_role]">
                                        <option value="manage_options" <?php selected($this->options['min_role'] ?? 'manage_options', 'manage_options'); ?>>
                                            <?php _e('Administrator', 'bxb-dashboard'); ?>
                                        </option>
                                        <option value="edit_others_posts" <?php selected($this->options['min_role'] ?? 'manage_options', 'edit_others_posts'); ?>>
                                            <?php _e('Editor', 'bxb-dashboard'); ?>
                                        </option>
                                    </select>
                                    <p class="description">
                                        <?php _e('Select the minimum user role required to access the Server Setup functionality.', 'bxb-dashboard'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
} 