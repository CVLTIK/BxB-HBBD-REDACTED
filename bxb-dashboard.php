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

$files_to_include = array(
    // Include required global files.
        'includes/enqueue.php',
        'includes/parsedown.php',
        'includes/save-global-settings.php',
    // Include Modules
        // BxB Dashboard
            'modules/BxB Dashboard/dashboard.php',
        // Documentation
            'modules/Documentation/readme.php',
            'modules/Documentation/plugin-changelog.php',
            'modules/Documentation/layout-changelog.php',
        // Server Setup
            'modules/Server Setup/server-setup.php',
            'modules/Server Setup/server-setup-docs.php',
            'modules/Server Setup/server-setup-toggle.php',
        // Script Manager
            'modules/Script Manager/script-manager.php',
);

// Initialize modules
foreach ($files_to_include as $file) {
    if (file_exists(BXB_dashboard_DIR . $file)) {
        require_once BXB_dashboard_DIR . $file;
    }
}

// Initialize Server Setup module and its submodules
if (class_exists('BxB_Server_Setup')) {
    $bxb_server_setup = new BxB_Server_Setup();
    $bxb_server_setup_docs = new BxB_Server_Setup_Docs();
    $bxb_server_setup_toggle = new BxB_Server_Setup_Toggle();
}
  
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