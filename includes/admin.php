<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Initial Page and Submenus.
 */
function bxb_dashboard_add_admin_menu() {
    add_menu_page(
        'BxB Dashboard Settings',
        'BxB Dashboard',
        'manage_options',
        'bxb-dashboard',
        'bxb_dashboard_settings_page',
        'dashicons-table-col-after',
        25
    );

    // Add README Page
    add_submenu_page(
        'bxb-dashboard', // ✅ Fix: Parent slug must match the main menu slug
        'BxB HBBD README',
        '📖 README',
        'manage_options',
        'bxb-hbbd-readme',
        'bxb_hbbd_readme_page'
    );

    // Add Changelog Page
    add_submenu_page(
        'bxb-dashboard', // ✅ Fix: Parent slug must match the main menu slug
        'BxB HBBD Changelog',
        '📜 Changelog',
        'manage_options',
        'bxb-hbbd-changelog',
        'bxb_hbbd_changelog_page'
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
function bxb_hbbd_readme_page() {
    $readme_path = plugin_dir_path(__FILE__) . 'README.md'; // ✅ Fix: Use `plugin_dir_path(__FILE__)`

    echo '<div class="wrap"><h1>📖 BxB HBBD README</h1>';
    
    if (file_exists($readme_path)) {
        $readme_content = file_get_contents($readme_path);
        echo '<pre style="background:#fff; padding:10px; border:1px solid #ccc; white-space: pre-wrap;">' . esc_html($readme_content) . '</pre>';
    } else {
        echo '<p style="color:red;">README.md not found.</p>';
    }

    echo '</div>';
}

/**
 * Display the Changelog page.
 */
function bxb_hbbd_changelog_page() {
    $changelog_path = plugin_dir_path(__FILE__) . 'CHANGELOG.md'; // ✅ Fix: Use `plugin_dir_path(__FILE__)`

    echo '<div class="wrap"><h1>📜 BxB HBBD Changelog</h1>';
    
    if (file_exists($changelog_path)) {
        $changelog_content = file_get_contents($changelog_path);
        echo '<pre style="background:#fff; padding:10px; border:1px solid #ccc; white-space: pre-wrap;">' . esc_html($changelog_content) . '</pre>';
    } else {
        echo '<p style="color:red;">CHANGELOG.md not found.</p>';
    }

    echo '</div>';
}
