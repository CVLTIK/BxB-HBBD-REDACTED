<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/* Add Snippet Settings Page */
function bxb_dashboard_add_snippet_settings_page() {
    add_submenu_page(
        null, // Hide from menu
        'Snippet Settings',
        'Snippet Settings',
        'manage_options',
        'bxb-snippet-settings',
        'bxb_snippet_settings_page'
    );
}
add_action('admin_menu', 'bxb_dashboard_add_snippet_settings_page');

/* Display Snippet Settings Page */
function bxb_snippet_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $snippet_slug = isset($_GET['snippet']) ? sanitize_text_field($_GET['snippet']) : '';
    $snippets = get_option('bxb_snippets', array());
    
    if (empty($snippet_slug) || !isset($snippets[$snippet_slug])) {
        wp_die(__('Invalid snippet.'));
    }

    $snippet = $snippets[$snippet_slug];

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bxb_snippet_nonce']) && wp_verify_nonce($_POST['bxb_snippet_nonce'], 'bxb_snippet_settings')) {
        $snippet['name'] = sanitize_text_field($_POST['snippet_name']);
        $snippet['description'] = sanitize_textarea_field($_POST['snippet_description']);
        $snippet['code'] = $_POST['snippet_code'];
        $snippet['documentation'] = $_POST['snippet_documentation'];
        $snippet['tags'] = array_map('trim', explode(',', sanitize_text_field($_POST['snippet_tags'])));
        $snippet['security'] = array(
            'scope' => sanitize_text_field($_POST['snippet_scope']),
            'run_once' => isset($_POST['snippet_run_once']),
            'min_role' => sanitize_text_field($_POST['snippet_min_role'])
        );

        $snippets[$snippet_slug] = $snippet;
        update_option('bxb_snippets', $snippets);
        
        echo '<div class="notice notice-success"><p>Snippet updated successfully.</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Edit Snippet: <?php echo esc_html($snippet['name']); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('bxb_snippet_settings', 'bxb_snippet_nonce'); ?>
            
            <div class="nav-tab-wrapper">
                <a href="#general" class="nav-tab nav-tab-active">General</a>
                <a href="#code" class="nav-tab">Code</a>
                <a href="#documentation" class="nav-tab">Documentation</a>
                <a href="#security" class="nav-tab">Security</a>
            </div>
            
            <div id="general" class="tab-content" style="display: block;">
                <table class="form-table">
                    <tr>
                        <th scope="row">Name</th>
                        <td>
                            <input type="text" name="snippet_name" class="regular-text" value="<?php echo esc_attr($snippet['name']); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Description</th>
                        <td>
                            <textarea name="snippet_description" rows="3" class="large-text" required><?php echo esc_textarea($snippet['description']); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Tags</th>
                        <td>
                            <input type="text" name="snippet_tags" class="regular-text" value="<?php echo esc_attr(implode(', ', $snippet['tags'] ?? [])); ?>" placeholder="Comma-separated tags">
                            <p class="description">Separate tags with commas</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div id="code" class="tab-content" style="display: none;">
                <table class="form-table">
                    <tr>
                        <td colspan="2">
                            <textarea name="snippet_code" rows="20" class="large-text code" style="font-family: monospace;"><?php echo esc_textarea($snippet['code']); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div id="documentation" class="tab-content" style="display: none;">
                <table class="form-table">
                    <tr>
                        <td colspan="2">
                            <textarea name="snippet_documentation" rows="20" class="large-text"><?php echo esc_textarea($snippet['documentation']); ?></textarea>
                            <p class="description">Use Markdown for formatting</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div id="security" class="tab-content" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th scope="row">Execution Scope</th>
                        <td>
                            <select name="snippet_scope" class="regular-text">
                                <option value="everywhere" <?php selected($snippet['security']['scope'] ?? 'everywhere', 'everywhere'); ?>>Everywhere</option>
                                <option value="backend" <?php selected($snippet['security']['scope'] ?? '', 'backend'); ?>>Backend Only</option>
                                <option value="frontend" <?php selected($snippet['security']['scope'] ?? '', 'frontend'); ?>>Frontend Only</option>
                            </select>
                            <p class="description">Where should this snippet be executed?</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Run Once</th>
                        <td>
                            <label>
                                <input type="checkbox" name="snippet_run_once" <?php checked($snippet['security']['run_once'] ?? false); ?>>
                                Execute this snippet only once
                            </label>
                            <p class="description">If checked, the snippet will be executed only once and then disabled</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Minimum Role</th>
                        <td>
                            <select name="snippet_min_role" class="regular-text">
                                <option value="manage_options" <?php selected($snippet['security']['min_role'] ?? 'manage_options', 'manage_options'); ?>>Administrator</option>
                                <option value="edit_posts" <?php selected($snippet['security']['min_role'] ?? '', 'edit_posts'); ?>>Editor</option>
                                <option value="publish_posts" <?php selected($snippet['security']['min_role'] ?? '', 'publish_posts'); ?>>Author</option>
                                <option value="edit_posts" <?php selected($snippet['security']['min_role'] ?? '', 'edit_posts'); ?>>Contributor</option>
                            </select>
                            <p class="description">Minimum user role required to execute this snippet</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <p class="submit">
                <input type="submit" class="button button-primary" value="Save Changes">
                <a href="<?php echo admin_url('admin.php?page=bxb-snippets-dashboard'); ?>" class="button">Back to Dashboard</a>
            </p>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            $('.nav-tab').removeClass('nav-tab-active');
            
            // Add active class to clicked tab
            $(this).addClass('nav-tab-active');
            
            // Hide all content
            $('.tab-content').hide();
            
            // Show selected content
            $($(this).attr('href')).show();
        });
    });
    </script>
    <?php
} 