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
            'server-setup' => array(
                'name' => 'Server Setup Manager',
                'description' => 'A comprehensive tool for managing server setup tasks including user management, password generation, and client code updates.',
                'code' => '<?php
/**
 * Server Setup Manager
 * 
 * This snippet provides functionality for managing server setup tasks including:
 * - User management
 * - Password generation
 * - Client code updates
 * - CSV export of credentials
 */

class BxB_Server_Manager {
    private $options;
    private $toggle_options;

    public function __construct() {
        $this->options = get_option(\'bxb_server_setup\', array());
        $this->toggle_options = get_option(\'bxb_server_setup_toggle\', array(\'enabled\' => true));
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
}

// Usage example:
// $server_manager = new BxB_Server_Manager();
// $result = $server_manager->update_usernames(\'CLIENT\', \'Company Name\');
// $passwords = $server_manager->update_passwords();
// $server_manager->generate_csv_file($passwords[\'updated_users\'], \'CLIENT\', \'Company Name\');',
                'documentation' => '# Server Setup Manager Documentation

## Overview
The Server Setup Manager is a comprehensive tool for managing server setup tasks including user management, password generation, and client code updates.

## Features
- Update usernames with new client codes
- Generate secure random passwords
- Update user display names and nicknames
- Export credentials to CSV
- Categorize users by vault type

## Usage
1. Initialize the manager:
```php
$server_manager = new BxB_Server_Manager();
```

2. Update usernames:
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

## Security Notes
- All passwords are generated using cryptographically secure methods
- The CSV file contains sensitive information and should be handled securely
- Only administrators should have access to this functionality

## Troubleshooting
- Ensure proper database permissions
- Verify user capabilities
- Check for proper file permissions when generating CSV
- Monitor error counts in returned results',
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
            <!-- Add New Snippet Card -->
            <div class="snippet-card add-new" style="background: #fff; border: 2px dashed #ddd; border-radius: 4px; padding: 20px; text-align: center; cursor: pointer;">
                <h3>Add New Snippet</h3>
                <p>Click to create a new code snippet</p>
                <span class="dashicons dashicons-plus-alt" style="font-size: 40px; color: #ddd;"></span>
            </div>

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

        // Add New Snippet functionality
        $('.snippet-card.add-new').on('click', function() {
            $('#add-snippet-modal').show();
        });

        $('#cancel-add-snippet').on('click', function() {
            $('#add-snippet-modal').hide();
        });

        $('#add-snippet-form').on('submit', function(e) {
            e.preventDefault();
            
            var formData = {
                action: 'add_snippet',
                name: $('input[name="snippet_name"]').val(),
                description: $('textarea[name="snippet_description"]').val(),
                nonce: '<?php echo wp_create_nonce('add_snippet'); ?>'
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error adding snippet: ' + response.data);
                    }
                }
            });
        });
    });
    </script>
    <?php
} 