<?php
/**
 * Admin Ads Page Template
 * * Giao diện quản lý các banner quảng cáo trong Admin.
 *
 * @package    Download Link Manager
 * @author     Đạt Nguyễn (DeeAYTee) <https://deeaytee.xyz>
 * @copyright  2026 DeeAyTee
 * @license    GPL-2.0+
 * @version    2.0.3
 */

if (!defined('ABSPATH')) exit;

if (!isset($ads)) { $ads = array(); }
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
                            <input type="url" id="ad-image" class="regular-text" placeholder="https://..." required>
                            <p class="description">Gợi ý: Header/Footer (728x90), Left/Right (160x600).</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label>Link đích:</label></th>
                        <td><input type="url" id="ad-link" class="regular-text" placeholder="https://..."></td>
                    </tr>
                    <tr>
                        <th><label>Kích thước (Rộng x Cao):</label></th>
                        <td>
                            <input type="text" id="ad-width" value="100%" placeholder="100%" class="small-text"> 
                            x 
                            <input type="text" id="ad-height" value="auto" placeholder="auto" class="small-text">
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
                <div class="notice notice-info"><p>📝 Chưa có quảng cáo nào.</p></div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Vị trí</th>
                            <th width="25%">Hình ảnh</th>
                            <th width="15%">Kích thước</th>
                            <th width="20%">Bật/Tắt nhanh</th> <th width="20%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ads as $ad): ?>
                            <tr>
                                <td><strong><?php echo $ad->id; ?></strong></td>
                                <td><code><?php echo esc_html($ad->position); ?></code></td>
                                <td>
                                    <img src="<?php echo esc_url($ad->image_url); ?>" style="max-width:120px;max-height:60px;border-radius:4px;">
                                </td>
                                <td><?php echo esc_html($ad->width); ?> × <?php echo esc_html($ad->height); ?></td>
                                
<td>
                                    <div class="toggle-wrapper">
                                        <label class="dlm-switch">
                                            <input type="checkbox" class="toggle-ad-status" 
                                                   data-id="<?php echo $ad->id; ?>" 
                                                   <?php checked($ad->status, 'active'); ?>>
                                            <span class="slider round"></span>
                                        </label>
                                        
                                        <span class="status-label" style="color: <?php echo ($ad->status === 'active') ? '#46b450' : '#999'; ?>;">
                                            <?php echo ($ad->status === 'active') ? 'Đang bật' : 'Đã tắt'; ?>
                                        </span>
                                    </div>
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
        © <?php echo date('Y'); ?> <strong>Download Link Manager</strong> | 
        Developed by <a href="https://deeaytee.xyz" target="_blank">Đạt Nguyễn (DeeAyTee)</a> | 
        Version <?php echo DLM_VERSION; ?>
    </p>
</div>