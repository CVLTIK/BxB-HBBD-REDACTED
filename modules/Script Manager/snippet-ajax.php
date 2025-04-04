<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/* Handle Snippet Toggle AJAX Request */
function bxb_toggle_snippet() {
    check_ajax_referer('bxb_dashboard_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }
    
    $snippet = sanitize_text_field($_POST['snippet']);
    $enabled = (bool) $_POST['enabled'];
    
    $snippets = get_option('bxb_snippets', array());
    
    if (!isset($snippets[$snippet])) {
        wp_send_json_error('Snippet not found');
    }
    
    $snippets[$snippet]['enabled'] = $enabled;
    update_option('bxb_snippets', $snippets);
    
    wp_send_json_success();
}
add_action('wp_ajax_toggle_snippet', 'bxb_toggle_snippet');

/* Handle Add New Snippet AJAX Request */
function bxb_add_snippet() {
    check_ajax_referer('bxb_dashboard_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }
    
    $name = sanitize_text_field($_POST['name']);
    $description = sanitize_textarea_field($_POST['description']);
    $tags = array_map('trim', explode(',', sanitize_text_field($_POST['tags'] ?? '')));
    
    if (empty($name) || empty($description)) {
        wp_send_json_error('Name and description are required');
    }
    
    $snippets = get_option('bxb_snippets', array());
    $slug = sanitize_title($name);
    
    // Check if snippet with this name already exists
    if (isset($snippets[$slug])) {
        wp_send_json_error('A snippet with this name already exists');
    }
    
    // Add new snippet
    $snippets[$slug] = array(
        'name' => $name,
        'description' => $description,
        'code' => '',
        'documentation' => '',
        'enabled' => false,
        'tags' => $tags,
        'security' => array(
            'scope' => 'everywhere',
            'run_once' => false,
            'min_role' => 'manage_options'
        )
    );
    
    update_option('bxb_snippets', $snippets);
    wp_send_json_success();
}
add_action('wp_ajax_add_snippet', 'bxb_add_snippet'); 