<?php
/*
Plugin Name: BxB Server Setup
Description: A plugin to update all usernames with a specific client code to a new client code, update their display name and nickname to the company name, and update their passwords.
Version: 2.4
Author: Don Richards
*/

function update_usernames($client_code, $company_name) {
    global $wpdb;

    // Fetch users that match the client code pattern
    $users = $wpdb->get_results($wpdb->prepare("SELECT ID, user_login FROM {$wpdb->users} WHERE user_login LIKE %s", '%' . $wpdb->esc_like('-bxb-') . '%'));
    $updated_count = 0;
    $error_count = 0;
    $changed_usernames = [];

    foreach ($users as $user) {
        // Ensure the new username format is client_code-bxb-username
        $new_username = $client_code . '-bxb-' . preg_replace('/^[^_]+-bxb-/', '', $user->user_login);

        // Update the user_login and user_nicename
        $result = $wpdb->update(
            $wpdb->users,
            ['user_login' => $new_username, 'user_nicename' => sanitize_title($new_username)],
            ['ID' => $user->ID]
        );

        // Update display name and nickname
        $user_update_result = wp_update_user([
            'ID' => $user->ID,
            'display_name' => $company_name,
            'nickname' => $company_name
        ]);

        if ($result === false || is_wp_error($user_update_result)) {
            $error_count++;
        } else {
            $updated_count++;
            $changed_usernames[] = [
                'old' => $user->user_login,
                'new' => $new_username
            ];
        }
    }

    return ['updated_count' => $updated_count, 'error_count' => $error_count, 'changed_usernames' => $changed_usernames];
}

function generate_random_password($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_password = '';
    for ($i = 0; $i < $length; $i++) {
        $random_password .= $characters[random_int(0, $characters_length - 1)];
    }
    return $random_password;
}

function update_passwords() {
    global $wpdb;

    // Fetch users that match the client code pattern
    $users = $wpdb->get_results($wpdb->prepare("SELECT ID, user_email, user_login FROM {$wpdb->users} WHERE user_login LIKE %s", '%' . $wpdb->esc_like('-bxb-') . '%'));
    $updated_count = 0;
    $error_count = 0;
    $updated_users = [];

    foreach ($users as $user) {
        // Generate a random password
        $new_password = generate_random_password();

        // Update the password
        $result = wp_set_password($new_password, $user->ID);

        if (is_wp_error($result)) {
            $error_count++;
        } else {
            $updated_count++;
            $vault = get_vault_for_user($user->user_login);
            if (strpos($user->user_login, 'content') === false) {
            $updated_users[] = [
                'username' => $user->user_login,
                'password' => $new_password,
                'email' => $user->user_email,
                'vault' => $vault
            ];
        }
        }
    }

    return ['updated_count' => $updated_count, 'error_count' => $error_count, 'updated_users' => $updated_users];
}

function get_vault_for_user($username) {
    if (strpos($username, 'admin') !== false) {
        return 'Website Admin';
    } elseif (strpos($username, 'blogs') !== false) {
        return 'Website Blogs';
    } elseif (strpos($username, 'editor') !== false) {
        return 'Website Editor';
    } elseif (strpos($username, 'ppc') !== false) {
        return 'Website PPC Editor';
    } else {
        return 'General Vault';
    }
}

function get_current_client_codes() {
    global $wpdb;
    $client_codes = $wpdb->get_results("SELECT DISTINCT SUBSTRING_INDEX(user_login, '-bxb-', 1) AS client_code FROM {$wpdb->users} WHERE user_login LIKE '%-bxb-%'");
    return $client_codes;
}

function generate_csv_file($updated_users, $client_code, $company_name) {
    // Start output buffering to prevent any previous output from being sent
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Set headers to indicate a CSV file download
    $timestamp = date('Ymd_His');
    $filename = "updated_users_" . sanitize_file_name($client_code) . "_" . sanitize_file_name($company_name) . "_" . $timestamp . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Open output stream for CSV writing
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Client', 'Username', 'Password', 'Domain', 'Vault']);
    
    // Sort updated users by vault name in alphabetical order
    usort($updated_users, function($a, $b) {
        return strcmp($a['vault'], $b['vault']);
    });
    
    foreach ($updated_users as $user) {
        fputcsv($output, [
            '(' . strtoupper($client_code) . ') ' . $company_name . ' ' . str_replace('Website ', '', $user['vault']),
            strtolower($user['username']),
            $user['password'],
            'https://' . $_SERVER['HTTP_HOST'] . '/bxb', // Add https and /bxb at the end of the domain information
            $user['vault']
        ]);
    }
    fclose($output);
    exit;
}

// Create admin menu
function bxb_server_setup_menu() {
    add_menu_page('BxB Server Setup', 'BxB Server Setup', 'manage_options', 'bxb-server-setup', 'bxb_server_setup_page', '', 2);
}
add_action('admin_menu', 'bxb_server_setup_menu');

// Enqueue custom styles and scripts
/* function bxb_server_setup_styles() {
    echo '
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
    ';
}
add_action('admin_head', 'bxb_server_setup_styles'); */

// Display admin page
function bxb_server_setup_page() {
    $current_client_codes = get_current_client_codes();
    $results = null;
    $url = 'https://bxbtestgrounds.kinsta.cloud/bxb'; // Update this URL as needed

    // Check for valid POST request and nonce to ensure secure form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bxb_nonce']) && wp_verify_nonce($_POST['bxb_nonce'], 'bxb_server_setup')) {
        if (!empty($_POST['client_code']) && !empty($_POST['company_name']) && isset($_POST['update_client_code_passwords'])) {
            // Sanitize user inputs
            $client_code = sanitize_text_field($_POST['client_code']);
            $company_name = sanitize_text_field($_POST['company_name']);

            // Update usernames
            $result_usernames = update_usernames($client_code, $company_name);
            // Update passwords
            $result_passwords = update_passwords();
            $results = [
                'usernames' => $result_usernames,
                'passwords' => $result_passwords
            ];

            // Generate CSV file with updated credentials
            generate_csv_file($result_passwords['updated_users'], $client_code, $company_name);
        }
    }

    ?>
    <div class="wrap">
        <h1>BxB Server Setup</h1>
        <div class="bxb-server-setup-container">
            <div class="bxb-server-setup-form">
                <h2>Add Client Code and Password</h2>
                <p>Current Client Codes:
                <?php
                    if (!empty($current_client_codes)) {
                        foreach ($current_client_codes as $code) {
                            echo esc_html($code->client_code) . ' ';
                        }
                    } else {
                        echo 'No client codes found.';
                    }
                ?>
                </p>
                <form method="post" action="">
                    <?php wp_nonce_field('bxb_server_setup', 'bxb_nonce'); ?>
                    <table>
                        <tr>
                            <th><label for="client_code">Client Code</label></th>
                            <td><input type="text" id="client_code" name="client_code" required></td>
                        </tr>
                        <tr>
                            <th><label for="company_name">Company Name</label></th>
                            <td><input type="text" id="company_name" name="company_name" required></td>
                        </tr>
                    </table>
                    <input type="submit" name="update_client_code_passwords" value="Update Client Code and Passwords">
                </form>
            </div>
            <?php if ($results): ?>
            <div class="bxb-server-setup-results">
                <h2>Results</h2>
                <ul>
                    <?php if (!empty($results['usernames']['changed_usernames'])): ?>
                        <li>Updated Usernames:
                            <ul>
                                <?php foreach ($results['usernames']['changed_usernames'] as $username): ?>
                                    <li>Old: <?php echo esc_html($username['old']); ?>, New: <?php echo esc_html($username['new']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($results['passwords']['updated_users'])): ?>
                        <li>Updated Passwords:
                            <ul>
                                <?php foreach ($results['passwords']['updated_users'] as $user): ?>
                                    <li>Username: <?php echo esc_html($user['username']); ?>, Password: <?php echo esc_html($user['password']); ?>, Email: <?php echo esc_html($user['email']); ?>, Vault: <?php echo esc_html($user['vault']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if ($results['usernames']['error_count'] > 0 || $results['passwords']['error_count'] > 0): ?>
                        <li>Errors:
                            Usernames: <?php echo esc_html($results['usernames']['error_count']); ?>,
                            Passwords: <?php echo esc_html($results['passwords']['error_count']); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}