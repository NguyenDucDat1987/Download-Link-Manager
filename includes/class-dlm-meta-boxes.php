<?php

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Meta_Boxes
{
    public function add_meta_boxes()
    {
        add_meta_box(
            'dlm_file_info',
            __('Thông Tin File', 'download-link-manager'),
            array($this, 'render_meta_box'),
            'dlm_download',
            'normal',
            'high'
        );

        add_meta_box(
            'dlm_download_stats',
            __('Thống Kê Download', 'download-link-manager'),
            array($this, 'render_stats_box'),
            'dlm_download',
            'side',
            'default'
        );
    }

    public function render_meta_box($post)
    {
        $file_url = get_post_meta($post->ID, '_dlm_file_url', true);
        $file_version = get_post_meta($post->ID, '_dlm_file_version', true);
        $file_password = get_post_meta($post->ID, '_dlm_file_password', true);
        $countdown_time = get_post_meta($post->ID, '_dlm_countdown_time', true);
        $countdown_time = $countdown_time ? $countdown_time : '10';

        wp_nonce_field('dlm_save_meta_box_data', 'dlm_meta_box_nonce');
        ?>
        <table class="form-table">
            <tr>
                <th><label for="dlm_file_url"><?php _e('URL File', 'download-link-manager'); ?> *</label></th>
                <td>
                    <input type="url" id="dlm_file_url" name="dlm_file_url" value="<?php echo esc_attr($file_url); ?>"
                        class="widefat" placeholder="https://example.com/file.zip" required>
                    <p class="description">Nhập URL trực tiếp đến file cần chia sẻ</p>
                </td>
            </tr>
            <tr>
                <th><label for="dlm_file_version"><?php _e('Phiên Bản', 'download-link-manager'); ?></label></th>
                <td>
                    <input type="text" id="dlm_file_version" name="dlm_file_version"
                        value="<?php echo esc_attr($file_version); ?>" placeholder="1.0.0">
                    <p class="description">Phiên bản của file (tùy chọn)</p>
                </td>
            </tr>
            <tr>
                <th><label for="dlm_file_password"><?php _e('Mật Khẩu Giải Nén', 'download-link-manager'); ?></label></th>
                <td>
                    <input type="text" id="dlm_file_password" name="dlm_file_password"
                        value="<?php echo esc_attr($file_password); ?>" placeholder="Nhập mật khẩu nếu file có nén">
                    <p class="description">Mật khẩu sẽ hiển thị sau khi đếm ngược kết thúc</p>
                </td>
            </tr>
            <tr>
                <th><label for="dlm_countdown_time"><?php _e('Thời Gian Đếm Ngược', 'download-link-manager'); ?></label></th>
                <td>
                    <select id="dlm_countdown_time" name="dlm_countdown_time">
                        <option value="0" <?php selected($countdown_time, '0'); ?>>Không đếm ngược</option>
                        <option value="5" <?php selected($countdown_time, '5'); ?>>5 giây</option>
                        <option value="10" <?php selected($countdown_time, '10'); ?>>10 giây</option>
                        <option value="15" <?php selected($countdown_time, '15'); ?>>15 giây</option>
                        <option value="20" <?php selected($countdown_time, '20'); ?>>20 giây</option>
                        <option value="30" <?php selected($countdown_time, '30'); ?>>30 giây</option>
                        <option value="60" <?php selected($countdown_time, '60'); ?>>60 giây</option>
                    </select>
                    <p class="description">Thời gian người dùng phải đợi trước khi hiện link download</p>
                </td>
            </tr>
        </table>

        <?php if ($post->post_status === 'publish'): ?>
            <div class="dlm-shortcode-display">
                <h4>📋 Shortcode để chèn vào bài viết:</h4>
                <div class="dlm-shortcode-code">
                    <code>[download_link id="<?php echo $post->ID; ?>"]</code>
                    <button type="button" class="dlm-shortcode-copy-btn">Copy</button>
                </div>
                <p class="description" style="margin-top: 10px;">
                    💡 <strong>Mẹo:</strong> Khi soạn bài viết, click nút <strong>"Chèn Download Link"</strong>
                    ngay trên khung soạn thảo để chèn shortcode một cách dễ dàng!
                </p>
            </div>
        <?php endif; ?>
    <?php
    }

    public function render_stats_box($post)
    {
        $download_count = (int) get_post_meta($post->ID, '_dlm_download_count', true);
        $unique_downloads = $this->get_unique_download_count($post->ID);

        ?>
        <div class="dlm-stats">
            <p><strong>Tổng lượt tải:</strong> <?php echo number_format($download_count); ?></p>
            <p><strong>Lượt tải duy nhất:</strong> <?php echo number_format($unique_downloads); ?></p>
            <p><a href="<?php echo admin_url('edit.php?post_type=dlm_download&page=dlm-stats&download_id=' . $post->ID); ?>">
                    Xem chi tiết thống kê
                </a></p>
        </div>
        <?php
    }

    private function get_unique_download_count($download_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dlm_download_logs';

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT ip_address) FROM $table_name WHERE download_id = %d",
            $download_id
        ));

        return $count ? $count : 0;
    }

    public function save_meta_box_data($post_id)
    {
        if (
            !isset($_POST['dlm_meta_box_nonce']) ||
            !wp_verify_nonce($_POST['dlm_meta_box_nonce'], 'dlm_save_meta_box_data')
        ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save File URL
        if (isset($_POST['dlm_file_url'])) {
            update_post_meta($post_id, '_dlm_file_url', esc_url_raw($_POST['dlm_file_url']));
        }

        // Save Version
        if (isset($_POST['dlm_file_version'])) {
            update_post_meta($post_id, '_dlm_file_version', sanitize_text_field($_POST['dlm_file_version']));
        }

        // Save Password
        if (isset($_POST['dlm_file_password'])) {
            update_post_meta($post_id, '_dlm_file_password', sanitize_text_field($_POST['dlm_file_password']));
        }

        // Save Countdown Time
        if (isset($_POST['dlm_countdown_time'])) {
            update_post_meta($post_id, '_dlm_countdown_time', absint($_POST['dlm_countdown_time']));
        }
    }
}