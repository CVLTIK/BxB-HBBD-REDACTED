<?php
/**
 * Script Manager Module
 * 
 * @package BxB Dashboard
 * @subpackage Script Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class BxB_Script_Manager {
    private $options;
    private $option_name = 'bxb_snippets';

    public function __construct() {
        $this->options = get_option($this->option_name, array());
        $this->init();
    }

    public function init() {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_toggle_snippet', array($this, 'ajax_toggle_snippet'));
        add_action('wp_ajax_add_snippet', array($this, 'ajax_add_snippet'));
    }

    public function register_settings() {
        register_setting(
            $this->option_name,
            $this->option_name,
            array($this, 'sanitize_settings')
        );
    }

    public function sanitize_settings($input) {
        $sanitized = array();
        if (isset($input['scripts'])) {
            foreach ($input['scripts'] as $key => $script) {
                $sanitized['scripts'][$key] = array(
                    'name' => sanitize_text_field($script['name']),
                    'description' => sanitize_textarea_field($script['description']),
                    'code' => wp_kses_post($script['code']),
                    'documentation' => wp_kses_post($script['documentation']),
                    'enabled' => isset($script['enabled']) ? true : false,
                    'tags' => array_map('sanitize_text_field', explode(',', $script['tags'] ?? '')),
                    'security' => array(
                        'scope' => sanitize_text_field($script['security']['scope'] ?? 'everywhere'),
                        'run_once' => isset($script['security']['run_once']),
                        'min_role' => sanitize_text_field($script['security']['min_role'] ?? 'manage_options')
                    )
                );
            }
        }
        return $sanitized;
    }

    public function enqueue_scripts() {
        if (!empty($this->options['scripts'])) {
            foreach ($this->options['scripts'] as $script) {
                if ($script['enabled'] && !empty($script['code'])) {
                    // Check if user has required role
                    if (!current_user_can($script['security']['min_role'])) {
                        continue;
                    }

                    // Check if script should run only once
                    if ($script['security']['run_once'] && get_option('bxb_snippet_run_' . $script['name'])) {
                        continue;
                    }

                    // Check scope
                    if ($script['security']['scope'] === 'backend' && !is_admin()) {
                        continue;
                    }
                    if ($script['security']['scope'] === 'frontend' && is_admin()) {
                        continue;
                    }

                    // Execute the code
                    eval($script['code']);

                    // Mark as run if run_once is enabled
                    if ($script['security']['run_once']) {
                        update_option('bxb_snippet_run_' . $script['name'], true);
                    }
                }
            }
        }
    }

    public function ajax_toggle_snippet() {
        check_ajax_referer('bxb_dashboard_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $snippet = sanitize_text_field($_POST['snippet']);
        $enabled = (bool) $_POST['enabled'];
        
        if (isset($this->options['scripts'][$snippet])) {
            $this->options['scripts'][$snippet]['enabled'] = $enabled;
            update_option($this->option_name, $this->options);
            wp_send_json_success();
        }
        
        wp_send_json_error('Snippet not found');
    }

    public function ajax_add_snippet() {
        check_ajax_referer('bxb_dashboard_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $name = sanitize_text_field($_POST['name']);
        $description = sanitize_textarea_field($_POST['description']);
        $tags = array_map('sanitize_text_field', explode(',', $_POST['tags'] ?? ''));
        
        if (empty($name) || empty($description)) {
            wp_send_json_error('Name and description are required');
        }

        $slug = sanitize_title($name);
        
        if (isset($this->options['scripts'][$slug])) {
            wp_send_json_error('A snippet with this name already exists');
        }

        $this->options['scripts'][$slug] = array(
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

        update_option($this->option_name, $this->options);
        wp_send_json_success();
    }
} 