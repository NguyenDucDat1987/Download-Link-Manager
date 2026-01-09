jQuery(document).ready(function($) {
    
    // ============= LINKS PAGE =============
    
    // Submit link form
    $('#dlm-link-form').on('submit', function(e) {
        e.preventDefault();
        
        const button = $(this).find('button[type="submit"]');
        const originalText = button.html();
        button.html('⏳ Đang lưu...').prop('disabled', true);
        
        $.ajax({
            url: dlmData.ajaxurl,
            type: 'POST',
            data: {
                action: 'dlm_save_link',
                nonce: dlmData.nonce,
                link_id: $('#link-id').val(),
                title: $('#link-title').val(),
                download_url: $('#download-url').val(),
                password: $('#password').val(),
                countdown_time: $('#countdown-time').val()
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.data.message);
                    location.reload();
                } else {
                    alert('❌ ' + response.data);
                    button.html(originalText).prop('disabled', false);
                }
            },
            error: function() {
                alert('❌ Có lỗi xảy ra. Vui lòng thử lại!');
                button.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Edit link
    $('.edit-link').on('click', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: dlmData.ajaxurl,
            type: 'POST',
            data: {
                action: 'dlm_get_link',
                nonce: dlmData.nonce,
                link_id: id
            },
            success: function(response) {
                if (response.success) {
                    const link = response.data;
                    $('#link-id').val(link.id);
                    $('#link-title').val(link.title);
                    $('#download-url').val(link.download_url);
                    $('#password').val(link.password);
                    $('#countdown-time').val(link.countdown_time);
                    $('#cancel-edit').show();
                    $('html, body').animate({scrollTop: 0}, 300);
                }
            }
        });
    });
    
    // Cancel edit
    $('#cancel-edit').on('click', function() {
        $('#dlm-link-form')[0].reset();
        $('#link-id').val('');
        $(this).hide();
    });
    
    // Delete link
    $('.delete-link').on('click', function() {
        if (!confirm('⚠️ Bạn có chắc muốn xóa link này?\n\nDữ liệu thống kê cũng sẽ bị xóa.')) {
            return;
        }
        
        const id = $(this).data('id');
        const button = $(this);
        const originalText = button.html();
        button.html('⏳').prop('disabled', true);
        
        $.ajax({
            url: dlmData.ajaxurl,
            type: 'POST',
            data: {
                action: 'dlm_delete_link',
                nonce: dlmData.nonce,
                link_id: id
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.data.message);
                    location.reload();
                } else {
                    alert('❌ ' + response.data);
                    button.html(originalText).prop('disabled', false);
                }
            }
        });
    });
    
    // Copy shortcode
    $('.copy-shortcode').on('click', function() {
        const shortcode = $(this).data('shortcode');
        const button = $(this);
        
        navigator.clipboard.writeText(shortcode).then(function() {
            const originalText = button.html();
            button.html('✅ Đã copy!');
            setTimeout(function() {
                button.html(originalText);
            }, 2000);
        });
    });
    
    // ============= ADS PAGE =============
    
    // Submit ad form
    $('#dlm-ad-form').on('submit', function(e) {
        e.preventDefault();
        
        const button = $(this).find('button[type="submit"]');
        const originalText = button.html();
        button.html('⏳ Đang lưu...').prop('disabled', true);
        
        $.ajax({
            url: dlmData.ajaxurl,
            type: 'POST',
            data: {
                action: 'dlm_save_ad',
                nonce: dlmData.nonce,
                ad_id: $('#ad-id').val(),
                position: $('#ad-position').val(),
                image_url: $('#ad-image').val(),
                link_url: $('#ad-link').val(),
                width: $('#ad-width').val(),
                height: $('#ad-height').val(),
                status: $('#ad-status').val()
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.data.message);
                    location.reload();
                } else {
                    alert('❌ ' + response.data);
                    button.html(originalText).prop('disabled', false);
                }
            },
            error: function() {
                alert('❌ Có lỗi xảy ra. Vui lòng thử lại!');
                button.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Edit ad
    $('.edit-ad').on('click', function() {
        const button = $(this);
        $('#ad-id').val(button.data('id'));
        $('#ad-position').val(button.data('position'));
        $('#ad-image').val(button.data('image'));
        $('#ad-link').val(button.data('link'));
        $('#ad-width').val(button.data('width'));
        $('#ad-height').val(button.data('height'));
        $('#ad-status').val(button.data('status'));
        $('#cancel-ad-edit').show();
        $('html, body').animate({scrollTop: 0}, 300);
    });
    
    // Cancel edit ad
    $('#cancel-ad-edit').on('click', function() {
        $('#dlm-ad-form')[0].reset();
        $('#ad-id').val('');
        $(this).hide();
    });
    
    // Delete ad
    $('.delete-ad').on('click', function() {
        if (!confirm('⚠️ Bạn có chắc muốn xóa quảng cáo này?')) {
            return;
        }
        
        const id = $(this).data('id');
        const button = $(this);
        const originalText = button.html();
        button.html('⏳').prop('disabled', true);
        
        $.ajax({
            url: dlmData.ajaxurl,
            type: 'POST',
            data: {
                action: 'dlm_delete_ad',
                nonce: dlmData.nonce,
                ad_id: id
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.data.message);
                    location.reload();
                } else {
                    alert('❌ ' + response.data);
                    button.html(originalText).prop('disabled', false);
                }
            }
        });
    });
});