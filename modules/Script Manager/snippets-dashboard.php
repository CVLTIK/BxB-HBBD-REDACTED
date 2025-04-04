<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/* Add Snippets Dashboard Page */
function bxb_dashboard_add_snippets_page() {
    add_submenu_page(
        'bxb-dashboard',
        'Snippets Dashboard',
        'Snippets',
        'manage_options',
        'bxb-snippets-dashboard',
        'bxb_snippets_dashboard_page'
    );
}
add_action('admin_menu', 'bxb_dashboard_add_snippets_page');

/* Display Snippets Dashboard Page */
function bxb_snippets_dashboard_page() {
    // Get all snippets from the database
    $snippets = get_option('bxb_snippets', array());
    
    // Add some sample snippets if none exist
    if (empty($snippets)) {
        $snippets = array(
            'sample-snippet' => array(
                'name' => 'Sample Snippet',
                'description' => 'This is a sample snippet that demonstrates the functionality.',
                'code' => '// Sample code here',
                'documentation' => 'Documentation for how to use this snippet.',
                'enabled' => false,
                'secure' => true
            )
        );
        update_option('bxb_snippets', $snippets);
    }
    
    ?>
    <div class="wrap">
        <h1>Snippets Dashboard</h1>
        
        <div class="snippets-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <?php foreach ($snippets as $slug => $snippet): ?>
                <div class="snippet-card" style="background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                    <h3><?php echo esc_html($snippet['name']); ?></h3>
                    <p><?php echo esc_html($snippet['description']); ?></p>
                    
                    <div class="snippet-actions" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                        <a href="<?php echo admin_url('admin.php?page=bxb-snippet-settings&snippet=' . $slug); ?>" 
                           class="button button-secondary">
                            Settings
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
    </style>

    <script>
    jQuery(document).ready(function($) {
        $('.snippet-toggle-input').on('change', function() {
            var snippet = $(this).data('snippet');
            var enabled = $(this).is(':checked');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'toggle_snippet',
                    snippet: snippet,
                    enabled: enabled,
                    nonce: '<?php echo wp_create_nonce('toggle_snippet'); ?>'
                },
                success: function(response) {
                    if (!response.success) {
                        alert('Error toggling snippet');
                    }
                }
            });
        });
    });
    </script>
    <?php
} 