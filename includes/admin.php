<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Initial Page.
 */
function bxb_dashboard_add_admin_menu() {
    add_menu_page(
        'BxB Dasboard Settings',
        'BxB Dashboard',
        'manage_options',
        'bxb-dashboard',
        'bxb_dashboard_settings_page',
        'dashicons-table-col-after',
        25
    );

    // Add README Page
    add_submenu_page(
        'bxb-dashboard', // ✅ This now correctly matches the parent menu
        'BxB HBBD README',
        '📖 README',
        'manage_options',
        'bxb-dashboard-readme',
        'bxb_readme_page'
    );
}
add_action('admin_menu', 'bxb_dashboard_add_admin_menu');


/**
 * Display settings page.
 */
function bxb_dashboard_settings_page() {
    ?>
    <div class="wrap">
        <h1>BxB Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('bxb_dashboard_options');
            do_settings_sections('bxb_dashboard');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
/**
 * Display the README page.
 */
function bxb_readme_page() {
    $readme_path = BXB_dashboard_DIR . 'README.md';

    echo '<div class="wrap"><h1>📖 BxB HBBD README</h1>';
    
    if (file_exists($readme_path)) {
        $readme_content = file_get_contents($readme_path);
        echo '<pre style="background:#fff; padding:10px; border:1px solid #ccc; white-space: pre-wrap;">' . esc_html($readme_content) . '</pre>';
    } else {
        echo '<p style="color:red;">README.md not found.</p>';
    }

    echo '</div>';
}
