<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include Parsedown if not already loaded.
if (!class_exists('Parsedown')) {
    require_once plugin_dir_path(__FILE__) . 'includes/parsedown.php';
}

/* Add PLUGIN CHANGELOG Page */
function bxb_dashboard_add_plugin_changelog_page() {
    add_submenu_page(
        'bxb-dashboard', // Parent menu slug where this submenu will appear
        'PLUGIN CHANGELOG', // Page title displayed in the browser tab
        'PLUGIN CHANGELOG', // Menu title displayed in the WordPress admin menu
        'manage_options', // Required capability (only admins can access this page)
        'bxb-plugin-changelog', // Unique slug used in the URL to identify this page
        'bxb_plugin_changelog_page' // Callback function that renders the page content
    );
}

add_action('admin_menu', 'bxb_dashboard_add_plugin_changelog_page');

/*  Display CHANGELOG Page. */
function bxb_plugin_changelog_page() {
    $pchangelog_path = BXB_dashboard_DIR . 'Plugin-Changelog.md';

    echo '<div class="wrap"><h1>PLUGIN CHANGELOG</h1>';
    
    if (file_exists($pchangelog_path)) {
        $pchangelog_content = file_get_contents($pchangelog_path);
           
        // Convert Markdown to HTML using Parsedown
            $Parsedown = new Parsedown();
            $pchangelog_html = $Parsedown->text($pchangelog_content);
            
            echo '<div style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px;">' . $pchangelog_html . '</div>';
        } else {
            echo '<p style="color:red;">Plugin-Changelog.md not found.</p>';
        }
    
        echo '</div>';
    }