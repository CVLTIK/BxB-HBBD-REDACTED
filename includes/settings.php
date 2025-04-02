<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register settings.
 */
function bxb_dashboard_register_settings() {
    register_setting('bxb_dashboard_options', 'bxb_dashboard_settings');
    add_settings_section('bxb_dashboard_section', 'General Settings', null, 'bxb_dashboard');
}
add_action('admin_init', 'bxb_dashboard_register_settings');
