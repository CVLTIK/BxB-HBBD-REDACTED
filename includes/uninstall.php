<?php

// Exit if accessed directly.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options.
delete_option('bxb_dashboard_settings');
