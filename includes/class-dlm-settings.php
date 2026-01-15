<?php

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Settings
{
    public function __construct()
    {
        // Settings are registered in register_settings() via admin_init
    }

    public function add_settings_page()
    {
        add_submenu_page(
            'edit.php?post_type=dlm_download',
            'Settings',
            'Settings',
            'manage_options',
            'dlm-settings',
            array($this, 'render_settings_page')
        );
    }

    public function register_settings()
    {
        register_setting('dlm_options_group', 'dlm_ad_code');
        register_setting('dlm_options_group', 'dlm_unique_time_limit');
    }

    public function render_settings_page()
    {
        ?>
        <div class="wrap dlm-settings-wrap">
            <h1>⚙️ Cài Đặt Download Link Manager Pro</h1>

            <div class="dlm-settings-container">
                <div class="dlm-main-content">
                    <form method="post" action="options.php">
                        <?php settings_fields('dlm_options_group'); ?>
                        <?php do_settings_sections('dlm_options_group'); ?>

                        <div class="dlm-card">
                            <h2>Cấu Hình Chung</h2>
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row">Mã Quảng Cáo (HTML)</th>
                                    <td>
                                        <textarea name="dlm_ad_code" rows="5"
                                            class="large-text code"><?php echo esc_textarea(get_option('dlm_ad_code')); ?></textarea>
                                        <p class="description">Mã này sẽ hiển thị trên trang download (trên nút tải về).</p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">Thời Gian Tracking (Giờ)</th>
                                    <td>
                                        <input type="number" name="dlm_unique_time_limit"
                                            value="<?php echo esc_attr(get_option('dlm_unique_time_limit', 24)); ?>"
                                            class="small-text">
                                        <p class="description">Số giờ để tính là 1 lượt tải duy nhất (unique download) từ 1 IP.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <?php submit_button(); ?>
                        </div>
                    </form>
                </div>

                <div class="dlm-sidebar">
                    <div class="dlm-card dlm-about-card">
                        <h3>👨‍💻 Thông tin Tác Giả</h3>
                        <div class="dlm-author-info">
                            <img src="https://secure.gravatar.com/avatar/generic?s=64&d=mm" alt="DeeAyTee" class="dlm-avatar">
                            <div class="dlm-author-text">
                                <strong>Dat Nguyen (DeeAyTee)</strong>
                                <p>Fullstack WordPress Developer</p>
                                <a href="https://deeaytee.xyz" target="_blank">🌐 Website</a> |
                                <a href="https://github.com/NguyenDucDat1987" target="_blank">🐙 GitHub</a>
                            </div>
                        </div>
                        <hr>
                        <h4>☕ Donate / Ủng Hộ</h4>
                        <p>Nếu thấy plugin này hữu ích, bạn có thể mời mình một ly cà phê nhé!</p>
                        <!-- <a href="https://www.buymeacoffee.com/deeaytee" target="_blank"
                            class="button button-primary dlm-donate-btn">
                            <span class="dashicons dashicons-heart"></span> Buy me a coffee
                        </a> -->
                        <div class="dlm-bank-info">
                            <code>MB Bank: 0903271018 (Nguyen Duc Dat)</code>
                        </div>
                    </div>

                    <div class="dlm-card">
                        <h3>📞 Hỗ Trợ</h3>
                        <p>Gặp lỗi hoặc cần tính năng mới?</p>
                        <ul>
                            <li><a href="mailto:tekachi.nguyen@gmail.com">📧 Email: tekachi.nguyen@gmail.com</a></li>
                            <li><a href="#" target="_blank">💬 Facebook Messenger</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .dlm-settings-container {
                display: flex;
                gap: 20px;
                margin-top: 20px;
            }

            .dlm-main-content {
                flex: 2;
            }

            .dlm-sidebar {
                flex: 1;
                max-width: 300px;
            }

            .dlm-card {
                background: white;
                padding: 20px;
                border: 1px solid #ccd0d4;
                border-radius: 4px;
                box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
                margin-bottom: 20px;
            }

            .dlm-card h2,
            .dlm-card h3 {
                margin-top: 0;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }

            .dlm-author-info {
                display: flex;
                gap: 10px;
                align-items: center;
                margin-bottom: 15px;
            }

            .dlm-avatar {
                border-radius: 50%;
                width: 50px;
                height: 50px;
                background: #eee;
            }

            .dlm-donate-btn {
                display: block !important;
                text-align: center;
                width: 100%;
                margin-bottom: 15px !important;
                display: flex !important;
                justify-content: center;
                align-items: center;
                gap: 5px;
            }

            .dlm-bank-info {
                background: #f0f6fc;
                padding: 10px;
                border-radius: 4px;
                font-size: 12px;
            }

            .dlm-bank-info code {
                display: block;
                margin-top: 5px;
                background: white;
            }

            @media (max-width: 782px) {
                .dlm-settings-container {
                    flex-direction: column;
                }

                .dlm-sidebar {
                    max-width: 100%;
                }
            }
        </style>
        <?php
    }
}