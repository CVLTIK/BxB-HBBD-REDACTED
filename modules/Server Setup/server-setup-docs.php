<?php
/**
 * Server Setup Documentation
 * 
 * @package BxB Dashboard
 * @subpackage Server Setup
 */

if (!defined('ABSPATH')) {
    exit;
}

class BxB_Server_Setup_Docs {
    public function __construct() {
        $this->init();
    }

    public function init() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu() {
        add_submenu_page(
            'bxb-dashboard',
            __('Server Setup Documentation', 'bxb-dashboard'),
            __('Server Setup Docs', 'bxb-dashboard'),
            'manage_options',
            'bxb-server-setup-docs',
            array($this, 'render_page')
        );
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Server Setup Documentation', 'bxb-dashboard'); ?></h1>
            
            <div class="bxb-docs-container">
                <div class="bxb-docs-section">
                    <h2><?php _e('Overview', 'bxb-dashboard'); ?></h2>
                    <p><?php _e('The Server Setup module allows you to manage client codes, update user information, and generate new passwords for BxB users.', 'bxb-dashboard'); ?></p>
                </div>

                <div class="bxb-docs-section">
                    <h2><?php _e('Features', 'bxb-dashboard'); ?></h2>
                    <ul>
                        <li><?php _e('Update client codes for all BxB users', 'bxb-dashboard'); ?></li>
                        <li><?php _e('Update company names for user display', 'bxb-dashboard'); ?></li>
                        <li><?php _e('Generate secure random passwords', 'bxb-dashboard'); ?></li>
                        <li><?php _e('Download CSV file with updated credentials', 'bxb-dashboard'); ?></li>
                        <li><?php _e('View current client codes in the system', 'bxb-dashboard'); ?></li>
                    </ul>
                </div>

                <div class="bxb-docs-section">
                    <h2><?php _e('Usage', 'bxb-dashboard'); ?></h2>
                    <ol>
                        <li><?php _e('Navigate to BxB Dashboard > Server Setup', 'bxb-dashboard'); ?></li>
                        <li><?php _e('Enter the new client code and company name', 'bxb-dashboard'); ?></li>
                        <li><?php _e('Click "Update Client Code and Passwords"', 'bxb-dashboard'); ?></li>
                        <li><?php _e('A CSV file will automatically download with the updated credentials', 'bxb-dashboard'); ?></li>
                    </ol>
                </div>

                <div class="bxb-docs-section">
                    <h2><?php _e('Security Notes', 'bxb-dashboard'); ?></h2>
                    <ul>
                        <li><?php _e('All passwords are generated using cryptographically secure methods', 'bxb-dashboard'); ?></li>
                        <li><?php _e('The CSV file contains sensitive information and should be handled securely', 'bxb-dashboard'); ?></li>
                        <li><?php _e('Only administrators can access this functionality', 'bxb-dashboard'); ?></li>
                    </ul>
                </div>

                <div class="bxb-docs-section">
                    <h2><?php _e('Troubleshooting', 'bxb-dashboard'); ?></h2>
                    <ul>
                        <li>
                            <strong><?php _e('No users found:', 'bxb-dashboard'); ?></strong>
                            <?php _e('Ensure there are users with the BxB prefix in their usernames', 'bxb-dashboard'); ?>
                        </li>
                        <li>
                            <strong><?php _e('CSV download issues:', 'bxb-dashboard'); ?></strong>
                            <?php _e('Check browser settings and ensure pop-ups are allowed', 'bxb-dashboard'); ?>
                        </li>
                        <li>
                            <strong><?php _e('Update errors:', 'bxb-dashboard'); ?></strong>
                            <?php _e('Verify database permissions and user capabilities', 'bxb-dashboard'); ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
} 