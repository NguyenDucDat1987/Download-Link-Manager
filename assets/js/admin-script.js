jQuery(document).ready(function ($) {

    // Open popup when clicking the button
    $('.dlm-insert-shortcode').on('click', function (e) {
        e.preventDefault();
        $('#dlm-shortcode-popup').fadeIn(200);
        $('body').css('overflow', 'hidden');
    });

    // Close popup
    function closePopup() {
        $('#dlm-shortcode-popup').fadeOut(200);
        $('body').css('overflow', 'auto');

        // Reset form
        $('#dlm-select-download').val('');
        $('#dlm-button-text').val('');
        $('#dlm-style').val('button');
        $('#dlm-show-version').prop('checked', true);
        $('#dlm-show-count').prop('checked', false);
        $('#dlm-shortcode-preview').html('<em style="color: #999;">Chọn download để xem mã shortcode...</em>');
        $('.dlm-insert-shortcode-btn').prop('disabled', true);
    }

    $('.dlm-popup-close').on('click', closePopup);

    $('.dlm-popup-overlay').on('click', closePopup);

    // Prevent popup content click from closing
    $('.dlm-popup-content').on('click', function (e) {
        e.stopPropagation();
    });

    // ESC key to close
    $(document).on('keyup', function (e) {
        if (e.key === 'Escape' && $('#dlm-shortcode-popup').is(':visible')) {
            closePopup();
        }
    });

    // Update preview when options change
    function updatePreview() {
        const downloadId = $('#dlm-select-download').val();
        const buttonText = $('#dlm-button-text').val();
        const style = $('#dlm-style').val();
        const showVersion = $('#dlm-show-version').is(':checked') ? 'yes' : 'no';
        const showCount = $('#dlm-show-count').is(':checked') ? 'yes' : 'no';

        if (!downloadId) {
            $('#dlm-shortcode-preview').html('<em style="color: #999;">Chọn download để xem mã shortcode...</em>');
            $('.dlm-insert-shortcode-btn').prop('disabled', true);
            return;
        }

        // Build shortcode
        let shortcode = '[download_link id="' + downloadId + '"';

        if (buttonText) {
            shortcode += ' text="' + buttonText + '"';
        }

        if (style !== 'button') {
            shortcode += ' style="' + style + '"';
        }

        if (showVersion !== 'yes') {
            shortcode += ' show_version="no"';
        }

        if (showCount === 'yes') {
            shortcode += ' show_count="yes"';
        }

        shortcode += ']';

        // Display with syntax highlighting
        const highlighted = shortcode
            .replace(/\[download_link/g, '<span style="color: #d63638;">[download_link</span>')
            .replace(/\]/g, '<span style="color: #d63638;">]</span>')
            .replace(/id=/g, '<span style="color: #0073aa;">id=</span>')
            .replace(/text=/g, '<span style="color: #0073aa;">text=</span>')
            .replace(/style=/g, '<span style="color: #0073aa;">style=</span>')
            .replace(/show_version=/g, '<span style="color: #0073aa;">show_version=</span>')
            .replace(/show_count=/g, '<span style="color: #0073aa;">show_count=</span>')
            .replace(/"([^"]*)"/g, '<span style="color: #008000;">"$1"</span>');

        $('#dlm-shortcode-preview').html(highlighted);
        $('.dlm-insert-shortcode-btn').prop('disabled', false);
    }

    // Attach change events
    $('#dlm-select-download, #dlm-button-text, #dlm-style, #dlm-show-version, #dlm-show-count').on('change input', updatePreview);

    // Insert shortcode into editor
    $('.dlm-insert-shortcode-btn').on('click', function () {
        const downloadId = $('#dlm-select-download').val();

        if (!downloadId) {
            alert('Vui lòng chọn một download!');
            return;
        }

        const buttonText = $('#dlm-button-text').val();
        const style = $('#dlm-style').val();
        const showVersion = $('#dlm-show-version').is(':checked') ? 'yes' : 'no';
        const showCount = $('#dlm-show-count').is(':checked') ? 'yes' : 'no';

        // Build shortcode
        let shortcode = '[download_link id="' + downloadId + '"';

        if (buttonText) {
            shortcode += ' text="' + buttonText + '"';
        }

        if (style !== 'button') {
            shortcode += ' style="' + style + '"';
        }

        if (showVersion !== 'yes') {
            shortcode += ' show_version="no"';
        }

        if (showCount === 'yes') {
            shortcode += ' show_count="yes"';
        }

        shortcode += ']';

        // Insert into editor
        let inserted = false;

        // 1. Try Gutenberg (Block Editor)
        if (document.body.classList.contains('block-editor-page') && window.wp && window.wp.data && window.wp.blocks) {
            const block = wp.blocks.createBlock('core/shortcode', {
                text: shortcode
            });
            wp.data.dispatch('core/block-editor').insertBlocks(block);
            inserted = true;
        } else {
            // 2. Try Classic Editor / TinyMCE
            if (window.tinyMCE && window.tinyMCE.activeEditor && !window.tinyMCE.activeEditor.isHidden()) {
                window.tinyMCE.activeEditor.execCommand('mceInsertContent', false, shortcode);
                inserted = true;
            } else if (window.send_to_editor) {
                // 3. Fallback to standard WP insert (works for Text tabs too)
                window.send_to_editor(shortcode);
                inserted = true;
            }
        }

        if (!inserted) {
            // Fallback: copy to clipboard
            copyToClipboard(shortcode);
            alert('Không tìm thấy trình soạn thảo! Shortcode đã được sao chép vào clipboard:\n\n' + shortcode);
        }

        closePopup();
    });

    // Copy to clipboard function
    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-999999px';
        document.body.appendChild(textarea);
        textarea.select();

        try {
            document.execCommand('copy');
        } catch (err) {
            console.error('Failed to copy:', err);
        }

        document.body.removeChild(textarea);
    }

    // Copy shortcode from meta box
    $(document).on('click', '.dlm-shortcode-copy-btn', function () {
        const shortcode = $(this).siblings('code').text();
        const button = $(this);

        copyToClipboard(shortcode);

        // Visual feedback
        button.text('✓ Đã Copy!').addClass('copied');

        setTimeout(function () {
            button.text('Copy').removeClass('copied');
        }, 2000);
    });

    // Quick copy from post list
    $(document).on('click', '.dlm-quick-copy', function (e) {
        e.preventDefault();
        const shortcode = $(this).data('shortcode');
        const button = $(this);
        const originalHtml = button.html();

        copyToClipboard(shortcode);

        button.html('<span class="dashicons dashicons-yes"></span> Đã Copy!').css('background', '#00a32a').css('color', 'white');

        setTimeout(function () {
            button.html(originalHtml).css('background', '').css('color', '');
        }, 2000);
    });
});