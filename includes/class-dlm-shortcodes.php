<?php

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Shortcodes
{
    public function render_download_link($atts)
    {
        $atts = shortcode_atts(
            array(
                'id' => 0,
                'text' => '',
                'style' => 'button', // button, link, box
                'show_version' => 'yes',
                'show_count' => 'no',
            ),
            $atts,
            'download_link'
        );

        $download_id = intval($atts['id']);
        if (!$download_id) {
            return '<p class="dlm-error">ID download không hợp lệ</p>';
        }

        $download_post = get_post($download_id);
        if (!$download_post || 'dlm_download' !== $download_post->post_type) {
            return '<p class="dlm-error">Download không tồn tại</p>';
        }

        $file_url = get_post_meta($download_id, '_dlm_file_url', true);
        if (!$file_url) {
            return '<p class="dlm-error">File URL chưa được cấu hình</p>';
        }

        $version = get_post_meta($download_id, '_dlm_file_version', true);
        $download_count = (int) get_post_meta($download_id, '_dlm_download_count', true);
        $download_url = DLM_Download_Handler::get_download_url($download_id);

        $button_text = !empty($atts['text']) ? esc_html($atts['text']) : esc_html($download_post->post_title);

        ob_start();

        if ($atts['style'] === 'box') {
            $this->render_box_style($button_text, $download_url, $version, $download_count, $atts);
        } elseif ($atts['style'] === 'link') {
            $this->render_link_style($button_text, $download_url, $version, $download_count, $atts);
        } else {
            $this->render_button_style($button_text, $download_url, $version, $download_count, $atts);
        }

        return ob_get_clean();
    }

    private function render_button_style($text, $url, $version, $count, $atts)
    {
        ?>
        <div class="dlm-download-wrapper">
            <a href="<?php echo esc_url($url); ?>" class="dlm-button dlm-button-primary" target="_blank">
                <span class="dlm-icon">📥</span>
                <span class="dlm-text"><?php echo $text; ?></span>
                <?php if ($atts['show_version'] === 'yes' && $version): ?>
                    <span class="dlm-version">(v<?php echo esc_html($version); ?>)</span>
                <?php endif; ?>
            </a>
            <?php if ($atts['show_count'] === 'yes'): ?>
                <span class="dlm-count">
                    <?php echo number_format($count); ?> lượt tải
                </span>
            <?php endif; ?>
        </div>
        <?php
    }

    private function render_link_style($text, $url, $version, $count, $atts)
    {
        ?>
        <a href="<?php echo esc_url($url); ?>" class="dlm-link" target="_blank">
            <?php echo $text; ?>
            <?php if ($atts['show_version'] === 'yes' && $version): ?>
                <span class="dlm-version">(v<?php echo esc_html($version); ?>)</span>
            <?php endif; ?>
            <?php if ($atts['show_count'] === 'yes'): ?>
                <span class="dlm-count"> - <?php echo number_format($count); ?> lượt tải</span>
            <?php endif; ?>
        </a>
        <?php
    }

    private function render_box_style($text, $url, $version, $count, $atts)
    {
        ?>
        <div class="dlm-download-box">
            <div class="dlm-box-icon">📦</div>
            <div class="dlm-box-content">
                <h3 class="dlm-box-title"><?php echo $text; ?></h3>
                <?php if ($atts['show_version'] === 'yes' && $version): ?>
                    <p class="dlm-box-version">Phiên bản: <?php echo esc_html($version); ?></p>
                <?php endif; ?>
                <?php if ($atts['show_count'] === 'yes'): ?>
                    <p class="dlm-box-stats">
                        <span class="dlm-stats-icon">📊</span>
                        <?php echo number_format($count); ?> lượt tải
                    </p>
                <?php endif; ?>
                <a href="<?php echo esc_url($url); ?>" class="dlm-box-button" target="_blank">
                    Tải xuống ngay
                </a>
            </div>
        </div>
        <?php
    }
}