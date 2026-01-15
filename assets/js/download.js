jQuery(document).ready(function ($) {
    // Handle download tracking for shortcode buttons
    $('.dlm-button, .dlm-box-button, .dlm-link').on('click', function (e) {
        const href = $(this).attr('href');

        // Only track if it's a DLM download URL
        if (href && href.indexOf('dlm-download=') !== -1) {
            // We do NOT prevent default here so the new tab opens immediately
            // e.preventDefault(); 

            const downloadId = new URL(href).searchParams.get('dlm-download');

            // Track the download in background (fire and forget)
            if (navigator.sendBeacon) {
                const formData = new FormData();
                formData.append('action', 'dlm_track_download');
                formData.append('download_id', downloadId);
                formData.append('nonce', dlmData.nonce);

                navigator.sendBeacon(dlmData.ajaxurl, formData);
            } else {
                // Fallback for older browsers: async AJAX
                $.ajax({
                    url: dlmData.ajaxurl,
                    type: 'POST',
                    async: true, // Don't block
                    data: {
                        action: 'dlm_track_download',
                        download_id: downloadId,
                        nonce: dlmData.nonce
                    }
                });
            }
        }
    });

    // Copy password to clipboard functionality
    $(document).on('click', '.dlm-password-code', function () {
        const password = $(this).text();

        // Modern clipboard API
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(password).then(function () {
                showCopyNotification('Đã sao chép mật khẩu!');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = password;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.select();

            try {
                document.execCommand('copy');
                showCopyNotification('Đã sao chép mật khẩu!');
            } catch (err) {
                console.error('Failed to copy:', err);
            }

            document.body.removeChild(textArea);
        }
    });

    function showCopyNotification(message) {
        // Create notification element
        const notification = $('<div>')
            .addClass('dlm-copy-notification')
            .text(message)
            .css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'background': '#4caf50',
                'color': 'white',
                'padding': '15px 25px',
                'border-radius': '8px',
                'box-shadow': '0 4px 12px rgba(0,0,0,0.3)',
                'z-index': '99999',
                'font-weight': 'bold',
                'animation': 'slideInRight 0.3s ease'
            });

        $('body').append(notification);

        // Remove after 3 seconds
        setTimeout(function () {
            notification.fadeOut(300, function () {
                $(this).remove();
            });
        }, 3000);
    }

    // Add animation keyframes
    if (!$('#dlm-animations').length) {
        $('head').append(`
            <style id="dlm-animations">
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            </style>
        `);
    }
});