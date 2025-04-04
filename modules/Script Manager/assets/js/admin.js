jQuery(document).ready(function($) {
    // Tag filtering
    $('#tag-filter').on('change', function() {
        var selectedTag = $(this).val();
        $('.snippet-card').each(function() {
            if (selectedTag === '' || $(this).data('tags').split(' ').includes(selectedTag)) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
    });

    // Toggle snippet functionality
    $('.snippet-toggle-input').on('change', function() {
        var snippet = $(this).data('snippet');
        var enabled = $(this).is(':checked');
        var $toggle = $(this);
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'toggle_snippet',
                snippet: snippet,
                enabled: enabled,
                nonce: bxbSnippets.nonce
            },
            success: function(response) {
                if (!response.success) {
                    alert('Error toggling snippet');
                    // Revert the toggle
                    $toggle.prop('checked', !enabled);
                }
            },
            error: function() {
                alert('Error toggling snippet');
                // Revert the toggle
                $toggle.prop('checked', !enabled);
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
            tags: $('input[name="snippet_tags"]').val().split(',').map(tag => tag.trim()).filter(tag => tag),
            nonce: bxbSnippets.nonce
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    var errorMessage = 'Error adding snippet';
                    if (response.data) {
                        errorMessage += ': ' + response.data;
                    }
                    if (response.debug) {
                        console.log('Debug info:', response.debug);
                    }
                    alert(errorMessage);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                alert('Error adding snippet: ' + error + '\nCheck browser console for details');
            }
        });
    });
}); 