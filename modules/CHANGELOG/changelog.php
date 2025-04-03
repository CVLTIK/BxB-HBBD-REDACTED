<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add CHANGELOG Page.
 */
function bxb_dashboard_add_changelog_page() {
    add_submenu_page(
        'bxb-dashboard',
        'CHANGELOG',
        'CHANGELOG',
        'manage_options',
        'bxb-dashboard-changelog',
        'bxb_changelog_page'
    );
}
add_action('admin_menu', 'bxb_dashboard_add_changelog_page');

/**
 * Display CHANGELOG Page.
 */
function bxb_changelog_page() {
    $changelog_path = BXB_dashboard_DIR . 'CHANGELOG.md';

    echo '<div class="wrap"><h1>📖 BxB HBBD CHANGELOG</h1>';
    
    if (file_exists($changelog_path)) {
        $changelog_content = file_get_contents($changelog_path);
        echo '<pre style="background:#fff; padding:10px; border:1px solid #ccc; white-space: pre-wrap;">' . esc_html($changelog_content) . '</pre>';
    } else {
        echo '<p style="color:red;">CHANGELOG.md not found.</p>';
    }

    echo '</div>';
}