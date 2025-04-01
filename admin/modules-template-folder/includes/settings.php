<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register settings.
 */
function bxb_ldash_register_settings() {
    register_setting('bxb_ldash_options', 'bxb_ldash_settings');
    add_settings_section('bxb_ldash_section', 'General Settings', null, 'bxb_ldash');
}
add_action('admin_init', 'bxb_ldash_register_settings');
