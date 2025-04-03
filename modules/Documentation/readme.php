<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include Parsedown if not already loaded.
if (!class_exists('Parsedown')) {
    require_once plugin_dir_path(__FILE__) . 'includes/parsedown.php';
}

/* Add README Page. */
function bxb_dashboard_add_readme_page() {
    add_submenu_page(
        'bxb-dashboard', // Parent menu slug where this submenu will appear
        'README', // Page title displayed in the browser tab
        'README', // Menu title displayed in the WordPress admin menu
        'manage_options', // Required capability (only admins can access this page)
        'bxb-dashboard-readme', // Unique slug used in the URL to identify this page
        'bxb_readme_page' // Callback function that renders the page content
    );
}

add_action('admin_menu', 'bxb_dashboard_add_readme_page');

/* Display README Page with Markdown Formatting.*/
function bxb_readme_page() {
    $readme_path = BXB_dashboard_DIR . 'README.md';

    echo '<div class="wrap"><h1> README on break</h1>';

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