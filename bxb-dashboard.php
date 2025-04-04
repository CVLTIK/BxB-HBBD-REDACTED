<?php
/**
 * Plugin Name: BxB Layout Dashboard
 * Plugin URI: https://github.com/CVLTIK/BxB-Layout-Dashboard
 * Description: A WordPress dashboard plugin for setting up headers, footers, colors, and global settings.
 * Version: 1.0.2
 * Author: CVTIK / BXBMedia
 * Author URI: 
 * License: MPL-2.0
 * License URI: https://opensource.org/licenses/MPL-2.0
 * Text Domain: bxb-dashboard
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants.
define('BXB_dashboard_VERSION', '1.0.2');
define('BXB_dashboard_DIR', plugin_dir_path(__FILE__));
define('BXB_dashboard_URL', plugin_dir_url(__FILE__));

// Include required files
require_once BXB_dashboard_DIR . 'includes/parsedown.php';
require_once BXB_dashboard_DIR . 'includes/enqueue.php';
require_once BXB_dashboard_DIR . 'includes/save-global-settings.php';

// Include module files
require_once BXB_dashboard_DIR . 'modules/Documentation/documentation.php';
require_once BXB_dashboard_DIR . 'modules/Script Manager/script-manager.php';
require_once BXB_dashboard_DIR . 'modules/Script Manager/snippets-dashboard.php';
require_once BXB_dashboard_DIR . 'modules/Script Manager/snippet-settings.php';
require_once BXB_dashboard_DIR . 'modules/Script Manager/snippet-ajax.php';

// Initialize modules
add_action('init', function() {
    // Initialize Script Manager
    if (class_exists('BxB_Script_Manager')) {
        global $bxb_script_manager;
        $bxb_script_manager = new BxB_Script_Manager();
    }
});

/* Plugin activation hook. */
function bxb_dashboard_activate() {
    // Create necessary database tables and options
    if (!get_option('bxb_dashboard_settings')) {
        add_option('bxb_dashboard_settings', array());
    }
    if (!get_option('bxb_snippets')) {
        add_option('bxb_snippets', array());
    }
}
register_activation_hook(__FILE__, 'bxb_dashboard_activate');

/** Plugin deactivation hook. */
function bxb_dashboard_deactivate() {
    // Clean up if needed
}
register_deactivation_hook(__FILE__, 'bxb_dashboard_deactivate');

 function bxb_dashboard_add_admin_menu() {
    add_menu_page(
        'BxB Dashboard',
        'BxB Dashboard',
        'manage_options',
        'bxb-dashboard',
        'bxb_dashboard_page',
        'dashicons-admin-generic',
        2
    );

 /*   // Add submenu items
    add_submenu_page(
        'bxb-dashboard',
        'Snippets',
        'Snippets',
        'manage_options',
        'bxb-snippets-dashboard',
        'bxb_snippets_dashboard_page'
    );

    add_submenu_page(
        'bxb-dashboard',
        'Documentation',
        'Documentation',
        'manage_options',
        'bxb-documentation',
        'bxb_documentation_page'
    );
}
add_action('admin_menu', 'bxb_dashboard_add_admin_menu');

// Main Dashboard Page
function bxb_dashboard_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h1>BxB Dashboard</h1>
        <div class="card" style="max-width: 100%; padding: 20px;">
            <h2>Welcome to BxB Dashboard</h2>
            <p>This dashboard provides tools for managing your WordPress site's layout and functionality.</p>
            <h3>Available Features:</h3>
            <ul>
                <li><strong>Snippets:</strong> Manage and execute code snippets with advanced security controls.</li>
                <li><strong>Documentation:</strong> Access comprehensive documentation for all features.</li>
            </ul>
        </div>
    </div>
    <?php
} */