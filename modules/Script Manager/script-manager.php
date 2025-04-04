<?php
/**
 * Script Manager Module
 * 
 * @package BxB Dashboard
 * @subpackage Script Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class BxB_Script_Manager {
    private $options;

    public function __construct() {
        $this->options = get_option('bxb_script_manager', array());
        $this->init();
    }

    public function init() {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_menu', array($this, 'register_menu'));
    }

    public function register_menu() {
        add_submenu_page(
            'bxb-dashboard',
            'Snippets',
            'Snippets',
            'manage_options',
            'bxb-snippets-dashboard',
            'bxb_snippets_dashboard_page'
        );
    }

    public function register_settings() {
        register_setting(
            'bxb_script_manager',
            'bxb_script_manager',
            array($this, 'sanitize_settings')
        );
    }

    public function sanitize_settings($input) {
        $sanitized = array();
        if (isset($input['scripts'])) {
            foreach ($input['scripts'] as $key => $script) {
                $sanitized['scripts'][$key] = array(
                    'name' => sanitize_text_field($script['name']),
                    'url' => esc_url_raw($script['url']),
                    'enabled' => isset($script['enabled']) ? true : false,
                    'location' => sanitize_text_field($script['location'] ?? 'footer')
                );
            }
        }
        return $sanitized;
    }

    public function enqueue_scripts() {
        if (!empty($this->options['scripts'])) {
            foreach ($this->options['scripts'] as $script) {
                if ($script['enabled'] && !empty($script['url'])) {
                    wp_enqueue_script(
                        'bxb-script-' . sanitize_title($script['name']),
                        $script['url'],
                        array(),
                        null,
                        $script['location'] === 'header' ? false : true
                    );
                }
            }
        }
    }

    public function render() {
        ?>
        <div class="bxb-module-script-manager">
            <h2><?php _e('Script Manager', 'bxb-dashboard'); ?></h2>
            <form method="post" action="options.php">
                <?php settings_fields('bxb_script_manager'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Scripts', 'bxb-dashboard'); ?></th>
                        <td>
                            <div id="script-list">
                                <?php
                                if (!empty($this->options['scripts'])) {
                                    foreach ($this->options['scripts'] as $key => $script) {
                                        $this->render_script_row($key, $script);
                                    }
                                }
                                ?>
                            </div>
                            <button type="button" class="button" id="add-script"><?php _e('Add Script', 'bxb-dashboard'); ?></button>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    private function render_script_row($key, $script) {
        ?>
        <div class="script-row">
            <input type="text" name="bxb_script_manager[scripts][<?php echo esc_attr($key); ?>][name]" 
                   value="<?php echo esc_attr($script['name']); ?>" placeholder="<?php _e('Script Name', 'bxb-dashboard'); ?>">
            <input type="url" name="bxb_script_manager[scripts][<?php echo esc_attr($key); ?>][url]" 
                   value="<?php echo esc_url($script['url']); ?>" placeholder="<?php _e('Script URL', 'bxb-dashboard'); ?>">
            <select name="bxb_script_manager[scripts][<?php echo esc_attr($key); ?>][location]">
                <option value="header" <?php selected($script['location'], 'header'); ?>><?php _e('Header', 'bxb-dashboard'); ?></option>
                <option value="footer" <?php selected($script['location'], 'footer'); ?>><?php _e('Footer', 'bxb-dashboard'); ?></option>
            </select>
            <label>
                <input type="checkbox" name="bxb_script_manager[scripts][<?php echo esc_attr($key); ?>][enabled]" 
                       <?php checked($script['enabled']); ?>>
                <?php _e('Enabled', 'bxb-dashboard'); ?>
            </label>
            <button type="button" class="button remove-script"><?php _e('Remove', 'bxb-dashboard'); ?></button>
        </div>
        <?php
    }
} 