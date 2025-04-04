<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include Parsedown if not already loaded.
if (!class_exists('Parsedown')) {
    require_once plugin_dir_path(__FILE__) . '../../includes/parsedown.php';
}

/* Add Documentation Page */
function bxb_dashboard_add_documentation_page() {
    add_submenu_page(
        'bxb-dashboard', // Parent menu slug
        'Documentation', // Page title
        'Documentation', // Menu title
        'manage_options', // Capability
        'bxb-documentation', // Menu slug
        'bxb_documentation_page' // Callback function
    );
}
add_action('admin_menu', 'bxb_dashboard_add_documentation_page');

/* Display Documentation Page */
function bxb_documentation_page() {
    // Get the content of each markdown file
    $readme_path = BXB_dashboard_DIR . 'README.md';
    $pchangelog_path = BXB_dashboard_DIR . 'modules/Documentation/includes/plugin-changelog.md';
    $lchangelog_path = BXB_dashboard_DIR . 'modules/Documentation/includes/layout-changelog.md';
    
    $readme_content = file_exists($readme_path) ? file_get_contents($readme_path) : '';
    $pchangelog_content = file_exists($pchangelog_path) ? file_get_contents($pchangelog_path) : '';
    $lchangelog_content = file_exists($lchangelog_path) ? file_get_contents($lchangelog_path) : '';
    
    // Convert markdown to HTML
    $Parsedown = new Parsedown();
    $readme_html = $Parsedown->text($readme_content);
    $pchangelog_html = $Parsedown->text($pchangelog_content);
    $lchangelog_html = $Parsedown->text($lchangelog_content);
    
    // Output the page with tabs
    ?>
    <div class="wrap">
        <h1>Documentation</h1>
        
        <h2 class="nav-tab-wrapper">
            <a href="#readme" class="nav-tab nav-tab-active">README</a>
            <a href="#plugin-changelog" class="nav-tab">Plugin Changelog</a>
            <a href="#layout-changelog" class="nav-tab">Layout Changelog</a>
        </h2>
        
        <div class="tab-content">
            <div id="readme" class="tab-pane active" style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px;">
                <?php echo $readme_html; ?>
            </div>
            
            <div id="plugin-changelog" class="tab-pane" style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px; display:none;">
                <?php echo $pchangelog_html; ?>
            </div>
            
            <div id="layout-changelog" class="tab-pane" style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px; display:none;">
                <?php echo $lchangelog_html; ?>
            </div>
        </div>
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