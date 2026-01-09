<?php
/**
 * Admin Links Page Template
 */
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1>📥 Quản Lý Link Tải Về</h1>
    
    <div class="dlm-admin-container">
        <div class="dlm-form-section">
            <h2>Thêm/Sửa Link</h2>
            <form id="dlm-link-form">
                <input type="hidden" id="link-id" value="">
                
                <table class="form-table">
                    <tr>
                        <th><label for="link-title">Tiêu đề: <span style="color:red;">*</span></label></th>
                        <td><input type="text" id="link-title" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="download-url">URL Tải về: <span style="color:red;">*</span></label></th>
                        <td>
                            <input type="url" id="download-url" class="regular-text" required>
                            <p class="description">Link trực tiếp đến file cần tải</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="password">Mật khẩu giải nén:</label></th>
                        <td>
                            <input type="text" id="password" class="regular-text">
                            <p class="description">Để trống nếu không cần mật khẩu</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="countdown-time">Thời gian đếm ngược:</label></th>
                        <td>
                            <input type="number" id="countdown-time" value="10" min="1" max="300" style="width:100px;"> giây
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary button-large">💾 Lưu Link</button>
                    <button type="button" id="cancel-edit" class="button button-large" style="display:none;">❌ Hủy</button>
                </p>
            </form>
        </div>
        
        <div class="dlm-list-section">
            <h2>Danh Sách Link</h2>
            
            <?php if (empty($links)): ?>
                <div class="notice notice-info">
                    <p>📝 Chưa có link nào. Hãy tạo link đầu tiên!</p>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Tiêu đề</th>
                            <th width="25%">Shortcode</th>
                            <th width="12%">Mật khẩu</th>
                            <th width="10%">Thời gian</th>
                            <th width="10%">Lượt tải</th>
                            <th width="18%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($links as $link): ?>
                            <tr>
                                <td><strong><?php echo $link->id; ?></strong></td>
                                <td><?php echo esc_html($link->title); ?></td>
                                <td>
                                    <code style="background:#f0f0f1;padding:4px 8px;border-radius:4px;font-size:12px;">[download_link id="<?php echo $link->id; ?>"]</code>
                                    <button class="button button-small copy-shortcode" data-shortcode='[download_link id="<?php echo $link->id; ?>"]' style="margin-left:5px;">📋 Copy</button>
                                </td>
                                <td>
                                    <?php if ($link->password): ?>
                                        <span style="color:#46b450;">🔐 Có</span>
                                    <?php else: ?>
                                        <span style="color:#999;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $link->countdown_time; ?>s</td>
                                <td><strong style="color:#2271b1;"><?php echo number_format($link->total_clicks); ?></strong></td>
                                <td>
                                    <button class="button button-small edit-link" data-id="<?php echo $link->id; ?>">✏️ Sửa</button>
                                    <button class="button button-small delete-link" data-id="<?php echo $link->id; ?>" style="color:#d63638;">🗑️ Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="dlm-copyright-footer">
    <p>
        © <?php echo date('Y'); ?> <strong>Download Link Manager</strong> | 
        Developed by <a href="https://deeaytee.xyz" target="_blank">Đạt Nguyễn (DeeAyTee)</a> | 
        Version <?php echo DLM_VERSION; ?>
    </p>
</div>

<style>
.dlm-copyright-footer {
    background: #f0f0f1;
    padding: 20px;
    text-align: center;
    margin-top: 30px;
    border-top: 3px solid #2271b1;
    border-radius: 4px;
}
.dlm-copyright-footer p {
    margin: 0;
    color: #50575e;
    font-size: 13px;
}
.dlm-copyright-footer a {
    color: #2271b1;
    text-decoration: none;
    font-weight: 600;
}
.dlm-copyright-footer a:hover {
    color: #135e96;
    text-decoration: underline;
}
</style>