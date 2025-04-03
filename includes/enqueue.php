<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin styles and scripts.
 */
function bxb_dashboard_enqueue_admin_assets() {
    // CSS  
    wp_enqueue_style('bxb-dashboard-admin', BXB_dashboard_URL . 'css/admin.css', array(), BXB_dashboard_VERSION);
    
    // JS
    wp_enqueue_script('bxb-dashboard-admin', BXB_dashboard_URL . 'js/admin.js', array('jquery'), BXB_dashboard_VERSION, true);
}
add_action('admin_enqueue_scripts', 'bxb_dashboard_enqueue_admin_assets');