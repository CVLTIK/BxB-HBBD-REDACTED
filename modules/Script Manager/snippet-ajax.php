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
    
    // Get existing snippets
    $snippets = get_option('bxb_snippets', array());
    $slug = sanitize_title($name);
    
    // Check if snippet with this name already exists
    if (isset($snippets[$slug])) {
        wp_send_json_error('A snippet with this name already exists');
    }
    
    // Create new snippet while preserving existing ones
    $new_snippet = array(
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
    
    // Add new snippet to existing snippets
    $snippets[$slug] = $new_snippet;
    
    // Debug information
    $debug_info = array(
        'snippet_count' => count($snippets),
        'new_slug' => $slug,
        'snippet_size' => strlen(serialize($snippets))
    );
    
    // Check if we can write to the options table
    global $wpdb;
    
    // First check if the option exists
    $option_exists = $wpdb->get_var($wpdb->prepare(
        "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s",
        'bxb_snippets'
    ));
    
    if ($option_exists) {
        // Try to update the existing option
        $result = $wpdb->update(
            $wpdb->options,
            array('option_value' => maybe_serialize($snippets)),
            array('option_name' => 'bxb_snippets'),
            array('%s'),
            array('%s')
        );
        
        if ($result === false) {
            $last_error = $wpdb->last_error;
            error_log('BxB Snippet Update Error: ' . $last_error);
            wp_send_json_error('Failed to update snippets: ' . $last_error);
        }
    } else {
        // Try to add the new option
        $result = $wpdb->insert(
            $wpdb->options,
            array(
                'option_name' => 'bxb_snippets',
                'option_value' => maybe_serialize($snippets),
                'autoload' => 'no'
            ),
            array('%s', '%s', '%s')
        );
        
        if ($result === false) {
            $last_error = $wpdb->last_error;
            error_log('BxB Snippet Insert Error: ' . $last_error);
            wp_send_json_error('Failed to create snippets: ' . $last_error);
        }
    }
    
    wp_send_json_success(array(
        'message' => 'Snippet added successfully',
        'snippet' => $new_snippet,
        'debug' => $debug_info
    ));
}
add_action('wp_ajax_add_snippet', 'bxb_add_snippet'); 