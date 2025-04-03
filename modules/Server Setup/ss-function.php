<?php
function update_usernames($client_code, $company_name) {
    global $wpdb;

    $users = $wpdb->get_results($wpdb->prepare("SELECT ID, user_login FROM {$wpdb->users} WHERE user_login LIKE %s", '%' . $wpdb->esc_like('-bxb-') . '%'));
    $updated_count = 0;
    $error_count = 0;
    $changed_usernames = [];

    foreach ($users as $user) {
        $new_username = $client_code . '-bxb-' . preg_replace('/^[^_]+-bxb-/', '', $user->user_login);
        $result = $wpdb->update(
            $wpdb->users,
            ['user_login'=> $new_username, 'user_nicename' => sanitize_title($new_username)],
            ['ID' => $user->ID]
        );
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

    $users = $wpdb->get_results($wpdb->prepare("SELECT ID, user_email, user_login FROM {$wpdb->users} WHERE user_login LIKE %s", '%' . $wpdb->esc_like('-bxb-') . '%'));
    $updated_count = 0;
    $error_count = 0;
    $updated_users = [];

    foreach ($users as $user) {
        $new_password = generate_random_password();
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
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    $timestamp = date('Ymd_His');
    $filename = "updated_users_" . sanitize_file_name($client_code) . "_" . sanitize_file_name($company_name) . "_" . $timestamp . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Client', 'Username', 'Password', 'Domain', 'Vault']);
    
    usort($updated_users, function($a, $b) {
        return strcmp($a['vault'], $b['vault']);
    });
    
    foreach ($updated_users as $user) {
        fputcsv($output, [
            '(' . strtoupper($client_code) . ') ' . $company_name . ' ' . str_replace('Website ', '', $user['vault']),
            strtolower($user['username']),
            $user['password'],
            'https://' . $_SERVER['HTTP_HOST'] . '/bxb',
            $user['vault']
        ]);
    }
    fclose($output);
    exit;
}

function bxb_server_setup_menu() {
    add_menu_page('BxB Server Setup', 'BxB Server Setup', 'manage_options', 'bxb-server-setup', 'bxb_server_setup_page', '', 2);
}
add_action('admin_menu', 'bxb_server_setup_menu');

function bxb_server_setup_page() {
    $current_client_codes = get_current_client_codes();
    $results = null;
    $url = 'https://bxbtestgrounds.kinsta.cloud/bxb';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bxb_nonce']) && wp_verify_nonce($_POST['bxb_nonce'], 'bxb_server_setup')) {
        if (!empty($_POST['client_code']) && !empty($_POST['company_name']) && isset($_POST['update_client_code_passwords'])) {
            $client_code = sanitize_text_field($_POST['client_code']);
            $company_name = sanitize_text_field($_POST['company_name']);
            $result_usernames = update_usernames($client_code, $company_name);
            $result_passwords = update_passwords();
            $results = [
                'usernames' => $result_usernames,
                'passwords' => $result_passwords
            ];
            generate_csv_file($result_passwords['updated_users'], $client_code, $company_name);
        }
    }
    
    include 'includes/ss-view.php';  // Include HTML template
}
?>