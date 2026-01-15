<?php

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Admin_UI
{
    public function __construct()
    {
        add_action('media_buttons', array($this, 'add_shortcode_button'));
        add_action('admin_footer', array($this, 'add_shortcode_popup'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_admin_scripts($hook)
    {
        // Only load on post edit pages
        if ('post.php' != $hook && 'post-new.php' != $hook) {
            return;
        }

        wp_enqueue_style(
            'dlm-admin-style',
            DLM_PLUGIN_URL . 'assets/css/admin-style.css',
            array(),
            DLM_VERSION
        );

        wp_enqueue_script(
            'dlm-admin-script',
            DLM_PLUGIN_URL . 'assets/js/admin-script.js',
            array('jquery'),
            DLM_VERSION,
            true
        );
    }

    public function add_shortcode_button()
    {
        echo '<button type="button" class="button dlm-insert-shortcode" style="padding-left: .4em;">
            <span class="dashicons dashicons-download" style="margin-top: 3px;"></span> 
            Chèn Download Link
        </button>';
    }

    public function add_shortcode_popup()
    {
        global $pagenow;
        if ('post.php' != $pagenow && 'post-new.php' != $pagenow) {
            return;
        }

        // Get all downloads
        $downloads = get_posts(array(
            'post_type' => 'dlm_download',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish'
        ));
        ?>
        <div id="dlm-shortcode-popup" style="display:none;">
            <div class="dlm-popup-overlay"></div>
            <div class="dlm-popup-content">
                <div class="dlm-popup-header">
                    <h2>
                        <span class="dashicons dashicons-download"></span>
                        Chèn Download Link
                    </h2>
                    <button type="button" class="dlm-popup-close">
                        <span class="dashicons dashicons-no-alt"></span>
                    </button>
                </div>

                <div class="dlm-popup-body">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="dlm-select-download">
                                    Chọn Download <span class="required">*</span>
                                </label>
                            </th>
                            <td>
                                <select id="dlm-select-download" class="widefat" required>
                                    <option value="">-- Chọn một download --</option>
                                    <?php foreach ($downloads as $download): ?>
                                        <option value="<?php echo $download->ID; ?>">
                                            <?php echo esc_html($download->post_title); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (empty($downloads)): ?>
                                    <p class="description" style="color: #d63638;">
                                        ⚠️ Chưa có download nào.
                                        <a href="<?php echo admin_url('post-new.php?post_type=dlm_download'); ?>" target="_blank">
                                            Tạo download mới
                                        </a>
                                    </p>
                                <?php else: ?>
                                    <p class="description">Chọn file download bạn muốn chèn vào bài viết</p>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="dlm-button-text">Văn Bản Hiển Thị</label>
                            </th>
                            <td>
                                <input type="text" id="dlm-button-text" class="widefat"
                                    placeholder="Để trống = dùng tên download">
                                <p class="description">Văn bản hiển thị trên nút/link (tùy chọn)</p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="dlm-style">Kiểu Hiển Thị</label>
                            </th>
                            <td>
                                <select id="dlm-style">
                                    <option value="button">🔘 Nút Bấm (Button)</option>
                                    <option value="box">📦 Hộp Download (Box)</option>
                                    <option value="link">🔗 Link Văn Bản (Link)</option>
                                </select>
                                <p class="description">Chọn cách hiển thị download link</p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Tùy Chọn Hiển Thị</th>
                            <td>
                                <label>
                                    <input type="checkbox" id="dlm-show-version" checked>
                                    Hiển thị phiên bản
                                </label>
                                <br>
                                <label>
                                    <input type="checkbox" id="dlm-show-count">
                                    Hiển thị số lượt tải
                                </label>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Xem Trước</th>
                            <td>
                                <div class="dlm-preview-box">
                                    <div id="dlm-shortcode-preview">
                                        <em style="color: #999;">Chọn download để xem mã shortcode...</em>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="dlm-popup-footer">
                    <button type="button" class="button button-secondary dlm-popup-close">
                        Hủy
                    </button>
                    <button type="button" class="button button-primary dlm-insert-shortcode-btn" disabled>
                        <span class="dashicons dashicons-plus-alt"></span>
                        Chèn vào Bài Viết
                    </button>
                </div>
            </div>
        </div>

        <style>
            .dlm-popup-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.7);
                z-index: 999999;
            }

            .dlm-popup-content {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                border-radius: 8px;
                box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
                z-index: 1000000;
                width: 90%;
                max-width: 600px;
                max-height: 90vh;
                display: flex;
                flex-direction: column;
            }

            .dlm-popup-header {
                padding: 20px;
                border-bottom: 1px solid #ddd;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .dlm-popup-header h2 {
                margin: 0;
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 20px;
            }

            .dlm-popup-close {
                background: none;
                border: none;
                cursor: pointer;
                padding: 5px;
                color: #999;
                transition: color 0.3s;
            }

            .dlm-popup-close:hover {
                color: #d63638;
            }

            .dlm-popup-body {
                padding: 20px;
                overflow-y: auto;
                flex: 1;
            }

            .dlm-popup-footer {
                padding: 15px 20px;
                border-top: 1px solid #ddd;
                text-align: right;
                display: flex;
                gap: 10px;
                justify-content: flex-end;
            }

            .dlm-preview-box {
                background: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 15px;
                font-family: monospace;
                font-size: 13px;
                color: #333;
                word-break: break-all;
            }

            .required {
                color: #d63638;
            }
        </style>
        <?php
    }
}