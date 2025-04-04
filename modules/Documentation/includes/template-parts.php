<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/* Render Documentation Tabs */
function bxb_render_documentation_tabs() {
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="#readme" class="nav-tab nav-tab-active">README</a>
        <a href="#plugin-changelog" class="nav-tab">Plugin Changelog</a>
        <a href="#layout-changelog" class="nav-tab">Layout Changelog</a>
    </h2>
    <?php
}

/* Render Documentation Content */
function bxb_render_documentation_content($content) {
    ?>
    <div class="tab-content">
        <div id="readme" class="tab-pane active" style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px;">
            <?php echo $content['readme']; ?>
        </div>
        
        <div id="plugin-changelog" class="tab-pane" style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px; display:none;">
            <?php echo $content['plugin_changelog']; ?>
        </div>
        
        <div id="layout-changelog" class="tab-pane" style="background:#fff; padding:15px; border:1px solid #ccc; max-width: 800px; display:none;">
            <?php echo $content['layout_changelog']; ?>
        </div>
    </div>
    <?php
}

/* Render Documentation Page Wrapper */
function bxb_render_documentation_wrapper($content) {
    ?>
    <div class="wrap">
        <h1>Documentation</h1>
        <?php
        bxb_render_documentation_tabs();
        bxb_render_documentation_content($content);
        ?>
    </div>
    <?php
} 