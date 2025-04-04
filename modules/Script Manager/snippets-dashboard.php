<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue admin scripts and styles
function bxb_snippets_enqueue_scripts($hook) {
    // Only load on our plugin pages
    if (strpos($hook, 'bxb-snippets-dashboard') === false) {
        return;
    }
    
    // Get plugin version
    $version = defined('BXB_dashboard_VERSION') ? BXB_dashboard_VERSION : '1.0.2';
    
    // Enqueue styles
    wp_enqueue_style(
        'bxb-snippets-admin',
        plugins_url('assets/css/admin.css', __FILE__),
        array(),
        $version
    );
    
    // Enqueue scripts
    wp_enqueue_script(
        'bxb-snippets-admin',
        plugins_url('assets/js/admin.js', __FILE__),
        array('jquery'),
        $version,
        true
    );
    
    // Localize script
    wp_localize_script('bxb-snippets-admin', 'bxbSnippets', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('bxb_dashboard_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'bxb_snippets_enqueue_scripts');

/* Display Snippets Dashboard Page */
function bxb_snippets_dashboard_page() {
    // Get all snippets from the database
    $snippets = get_option('bxb_snippets', array());
    
    // Get all unique tags
    $all_tags = array();
    foreach ($snippets as $snippet) {
        if (!empty($snippet['tags'])) {
            $all_tags = array_merge($all_tags, $snippet['tags']);
        }
    }
    $all_tags = array_unique($all_tags);
    sort($all_tags);
    
    ?>
    <div class="wrap">
        <h1>Snippets Dashboard</h1>
        
        <!-- Tag Filter -->
        <div class="snippet-filters" style="margin-bottom: 20px;">
            <select id="tag-filter" style="min-width: 200px; padding: 5px;">
                <option value="">All Tags</option>
                <?php foreach ($all_tags as $tag): ?>
                    <option value="<?php echo esc_attr($tag); ?>"><?php echo esc_html($tag); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="snippets-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <!-- Add New Snippet Card -->
            <div class="snippet-card add-new" style="background: #fff; border: 2px dashed #ddd; border-radius: 4px; padding: 20px; text-align: center; cursor: pointer;">
                <h3>Add New Snippet</h3>
                <p>Click to create a new code snippet</p>
                <span class="dashicons dashicons-plus-alt" style="font-size: 40px; color: #ddd;"></span>
            </div>

            <?php foreach ($snippets as $slug => $snippet): ?>
                <div class="snippet-card" data-tags="<?php echo esc_attr(implode(' ', $snippet['tags'] ?? [])); ?>" style="background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                    <h3><?php echo esc_html($snippet['name']); ?></h3>
                    <p><?php echo esc_html($snippet['description']); ?></p>
                    
                    <?php if (!empty($snippet['tags'])): ?>
                        <div class="snippet-tags" style="margin: 10px 0;">
                            <?php foreach ($snippet['tags'] as $tag): ?>
                                <span class="tag" style="background: #f0f0f0; padding: 2px 8px; border-radius: 3px; margin-right: 5px; font-size: 12px;">
                                    <?php echo esc_html($tag); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="snippet-actions" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                        <a href="<?php echo admin_url('admin.php?page=bxb-snippet-settings&snippet=' . $slug); ?>" 
                           class="button button-secondary">
                            Edit
                        </a>
                        
                        <div class="snippet-toggle">
                            <label class="switch">
                                <input type="checkbox" 
                                       class="snippet-toggle-input" 
                                       data-snippet="<?php echo esc_attr($slug); ?>"
                                       <?php checked($snippet['enabled']); ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Add New Snippet Modal -->
        <div id="add-snippet-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
            <div style="background: #fff; width: 500px; margin: 50px auto; padding: 20px; border-radius: 4px;">
                <h2>Add New Snippet</h2>
                <form id="add-snippet-form">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Name</th>
                            <td>
                                <input type="text" name="snippet_name" class="regular-text" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Description</th>
                            <td>
                                <textarea name="snippet_description" rows="3" class="large-text" required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Tags</th>
                            <td>
                                <input type="text" name="snippet_tags" class="regular-text" placeholder="Comma-separated tags">
                                <p class="description">Separate tags with commas</p>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" class="button button-primary" value="Add Snippet">
                        <button type="button" class="button" id="cancel-add-snippet">Cancel</button>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }
    
    input:checked + .slider {
        background-color: #2196F3;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    .slider.round {
        border-radius: 34px;
    }
    
    .slider.round:before {
        border-radius: 50%;
    }

    .snippet-card.add-new:hover {
        border-color: #0073aa;
        background: #f8f9fa;
    }

    .snippet-card {
        transition: all 0.3s ease;
    }
    .snippet-card.hidden {
        display: none;
    }
    </style>
    <?php
} 