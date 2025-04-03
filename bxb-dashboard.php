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

// Include required global files.
require_once BXB_dashboard_DIR . 'includes/enqueue.php';
require_once BXB_dashboard_DIR . 'includes/parsedown.php';
require_once BXB_dashboard_DIR . 'includes/save-global-settings.php';

// Include Modules
// BxB Dashboard
require_once BXB_dashboard_DIR . 'modules/BxB Dashboard/dashboard.php';
// Documentation
require_once BXB_dashboard_DIR . 'modules/Documentation/readme.php';
require_once BXB_dashboard_DIR . 'modules/Documentation/plugin-changelog.php';
require_once BXB_dashboard_DIR . 'modules/Documentation/layout-changelog.php';
//Server Setup
require_once BXB_dashboard_DIR . 'modules/Server Setup/ss-orig-bak.php';
  
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