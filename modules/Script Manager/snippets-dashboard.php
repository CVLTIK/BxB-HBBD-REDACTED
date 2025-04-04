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
    
    // Define the server setup snippet
    $server_setup_snippet = array(
        'server-setup' => array(
            'name' => 'Server Setup Manager',
            'description' => 'A comprehensive tool for managing server setup tasks including user management, password generation, client code updates, and security settings.',
            'code' => '<?php
/**
 * Server Setup Manager
 * 
 * This snippet provides comprehensive server setup functionality including:
 * - User management and security
 * - Password generation and updates
 * - Client code management
 * - CSV export of credentials
 * - Role-based access control
 */

class BxB_Server_Manager {
    private $options;
    private $toggle_options;
    private $min_role;

    public function __construct() {
        $this->options = get_option(\'bxb_server_setup\', array());
        $this->toggle_options = get_option(\'bxb_server_setup_toggle\', array(
            \'enabled\' => true,
            \'min_role\' => \'manage_options\'
        ));
        $this->min_role = $this->toggle_options[\'min_role\'] ?? \'manage_options\';
    }

    /**
     * Initialize the manager
     */
    public function init() {
        if ($this->toggle_options[\'enabled\']) {
            add_action(\'admin_menu\', array($this, \'add_admin_menu\'));
            add_action(\'admin_head\', array($this, \'enqueue_styles\'));
        }
    }

    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        if (current_user_can($this->min_role)) {
            add_submenu_page(
                \'bxb-dashboard\',
                __(\'Server Setup\', \'bxb-dashboard\'),
                __(\'Server Setup\', \'bxb-dashboard\'),
                $this->min_role,
                \'bxb-server-setup\',
                array($this, \'render_page\')
            );
        }
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_styles() {
        ?>
        <style>
            .bxb-server-setup-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-top: 20px;
            }
            .bxb-server-setup-form, .bxb-server-setup-results {
                background: #fff;
                border: 1px solid #ccd0d4;
                padding: 20px;
                box-shadow: 0 1px 1px rgba(0,0,0,0.04);
                width: 100%;
            }
            .bxb-server-setup-form h2, .bxb-server-setup-results h2 {
                font-size: 1.5em;
                margin-bottom: 10px;
            }
            .bxb-server-setup-form table, .bxb-server-setup-results table {
                width: 100%;
            }
            .bxb-server-setup-form table th, .bxb-server-setup-results table th {
                width: 25%;
                text-align: left;
                padding-right: 10px;
            }
            .bxb-server-setup-form table td, .bxb-server-setup-results table td {
                width: 75%;
            }
            .bxb-server-setup-form input[type="text"], .bxb-server-setup-form input[type="email"] {
                width: 100%;
                padding: 5px;
                font-size: 1em;
            }
            .bxb-server-setup-form input[type="submit"] {
                background: #0073aa;
                border: none;
                color: #fff;
                padding: 10px 20px;
                font-size: 1em;
                cursor: pointer;
            }
            .bxb-server-setup-form input[type="submit"]:hover {
                background: #005a87;
            }
            .bxb-server-setup-results ul {
                list-style-type: none;
                padding: 0;
            }
            .bxb-server-setup-results ul li {
                margin-bottom: 5px;
            }
            @media (max-width: 700px) {
                .bxb-server-setup-form, .bxb-server-setup-results {
                    width: 100%;
                }
            }
        </style>
        <?php
    }

    /**
     * Update usernames with new client code
     */
    public function update_usernames($client_code, $company_name) {
        global $wpdb;

        $users = $wpdb->get_results($wpdb->prepare("SELECT ID, user_login FROM {$wpdb->users} WHERE user_login LIKE %s", \'%\' . $wpdb->esc_like(\'-bxb-\') . \'%\'));
        $updated_count = 0;
        $error_count = 0;
        $changed_usernames = [];

        foreach ($users as $user) {
            $new_username = $client_code . \'-bxb-\' . preg_replace(\'/^[^_]+-bxb-/\', \'\', $user->user_login);

            $result = $wpdb->update(
                $wpdb->users,
                [\'user_login\'=> $new_username, \'user_nicename\' => sanitize_title($new_username)],
                [\'ID\' => $user->ID]
            );

            $user_update_result = wp_update_user([
                \'ID\' => $user->ID,
                \'display_name\' => $company_name,
                \'nickname\' => $company_name
            ]);

            if ($result === false || is_wp_error($user_update_result)) {
                $error_count++;
            } else {
                $updated_count++;
                $changed_usernames[] = [
                    \'old\' => $user->user_login,
                    \'new\' => $new_username
                ];
            }
        }

        return [\'updated_count\' => $updated_count, \'error_count\' => $error_count, \'changed_usernames\' => $changed_usernames];
    }

    /**
     * Generate a secure random password
     */
    public function generate_random_password($length = 20) {
        $characters = \'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ\';
        $characters_length = strlen($characters);
        $random_password = \'\';
        for ($i = 0; $i < $length; $i++) {
            $random_password .= $characters[random_int(0, $characters_length - 1)];
        }
        return $random_password;
    }

    /**
     * Update passwords for all BxB users
     */
    public function update_passwords() {
        global $wpdb;

        $users = $wpdb->get_results($wpdb->prepare("SELECT ID, user_email, user_login FROM {$wpdb->users} WHERE user_login LIKE %s", \'%\' . $wpdb->esc_like(\'-bxb-\') . \'%\'));
        $updated_count = 0;
        $error_count = 0;
        $updated_users = [];

        foreach ($users as $user) {
            $new_password = $this->generate_random_password();
            $result = wp_set_password($new_password, $user->ID);

            if (is_wp_error($result)) {
                $error_count++;
            } else {
                $updated_count++;
                $vault = $this->get_vault_for_user($user->user_login);
                if (strpos($user->user_login, \'content\') === false) {
                    $updated_users[] = [
                        \'username\' => $user->user_login,
                        \'password\' => $new_password,
                        \'email\' => $user->user_email,
                        \'vault\' => $vault
                    ];
                }
            }
        }

        return [\'updated_count\' => $updated_count, \'error_count\' => $error_count, \'updated_users\' => $updated_users];
    }

    /**
     * Get vault category for user
     */
    private function get_vault_for_user($username) {
        if (strpos($username, \'admin\') !== false) {
            return \'Website Admin\';
        } elseif (strpos($username, \'blogs\') !== false) {
            return \'Website Blogs\';
        } elseif (strpos($username, \'editor\') !== false) {
            return \'Website Editor\';
        } elseif (strpos($username, \'ppc\') !== false) {
            return \'Website PPC Editor\';
        } else {
            return \'General Vault\';
        }
    }

    /**
     * Generate CSV file with updated credentials
     */
    public function generate_csv_file($updated_users, $client_code, $company_name) {
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        $timestamp = date(\'Ymd_His\');
        $filename = "updated_users_" . sanitize_file_name($client_code) . "_" . sanitize_file_name($company_name) . "_" . $timestamp . ".csv";
        header(\'Content-Type: text/csv; charset=utf-8\');
        header(\'Content-Disposition: attachment; filename="\' . $filename . \'"\');
        
        $output = fopen(\'php://output\', \'w\');
        fputcsv($output, [\'Client\', \'Username\', \'Password\', \'Domain\', \'Vault\']);
        
        usort($updated_users, function($a, $b) {
            return strcmp($a[\'vault\'], $b[\'vault\']);
        });
        
        foreach ($updated_users as $user) {
            fputcsv($output, [
                \'(\' . strtoupper($client_code) . \') \' . $company_name . \' \' . str_replace(\'Website \', \'\', $user[\'vault\']),
                strtolower($user[\'username\']),
                $user[\'password\'],
                \'https://\' . $_SERVER[\'HTTP_HOST\'] . \'/bxb\',
                $user[\'vault\']
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * Get current client codes
     */
    public function get_current_client_codes() {
        global $wpdb;
        $client_codes = $wpdb->get_results("SELECT DISTINCT SUBSTRING_INDEX(user_login, \'-bxb-\', 1) AS client_code FROM {$wpdb->users} WHERE user_login LIKE \'%-bxb-%\'");
        return $client_codes;
    }

    /**
     * Render the admin page
     */
    public function render_page() {
        $current_client_codes = $this->get_current_client_codes();
        $results = null;
        $url = \'https://\' . $_SERVER[\'HTTP_HOST\'] . \'/bxb\';

        if ($_SERVER[\'REQUEST_METHOD\'] == \'POST\' && isset($_POST[\'bxb_nonce\']) && wp_verify_nonce($_POST[\'bxb_nonce\'], \'bxb_server_setup\')) {
            if (!empty($_POST[\'client_code\']) && !empty($_POST[\'company_name\']) && isset($_POST[\'update_client_code_passwords\'])) {
                $client_code = sanitize_text_field($_POST[\'client_code\']);
                $company_name = sanitize_text_field($_POST[\'company_name\']);

                $result_usernames = $this->update_usernames($client_code, $company_name);
                $result_passwords = $this->update_passwords();
                $results = [
                    \'usernames\' => $result_usernames,
                    \'passwords\' => $result_passwords
                ];

                $this->generate_csv_file($result_passwords[\'updated_users\'], $client_code, $company_name);
            }
        }
        ?>
        <div class="wrap">
            <h1><?php _e(\'Server Setup\', \'bxb-dashboard\'); ?></h1>
            
            <div class="bxb-server-setup-container">
                <div class="bxb-server-setup-form">
                    <h2><?php _e(\'Update Client Code and Passwords\', \'bxb-dashboard\'); ?></h2>
                    <form method="post" action="">
                        <?php wp_nonce_field(\'bxb_server_setup\', \'bxb_nonce\'); ?>
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e(\'Client Code\', \'bxb-dashboard\'); ?></th>
                                <td>
                                    <input type="text" name="client_code" class="regular-text" required>
                                    <p class="description"><?php _e(\'Enter the new client code (e.g., ABC)\', \'bxb-dashboard\'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e(\'Company Name\', \'bxb-dashboard\'); ?></th>
                                <td>
                                    <input type="text" name="company_name" class="regular-text" required>
                                    <p class="description"><?php _e(\'Enter the company name\', \'bxb-dashboard\'); ?></p>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <input type="submit" name="update_client_code_passwords" class="button button-primary" value="<?php _e(\'Update Client Code and Passwords\', \'bxb-dashboard\'); ?>">
                        </p>
                    </form>
                </div>

                <?php if ($results): ?>
                <div class="bxb-server-setup-results">
                    <h2><?php _e(\'Results\', \'bxb-dashboard\'); ?></h2>
                    <ul>
                        <li><?php printf(__(\'Updated %d usernames\', \'bxb-dashboard\'), $results[\'usernames\'][\'updated_count\']); ?></li>
                        <li><?php printf(__(\'Generated %d new passwords\', \'bxb-dashboard\'), $results[\'passwords\'][\'updated_count\']); ?></li>
                        <?php if ($results[\'usernames\'][\'error_count\'] > 0): ?>
                            <li><?php printf(__(\'%d errors occurred\', \'bxb-dashboard\'), $results[\'usernames\'][\'error_count\']); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

// Usage example:
// $server_manager = new BxB_Server_Manager();
// $server_manager->init();
// 
// // Update usernames and passwords
// $result = $server_manager->update_usernames(\'CLIENT\', \'Company Name\');
// $passwords = $server_manager->update_passwords();
// $server_manager->generate_csv_file($passwords[\'updated_users\'], \'CLIENT\', \'Company Name\');',
            'documentation' => '# Server Setup Manager Documentation

## Overview
The Server Setup Manager is a comprehensive tool for managing server setup tasks including user management, password generation, and client code updates.

## Features
- User management and security
- Password generation and updates
- Client code management
- CSV export of credentials
- Role-based access control
- Automatic user categorization
- Secure password generation
- Error handling and reporting

## Usage
1. Initialize the manager:
```php
$server_manager = new BxB_Server_Manager();
$server_manager->init();
```

2. Update usernames and company names:
```php
$result = $server_manager->update_usernames(\'CLIENT\', \'Company Name\');
```

3. Update passwords:
```php
$passwords = $server_manager->update_passwords();
```

4. Generate CSV file:
```php
$server_manager->generate_csv_file($passwords[\'updated_users\'], \'CLIENT\', \'Company Name\');
```

## Security Features
- Role-based access control
- Secure password generation
- Input sanitization
- Nonce verification
- Error handling
- Secure CSV export

## User Categories
- Website Admin
- Website Blogs
- Website Editor
- Website PPC Editor
- General Vault

## Troubleshooting
- Check user capabilities
- Verify database permissions
- Monitor error counts
- Check CSV file permissions
- Ensure proper role settings

## Best Practices
1. Always verify user capabilities before operations
2. Keep backups before making changes
3. Monitor error counts in results
4. Handle CSV files securely
5. Use strong passwords
6. Regular security audits',
            'enabled' => false,
            'secure' => true
        )
    );

    // Add the server setup snippet if it doesn\'t exist
    if (!isset($snippets[\'server-setup\'])) {
        $snippets[\'server-setup\'] = $server_setup_snippet[\'server-setup\'];
        update_option(\'bxb_snippets\', $snippets);
    }
    
    ?>
    <div class="wrap">
        <h1>Snippets Dashboard</h1>
        
        <div class="snippets-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <!-- Add New Snippet Card -->
            <div class="snippet-card add-new" style="background: #fff; border: 2px dashed #ddd; border-radius: 4px; padding: 20px; text-align: center; cursor: pointer;">
                <h3>Add New Snippet</h3>
                <p>Click to create a new code snippet</p>
                <span class="dashicons dashicons-plus-alt" style="font-size: 40px; color: #ddd;"></span>
            </div>

            <?php foreach ($snippets as $slug => $snippet): ?>
                <div class="snippet-card" style="background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                    <h3><?php echo esc_html($snippet[\'name\']); ?></h3>
                    <p><?php echo esc_html($snippet[\'description\']); ?></p>
                    
                    <div class="snippet-actions" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                        <a href="<?php echo admin_url(\'admin.php?page=bxb-snippet-settings&snippet=\' . $slug); ?>" 
                           class="button button-secondary">
                            Settings
                        </a>
                        
                        <div class="snippet-toggle">
                            <label class="switch">
                                <input type="checkbox" 
                                       class="snippet-toggle-input" 
                                       data-snippet="<?php echo esc_attr($slug); ?>"
                                       <?php checked($snippet[\'enabled\']); ?>>
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
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Existing toggle functionality
        $(\'.snippet-toggle-input\').on(\'change\', function() {
            var snippet = $(this).data(\'snippet\');
            var enabled = $(this).is(\':checked\');
            
            $.ajax({
                url: ajaxurl,
                type: \'POST\',
                data: {
                    action: \'toggle_snippet\',
                    snippet: snippet,
                    enabled: enabled,
                    nonce: \'<?php echo wp_create_nonce(\'toggle_snippet\'); ?>\'
                },
                success: function(response) {
                    if (!response.success) {
                        alert(\'Error toggling snippet\');
                    }
                }
            });
        });

        // Add New Snippet functionality
        $(\'.snippet-card.add-new\').on(\'click\', function() {
            $(\'#add-snippet-modal\').show();
        });

        $(\'#cancel-add-snippet\').on(\'click\', function() {
            $(\'#add-snippet-modal\').hide();
        });

        $(\'#add-snippet-form\').on(\'submit\', function(e) {
            e.preventDefault();
            
            var formData = {
                action: \'add_snippet\',
                name: $(\'input[name="snippet_name"]\').val(),
                description: $(\'textarea[name="snippet_description"]\').val(),
                nonce: \'<?php echo wp_create_nonce(\'add_snippet\'); ?>\'
            };

            $.ajax({
                url: ajaxurl,
                type: \'POST\',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(\'Error adding snippet: \' + response.data);
                    }
                }
            });
        });
    });
    </script>
    <?php
} 