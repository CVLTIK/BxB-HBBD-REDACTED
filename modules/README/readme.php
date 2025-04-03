<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include Parsedown if not already loaded.
if (!class_exists('Parsedown')) {
    require_once plugin_dir_path(__FILE__) . 'includes/parsedown.php';
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
 * Display README Page with Markdown Formatting.
 */
function bxb_readme_page() {
    $changelog_path = BXB_dashboard_DIR . 'README.md';

    echo '<div class="wrap"><h1> BxB README</h1>';

    if (file_exists($readme_path)) {
        $readme_content = file_get_contents($readme_path);

        // Convert Markdown to HTML using Parsedown
        $Parsedown = new Parsedown();
        $readme_html = $Parsedown->text($readme_content);

        echo '<div style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px;">' . $readme_html . '</div>';
    } else {
        echo '<p style="color:red;">README.md not found.</p>';
    }

    echo '</div>';
}
