<?php

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Download_Handler
{
    public function run()
    {
        add_action('template_redirect', array($this, 'handle_download_page'));
        add_action('wp_ajax_dlm_track_download', array($this, 'ajax_track_download'));
        add_action('wp_ajax_nopriv_dlm_track_download', array($this, 'ajax_track_download'));
    }

    public function handle_download_page()
    {
        if (isset($_GET['dlm-download'])) {
            $download_id = intval($_GET['dlm-download']);
            
            if ($download_id) {
                $this->display_download_page($download_id);
            }
        }
    }

    private function display_download_page($download_id)
    {
        $download = get_post($download_id);

        if (!$download || 'dlm_download' !== $download->post_type) {
            wp_die(__('Download không tồn tại.', 'download-link-manager'));
        }

        $file_url = get_post_meta($download_id, '_dlm_file_url', true);
        $file_password = get_post_meta($download_id, '_dlm_file_password', true);
        $countdown_time = get_post_meta($download_id, '_dlm_countdown_time', true);
        $file_version = get_post_meta($download_id, '_dlm_file_version', true);

        if (!$file_url) {
            wp_die(__('File URL chưa được cấu hình.', 'download-link-manager'));
        }

        // Get ad code from settings
        $ad_code = get_option('dlm_ad_code', '');

        // Load template
        include DLM_PLUGIN_DIR . 'templates/download-page.php';
        exit;
    }

    public function ajax_track_download()
    {
        check_ajax_referer('dlm_nonce', 'nonce');

        $download_id = isset($_POST['download_id']) ? intval($_POST['download_id']) : 0;

        if (!$download_id) {
            wp_send_json_error('Invalid download ID');
        }

        // Check if this is a unique download
        if ($this->is_unique_download($download_id)) {
            $this->log_download($download_id);
            
            // Update download count
            $current_count = (int) get_post_meta($download_id, '_dlm_download_count', true);
            update_post_meta($download_id, '_dlm_download_count', $current_count + 1);
        }

        $file_url = get_post_meta($download_id, '_dlm_file_url', true);
        wp_send_json_success(array('file_url' => $file_url));
    }

    private function is_unique_download($download_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dlm_download_logs';
        $ip_address = $this->get_client_ip();
        $time_limit = get_option('dlm_unique_time_limit', 24); // hours

        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table_name 
            WHERE download_id = %d 
            AND ip_address = %s 
            AND download_date > DATE_SUB(NOW(), INTERVAL %d HOUR)
            LIMIT 1",
            $download_id,
            $ip_address,
            $time_limit
        ));

        return !$existing;
    }

    private function log_download($download_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dlm_download_logs';

        $wpdb->insert(
            $table_name,
            array(
                'download_id' => $download_id,
                'ip_address' => $this->get_client_ip(),
                'user_agent' => $this->get_user_agent(),
                'user_id' => get_current_user_id(),
                'download_date' => current_time('mysql')
            ),
            array('%d', '%s', '%s', '%d', '%s')
        );
    }

    private function get_client_ip()
    {
        $ip = '';
        
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return sanitize_text_field($ip);
    }

    private function get_user_agent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? 
               substr(sanitize_text_field($_SERVER['HTTP_USER_AGENT']), 0, 255) : '';
    }

    public static function get_download_url($post_id)
    {
        return add_query_arg('dlm-download', $post_id, home_url('/'));
    }
}