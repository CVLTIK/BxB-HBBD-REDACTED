<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Check if ACF is installed and activated.
if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'key' => 'group_bxb_dashboard_settings',
        'title' => 'BxB dashboard Settings',
        'fields' => array(
            array(
                'key' => 'field_header_logo',
                'label' => 'Header Logo',
                'name' => 'header_logo',
                'type' => 'image',
                'return_format' => 'url',
            ),
            array(
                'key' => 'field_favicon',
                'label' => 'Favicon',
                'name' => 'favicon',
                'type' => 'image',
                'return_format' => 'url',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'bxb-dashboard',
                ),
            ),
        ),
    ));
}
