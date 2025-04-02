<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add README Page.
 */
function bxb_dashboard_add_readme_page() {
    add_submenu_page(
        'bxb-dashboard',
        'README',
        'README',
        'manage_options',
        'bxb-dashboard-readme',
        'bxb_readme_page'
    );
}
add_action('admin_menu', 'bxb_dashboard_add_readme_page');

/**
 * Display README Page.
 */
function bxb_readme_page() {
    $readme_path = BXB_DASHBOARD_DIR . 'README.md';

    echo '<div class="wrap"><h1>📖 BxB HBBD README</h1>';
    
    if (file_exists($readme_path)) {
        $readme_content = file_get_contents($readme_path);
        echo '<pre style="background:#fff; padding:10px; border:1px solid #ccc; white-space: pre-wrap;">' . esc_html($readme_content) . '</pre>';
    } else {
        echo '<p style="color:red;">README.md not found.</p>';
    }

    echo '</div>';
}
