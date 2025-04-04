jQuery(document).ready(function($) {
    // Add new script row
    $('#add-script').on('click', function() {
        const timestamp = Date.now();
        const newRow = `
            <div class="script-row">
                <input type="text" name="bxb_script_manager[scripts][${timestamp}][name]" 
                       placeholder="<?php _e('Script Name', 'bxb-dashboard'); ?>">
                <input type="url" name="bxb_script_manager[scripts][${timestamp}][url]" 
                       placeholder="<?php _e('Script URL', 'bxb-dashboard'); ?>">
                <select name="bxb_script_manager[scripts][${timestamp}][location]">
                    <option value="header"><?php _e('Header', 'bxb-dashboard'); ?></option>
                    <option value="footer" selected><?php _e('Footer', 'bxb-dashboard'); ?></option>
                </select>
                <label>
                    <input type="checkbox" name="bxb_script_manager[scripts][${timestamp}][enabled]" checked>
                    <?php _e('Enabled', 'bxb-dashboard'); ?>
                </label>
                <button type="button" class="button remove-script"><?php _e('Remove', 'bxb-dashboard'); ?></button>
            </div>
        `;
        $('#script-list').append(newRow);
    });

    // Remove script row
    $(document).on('click', '.remove-script', function() {
        $(this).closest('.script-row').fadeOut(300, function() {
            $(this).remove();
        });
    });
}); 