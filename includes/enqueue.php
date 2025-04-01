<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin styles and scripts.
 */
function bxb_ldash_enqueue_admin_assets() {
    wp_enqueue_style('bxb-ldash-admin', BXB_ldash_URL . 'css/admin.css', array(), BXB_ldash_VERSION);
    wp_enqueue_script('bxb-ldash-admin', BXB_ldash_URL . 'js/admin.js', array('jquery'), BXB_ldash_VERSION, true);
}
add_action('admin_enqueue_scripts', 'bxb_ldash_enqueue_admin_assets');
