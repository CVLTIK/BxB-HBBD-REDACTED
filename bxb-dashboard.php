<?php
/**
 * Plugin Name: BxB Layout Dashboard
 * Plugin URI: https://github.com/CVLTIK/BxB-Layout-Dashboard
 * Description: A WordPress dashboard plugin for setting up headers, footers, colors, and global settings.
 * Version: 1.0.1
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
define('BXB_dashboard_VERSION', '1.0.1');
define('BXB_dashboard_DIR', plugin_dir_path(__FILE__));
define('BXB_dashboard_URL', plugin_dir_url(__FILE__));

$files_to_include = array(
    // Global files
    'includes/global-functions.php',
    'includes/global-variables.php',
    
    // Modules
    'modules/Documentation/documentation.php',
    'modules/Script Manager/snippets-dashboard.php',
    'modules/Script Manager/snippet-settings.php',
    'modules/Script Manager/snippet-ajax.php'
);

// Initialize modules
foreach ($files_to_include as $file) {
    if (file_exists(BXB_dashboard_DIR . $file)) {
        require_once BXB_dashboard_DIR . $file;
    }
}

// Initialize Server Setup module and its submodules
add_action('plugins_loaded', function() {
    if (class_exists('BxB_Server_Setup')) {
        global $bxb_server_setup;
        $bxb_server_setup = new BxB_Server_Setup();
    }
    
    if (class_exists('BxB_Server_Setup_Docs')) {
        global $bxb_server_setup_docs;
        $bxb_server_setup_docs = new BxB_Server_Setup_Docs();
    }
    
    if (class_exists('BxB_Server_Setup_Toggle')) {
        global $bxb_server_setup_toggle;
        $bxb_server_setup_toggle = new BxB_Server_Setup_Toggle();
    }
});
  
/* Plugin activation hook. */
function bxb_dashboard_activate() {
    // Actions on activation
}
register_activation_hook(__FILE__, 'bxb_dashboard_activate');

/** Plugin deactivation hook. */
function bxb_dashboard_deactivate() {
    // Actions on deactivation
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
}