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
    if (!isset($_GET['snippet'])) {
        wp_die('No snippet specified');
    }

    $snippet_slug = sanitize_text_field($_GET['snippet']);
    $snippets = get_option('bxb_snippets', array());
    
    if (!isset($snippets[$snippet_slug])) {
        wp_die('Snippet not found');
    }

    $snippet = $snippets[$snippet_slug];
    
    // Handle form submission
    if (isset($_POST['save_snippet']) && check_admin_referer('save_snippet_' . $snippet_slug)) {
        $snippets[$snippet_slug] = array(
            'name' => sanitize_text_field($_POST['snippet_name']),
            'description' => sanitize_textarea_field($_POST['snippet_description']),
            'code' => wp_unslash($_POST['snippet_code']),
            'documentation' => wp_unslash($_POST['snippet_documentation']),
            'enabled' => isset($_POST['snippet_enabled']),
            'secure' => isset($_POST['snippet_secure'])
        );
        update_option('bxb_snippets', $snippets);
        $snippet = $snippets[$snippet_slug];
        echo '<div class="notice notice-success"><p>Snippet saved successfully.</p></div>';
    }
    
    ?>
    <div class="wrap">
        <h1>Edit Snippet: <?php echo esc_html($snippet['name']); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('save_snippet_' . $snippet_slug); ?>
            
            <div class="nav-tab-wrapper">
                <a href="#general" class="nav-tab nav-tab-active">General</a>
                <a href="#code" class="nav-tab">Code</a>
                <a href="#documentation" class="nav-tab">Documentation</a>
                <a href="#security" class="nav-tab">Security</a>
            </div>
            
            <div class="tab-content">
                <!-- General Tab -->
                <div id="general" class="tab-pane active" style="background: #fff; padding: 20px; border: 1px solid #ddd; margin-top: 20px;">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Name</th>
                            <td>
                                <input type="text" name="snippet_name" value="<?php echo esc_attr($snippet['name']); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Description</th>
                            <td>
                                <textarea name="snippet_description" rows="3" class="large-text"><?php echo esc_textarea($snippet['description']); ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Code Tab -->
                <div id="code" class="tab-pane" style="background: #fff; padding: 20px; border: 1px solid #ddd; margin-top: 20px; display: none;">
                    <textarea name="snippet_code" rows="20" class="large-text code"><?php echo esc_textarea($snippet['code']); ?></textarea>
                </div>
                
                <!-- Documentation Tab -->
                <div id="documentation" class="tab-pane" style="background: #fff; padding: 20px; border: 1px solid #ddd; margin-top: 20px; display: none;">
                    <textarea name="snippet_documentation" rows="20" class="large-text"><?php echo esc_textarea($snippet['documentation']); ?></textarea>
                </div>
                
                <!-- Security Tab -->
                <div id="security" class="tab-pane" style="background: #fff; padding: 20px; border: 1px solid #ddd; margin-top: 20px; display: none;">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Enabled</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="snippet_enabled" <?php checked($snippet['enabled']); ?>>
                                    Enable this snippet
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Security</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="snippet_secure" <?php checked($snippet['secure']); ?>>
                                    Enable security checks
                                </label>
                                <p class="description">When enabled, additional security checks will be performed before executing the snippet.</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <p class="submit">
                <input type="submit" name="save_snippet" class="button button-primary" value="Save Changes">
                <a href="<?php echo admin_url('admin.php?page=bxb-snippets-dashboard'); ?>" class="button">Back to Dashboard</a>
            </p>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Tab switching
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            // Update active tab
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Show corresponding content
            var target = $(this).attr('href');
            $('.tab-pane').hide();
            $(target).show();
        });
    });
    </script>
    <?php
} 