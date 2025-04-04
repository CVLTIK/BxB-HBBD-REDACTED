<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin styles and scripts.
 */
function bxb_dashboard_enqueue_admin_assets($hook) {
    // Only load on our plugin pages
    if (strpos($hook, 'bxb-dashboard') === false) {
        return;
    }

    // CSS  
    wp_enqueue_style(
        'bxb-dashboard-admin',
        BXB_dashboard_URL . 'modules/Script Manager/assets/css/admin.css',
        array(),
        BXB_dashboard_VERSION
    );
    
    // JS
    wp_enqueue_script(
        'bxb-dashboard-admin',
        BXB_dashboard_URL . 'modules/Script Manager/assets/js/admin.js',
        array('jquery'),
        BXB_dashboard_VERSION,
        true
    );

    // Localize script
    wp_localize_script('bxb-dashboard-admin', 'bxbDashboard', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('bxb_dashboard_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'bxb_dashboard_enqueue_admin_assets');