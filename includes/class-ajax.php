<?php
/**
 * AJAX Handler
 */

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Ajax {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Links
        add_action('wp_ajax_dlm_save_link', array($this, 'save_link'));
        add_action('wp_ajax_dlm_get_link', array($this, 'get_link'));
        add_action('wp_ajax_dlm_delete_link', array($this, 'delete_link'));
        
        // Ads
        add_action('wp_ajax_dlm_save_ad', array($this, 'save_ad'));
        add_action('wp_ajax_dlm_delete_ad', array($this, 'delete_ad'));
        
        // Stats
        add_action('wp_ajax_dlm_track_download', array($this, 'track_download'));
        add_action('wp_ajax_nopriv_dlm_track_download', array($this, 'track_download'));
    }
    
    public function save_link() {
        check_ajax_referer('dlm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        $link_id = isset($_POST['link_id']) ? intval($_POST['link_id']) : 0;
        
        $data = array(
            'title' => sanitize_text_field($_POST['title']),
            'download_url' => esc_url_raw($_POST['download_url']),
            'password' => sanitize_text_field($_POST['password']),
            'countdown_time' => intval($_POST['countdown_time'])
        );
        
        $result = DLM_Database::save_link($data, $link_id);
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Lưu thành công!'));
        } else {
            wp_send_json_error('Có lỗi xảy ra');
        }
    }
    
    public function get_link() {
        check_ajax_referer('dlm_nonce', 'nonce');
        
        $link_id = intval($_POST['link_id']);
        $link = DLM_Database::get_link($link_id);
        
        if ($link) {
            wp_send_json_success($link);
        } else {
            wp_send_json_error('Link không tồn tại');
        }
    }
    
    public function delete_link() {
        check_ajax_referer('dlm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        $link_id = intval($_POST['link_id']);
        $result = DLM_Database::delete_link($link_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Xóa thành công!'));
        } else {
            wp_send_json_error('Có lỗi xảy ra');
        }
    }
    
    public function save_ad() {
        check_ajax_referer('dlm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        $ad_id = isset($_POST['ad_id']) ? intval($_POST['ad_id']) : 0;
        
        $data = array(
            'position' => sanitize_text_field($_POST['position']),
            'image_url' => esc_url_raw($_POST['image_url']),
            'link_url' => esc_url_raw($_POST['link_url']),
            'width' => sanitize_text_field($_POST['width']),
            'height' => sanitize_text_field($_POST['height']),
            'status' => sanitize_text_field($_POST['status'])
        );
        
        $result = DLM_Database::save_ad($data, $ad_id);
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Lưu thành công!'));
        } else {
            wp_send_json_error('Có lỗi xảy ra');
        }
    }
    
    public function delete_ad() {
        check_ajax_referer('dlm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        $ad_id = intval($_POST['ad_id']);
        $result = DLM_Database::delete_ad($ad_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Xóa thành công!'));
        } else {
            wp_send_json_error('Có lỗi xảy ra');
        }
    }
    
    public function track_download() {
        $link_id = intval($_POST['link_id']);
        DLM_Database::track_download($link_id);
        wp_send_json_success();
    }
}