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
