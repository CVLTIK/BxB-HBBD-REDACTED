<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include required files
require_once plugin_dir_path(__FILE__) . '../../includes/parsedown.php';
require_once plugin_dir_path(__FILE__) . 'includes/content-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/template-parts.php';

/* Add Documentation Page */
function bxb_dashboard_add_documentation_page() {
    add_submenu_page(
        'bxb-dashboard', // Parent menu slug
        'Documentation', // Page title
        'Documentation', // Menu title
        'manage_options', // Capability
        'bxb-documentation', // Menu slug
        'bxb_documentation_page' // Callback function
    );
}
add_action('admin_menu', 'bxb_dashboard_add_documentation_page');

/* Enqueue Documentation Assets */
function bxb_enqueue_documentation_assets($hook) {
    if (strpos($hook, 'bxb-documentation') === false) {
        return;
    }

    wp_enqueue_script(
        'bxb-documentation',
        plugins_url('assets/js/documentation.js', __FILE__),
        array('jquery'),
        BXB_dashboard_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'bxb_enqueue_documentation_assets');

/* Display Documentation Page */
function bxb_documentation_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Load all documentation content
    $content = bxb_load_documentation_content();
    
    // Render the page
    bxb_render_documentation_wrapper($content);
} 