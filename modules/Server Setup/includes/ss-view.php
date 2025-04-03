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