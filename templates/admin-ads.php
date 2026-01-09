<?php
/**
 * Admin Stats Page Template
 */
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1>📊 Thống Kê Lượt Tải</h1>
    
    <?php if (empty($stats)): ?>
        <div class="notice notice-info" style="margin-top:20px;">
            <p>📝 Chưa có dữ liệu thống kê. Tạo link và chia sẻ để bắt đầu theo dõi!</p>
        </div>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped" style="margin-top:20px;">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="40%">Tiêu đề</th>
                    <th width="26%">Tổng lượt tải</th>
                    <th width="26%">Lượt tải hôm nay</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats as $stat): ?>
                    <tr>
                        <td><strong><?php echo $stat->id; ?></strong></td>
                        <td><?php echo esc_html($stat->title); ?></td>
                        <td>
                            <strong style="color:#2271b1;font-size:18px;">
                                <?php echo number_format($stat->total_clicks); ?>
                            </strong>
                        </td>
                        <td>
                            <strong style="color:#46b450;font-size:18px;">
                                <?php echo number_format($stat->today_clicks); ?>
                            </strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="background:#f0f0f1;font-weight:bold;">
                    <td colspan="2" style="text-align:right;padding-right:20px;">TỔNG CỘNG:</td>
                    <td>
                        <strong style="color:#2271b1;font-size:18px;">
                            <?php echo number_format(array_sum(array_column($stats, 'total_clicks'))); ?>
                        </strong>
                    </td>
                    <td>
                        <strong style="color:#46b450;font-size:18px;">
                            <?php echo number_format(array_sum(array_column($stats, 'today_clicks'))); ?>
                        </strong>
                    </td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
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