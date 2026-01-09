<?php
/**
 * Admin Ads Page Template
 */
if (!defined('ABSPATH')) exit;

// Đảm bảo $ads được định nghĩa
if (!isset($ads)) {
    $ads = array();
}
?>

<div class="wrap">
    <h1>🎯 Quản Lý Quảng Cáo</h1>
    
    <div class="dlm-admin-container">
        <div class="dlm-form-section">
            <h2>Thêm/Sửa Quảng Cáo</h2>
            <form id="dlm-ad-form">
                <input type="hidden" id="ad-id" value="">
                
                <table class="form-table">
                    <tr>
                        <th><label>Vị trí: <span style="color:red;">*</span></label></th>
                        <td>
                            <select id="ad-position" class="regular-text" required>
                                <option value="header">📍 Header (Trên cùng)</option>
                                <option value="footer">📍 Footer (Dưới cùng)</option>
                                <option value="left">📍 Left (Bên trái - Sticky)</option>
                                <option value="right">📍 Right (Bên phải - Sticky)</option>
                                <option value="before_countdown">📍 Trước đồng hồ đếm ngược</option>
                                <option value="after_countdown">📍 Sau đồng hồ đếm ngược</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label>URL Hình ảnh: <span style="color:red;">*</span></label></th>
                        <td>
                            <input type="url" id="ad-image" class="regular-text" placeholder="https://i.imgur.com/example.jpg" required>
                            <p class="description">
                                Dán link hình ảnh từ: Imgur, ImgBB, Google Drive, Dropbox, v.v.<br>
                                <strong>Gợi ý kích thước:</strong><br>
                                • Header/Footer: 728x90px hoặc 970x90px<br>
                                • Left/Right: 160x600px hoặc 300x600px<br>
                                • Before/After Countdown: 300x250px hoặc 336x280px
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><label>Link đích (tùy chọn):</label></th>
                        <td>
                            <input type="url" id="ad-link" class="regular-text" placeholder="https://example.com">
                            <p class="description">Link khi click vào quảng cáo (để trống nếu không cần)</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label>Chiều rộng:</label></th>
                        <td>
                            <input type="text" id="ad-width" value="100%" placeholder="100%, 300px, 728px...">
                        </td>
                    </tr>
                    <tr>
                        <th><label>Chiều cao:</label></th>
                        <td>
                            <input type="text" id="ad-height" value="auto" placeholder="auto, 90px, 250px...">
                        </td>
                    </tr>
                    <tr>
                        <th><label>Trạng thái:</label></th>
                        <td>
                            <select id="ad-status">
                                <option value="active">✅ Kích hoạt</option>
                                <option value="inactive">❌ Tắt</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary button-large">💾 Lưu Quảng Cáo</button>
                    <button type="button" id="cancel-ad-edit" class="button button-large" style="display:none;">❌ Hủy</button>
                </p>
            </form>
        </div>
        
        <div class="dlm-list-section">
            <h2>Danh Sách Quảng Cáo</h2>
            
            <?php if (empty($ads)): ?>
                <div class="notice notice-info">
                    <p>📝 Chưa có quảng cáo nào. Hãy thêm quảng cáo đầu tiên!</p>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Vị trí</th>
                            <th width="30%">Hình ảnh</th>
                            <th width="15%">Kích thước</th>
                            <th width="15%">Trạng thái</th>
                            <th width="20%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ads as $ad): ?>
                            <tr>
                                <td><strong><?php echo $ad->id; ?></strong></td>
                                <td><code><?php echo esc_html($ad->position); ?></code></td>
                                <td>
                                    <img src="<?php echo esc_url($ad->image_url); ?>" 
                                         style="max-width:150px;max-height:80px;border:1px solid #ddd;border-radius:4px;"
                                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'80\'%3E%3Crect fill=\'%23f0f0f1\' width=\'150\' height=\'80\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23999\'%3ELỗi ảnh%3C/text%3E%3C/svg%3E'">
                                </td>
                                <td><?php echo esc_html($ad->width); ?> × <?php echo esc_html($ad->height); ?></td>
                                <td>
                                    <?php if ($ad->status === 'active'): ?>
                                        <span style="color:#46b450;font-weight:bold;">✅ Đang bật</span>
                                    <?php else: ?>
                                        <span style="color:#999;">❌ Đã tắt</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="button button-small edit-ad" 
                                            data-id="<?php echo $ad->id; ?>"
                                            data-position="<?php echo esc_attr($ad->position); ?>"
                                            data-image="<?php echo esc_attr($ad->image_url); ?>"
                                            data-link="<?php echo esc_attr($ad->link_url); ?>"
                                            data-width="<?php echo esc_attr($ad->width); ?>"
                                            data-height="<?php echo esc_attr($ad->height); ?>"
                                            data-status="<?php echo esc_attr($ad->status); ?>">
                                        ✏️ Sửa
                                    </button>
                                    <button class="button button-small delete-ad" data-id="<?php echo $ad->id; ?>" style="color:#d63638;">🗑️ Xóa</button>
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
        © <?php echo date('Y'); ?> <strong>Download Link Manager Pro</strong> | 
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