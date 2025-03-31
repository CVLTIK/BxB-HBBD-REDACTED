<?php
/**
 * Plugin Name: BxB Dashboard
 * Plugin URI: https://github.com/CVLTIK/BxB-HBBD-REDACTED
 * Description: A WordPress dashboard plugin for setting up headers, footers, colors, and global settings.
 * Version: 1.0.0
 * Author: CVTIK / BXBMedia
 * Author URI: 
 * License: MPL-2.0
 * License URI: https://opensource.org/licenses/MPL-2.0
 * Text Domain: bxb-hbbd
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants.
define('BXB_HBBD_VERSION', '1.0.0');
define('BXB_HBBD_DIR', plugin_dir_path(__FILE__));
define('BXB_HBBD_URL', plugin_dir_url(__FILE__));

// Include required files.
require_once BXB_HBBD_DIR . 'includes/admin.php';
require_once BXB_HBBD_DIR . 'includes/enqueue.php';
require_once BXB_HBBD_DIR . 'includes/acf-fields.php';
require_once BXB_HBBD_DIR . 'includes/settings.php';

/**
 * Plugin activation hook.
 */
function bxb_hbbd_activate() {
    // Actions on activation
}
register_activation_hook(__FILE__, 'bxb_hbbd_activate');

/**
 * Plugin deactivation hook.
 */
function bxb_hbbd_deactivate() {
    // Actions on deactivation
}
register_deactivation_hook(__FILE__, 'bxb_hbbd_deactivate');
