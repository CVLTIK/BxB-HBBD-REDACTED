<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/* Get Documentation Content */
function bxb_get_documentation_content($file_path) {
    if (!file_exists($file_path)) {
        return '';
    }
    
    $content = file_get_contents($file_path);
    $Parsedown = new Parsedown();
    return $Parsedown->text($content);
}

/* Get Documentation File Paths */
function bxb_get_documentation_paths() {
    return array(
        'readme' => BXB_dashboard_DIR . 'README.md',
        'plugin_changelog' => BXB_dashboard_DIR . 'modules/Documentation/docs/plugin-changelog.md',
        'layout_changelog' => BXB_dashboard_DIR . 'modules/Documentation/docs/layout-changelog.md'
    );
}

/* Load All Documentation Content */
function bxb_load_documentation_content() {
    $paths = bxb_get_documentation_paths();
    
    return array(
        'readme' => bxb_get_documentation_content($paths['readme']),
        'plugin_changelog' => bxb_get_documentation_content($paths['plugin_changelog']),
        'layout_changelog' => bxb_get_documentation_content($paths['layout_changelog'])
    );
} 