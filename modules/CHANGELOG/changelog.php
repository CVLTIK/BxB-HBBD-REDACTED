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

// Include Parsedown if not already loaded.
if (!class_exists('Parsedown')) {
    require_once plugin_dir_path(__FILE__) . 'includes/parsedown.php';
}

/**
 * Display CHANGELOG Page.
 */
function bxb_changelog_page() {
    $changelog_path = BXB_dashboard_DIR . 'CHANGELOG.md';

    echo '<div class="wrap"><h1>📖 BxB HBBD CHANGELOG</h1>';
    
    if (file_exists($changelog_path)) {
        $changelog_content = file_get_contents($changelog_path);
           
        // Convert Markdown to HTML using Parsedown
            $Parsedown = new Parsedown();
            $changelog_html = $Parsedown->text($changelog_content);
            
            echo '<div style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px;">' . $changelog_html . '</div>';
        } else {
            echo '<p style="color:red;">CHANGELOG.md not found.</p>';
        }
    
        echo '</div>';
    }