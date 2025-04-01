<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add menu page.
 */
function bxb_ldash_add_admin_menu() {
    add_menu_page(
        'BxB ldash Settings',
        'BxB ldash',
        'manage_options',
        'bxb-ldash',
        'bxb_ldash_settings_page',
        'dashicons-admin-generic',
        25
    );
}
add_action('admin_menu', 'bxb_ldash_add_admin_menu');

/**
 * Display settings page.
 */
function bxb_ldash_settings_page() {
    ?>
    <div class="wrap">
        <h1>BxB ldash Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('bxb_ldash_options');
            do_settings_sections('bxb_ldash');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
