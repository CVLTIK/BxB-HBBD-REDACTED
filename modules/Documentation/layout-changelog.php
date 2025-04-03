<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include Parsedown if not already loaded.
if (!class_exists('Parsedown')) {
    require_once plugin_dir_path(__FILE__) . 'includes/parsedown.php';
}

/* Add LAYOUT CHANGELOG Page */
function bxb_dashboard_add_layout_changelog_page() {
    add_submenu_page(
        'bxb-dashboard', // Parent menu slug where this submenu will appear
        'Layout Changelog', // Page title displayed in the browser tab
        'Layout Changelog', // Menu title displayed in the WordPress admin menu
        'manage_options', // Required capability (only admins can access this page)
        'bxb-layout-dashboard-changelog', // Unique slug used in the URL to identify this page
        'bxb_layout_changelog_page' // Callback function that renders the page content
    );
}

add_action('admin_menu', 'bxb_dashboard_add_layout_changelog_page');

/*  Display LAYOUT CHANGELOG Page. */
function bxb_layout_changelog_page() {
    $lchangelog_path = BXB_dashboard_DIR . 'Layout-Changelog.md';

    echo '<div class="wrap"><h1>Layout Changelog</h1>';
    
    if (file_exists($lchangelog_path)) {
        $lchangelog_content = file_get_contents($lchangelog_path);
           
        // Convert Markdown to HTML using Parsedown
            $Parsedown = new Parsedown();
            $lchangelog_html = $Parsedown->text($lchangelog_content);
            
            echo '<div style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px;">' . $lchangelog_html . '</div>';
        } else {
            echo '<p style="color:red;">Layout-Changelog.md not found.</p>';
        }
    
        echo '</div>';
    }