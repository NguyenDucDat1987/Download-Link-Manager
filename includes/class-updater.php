<?php
/*
Plugin Name: Download Link Manager
Plugin URI: https://deeaytee.xyz
Description: Quản lý link tải về với trang đếm ngược và hệ thống quảng cáo.
Version: 2.0.3
Author: Đạt Nguyễn (DeeAyTee)
Author URI: https://deeaytee.xyz
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Updater {
    
    private static $instance = null;
    private $plugin_slug;
    private $plugin_basename;
    private $version;
    private $update_url;
    private $cache_key;
    private $cache_allowed;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->plugin_slug = 'download-link-manager';
        $this->plugin_basename = plugin_basename(DLM_PLUGIN_DIR . 'download-link-manager.php');
        $this->version = DLM_VERSION;
        
        // Chọn 1 trong 2 phương thức:
        
        // PHƯƠNG THỨC 1: Từ GitHub (Miễn phí, dễ dùng)
        $this->update_url = 'https://api.github.com/repos/NguyenDucDat1987/Download-Link-Manager/releases/latest';
        $this->cache_key = 'dlm_update_new_v1_' . $this->plugin_slug;
        $this->cache_allowed = true;
        
        // Hooks
        add_filter('plugins_api', array($this, 'plugin_info'), 20, 3);
        add_filter('site_transient_update_plugins', array($this, 'check_update'));
        add_action('upgrader_process_complete', array($this, 'purge_cache'), 10, 2);
        
        // Thêm link "Xem chi tiết" trong trang plugins
        add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);
    }
    
    /**
     * Lấy thông tin update từ server
     */
    private function request_update_info() {
        // Check cache
        if ($this->cache_allowed) {
            $cache = get_transient($this->cache_key);
            if (false !== $cache) {
                return $cache;
            }
        }
        
        // Request from server
        $response = wp_remote_get(
            $this->update_url,
            array(
                'timeout' => 15,
                'headers' => array(
                    'Accept' => 'application/json'
                )
            )
        );
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (empty($data)) {
            return false;
        }
        
        // Nếu dùng GitHub, chuyển đổi format
        if (strpos($this->update_url, 'github.com') !== false) {
            $data = $this->parse_github_response($data);
        }
        
        // Cache 12 giờ
        if ($this->cache_allowed && !empty($data)) {
            set_transient($this->cache_key, $data, 12 * HOUR_IN_SECONDS);
        }
        
        return $data;
    }
    
    /**
     * Chuyển đổi response từ GitHub sang format chuẩn
     */
    private function parse_github_response($response) {
        if (empty($response['tag_name'])) {
            return false;
        }
        
        $version = str_replace('v', '', $response['tag_name']);
        $download_url = '';
        
        // Tìm file .zip trong assets
        if (!empty($response['assets'])) {
            foreach ($response['assets'] as $asset) {
                if (strpos($asset['name'], '.zip') !== false) {
                    $download_url = $asset['browser_download_url'];
                    break;
                }
            }
        }
        
        // Nếu không có zip, dùng zipball_url
        if (empty($download_url) && !empty($response['zipball_url'])) {
            $download_url = $response['zipball_url'];
        }
        
        return array(
            'version' => $version,
            'download_url' => $download_url,
            'requires' => '5.0',
            'tested' => '6.4',
            'requires_php' => '7.0',
            'last_updated' => $response['published_at'],
            'changelog' => $response['body'],
            'author' => !empty($response['author']['login']) ? $response['author']['login'] : 'Developer',
            'homepage' => $response['html_url']
        );
    }
    
    /**
     * Check for updates
     */
    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        $update_info = $this->request_update_info();
        
        if (!$update_info || empty($update_info['version'])) {
            return $transient;
        }
        
        // So sánh version
        if (version_compare($this->version, $update_info['version'], '<')) {
            $plugin = array(
                'slug' => $this->plugin_slug,
                'plugin' => $this->plugin_basename,
                'new_version' => $update_info['version'],
                'url' => !empty($update_info['homepage']) ? $update_info['homepage'] : '',
                'package' => $update_info['download_url'],
                'tested' => !empty($update_info['tested']) ? $update_info['tested'] : '',
                'requires_php' => !empty($update_info['requires_php']) ? $update_info['requires_php'] : '',
            );
            
            $transient->response[$this->plugin_basename] = (object) $plugin;
        }
        
        return $transient;
    }
    
    /**
     * Plugin information popup
     */
    public function plugin_info($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return $result;
        }
        
        if ($args->slug !== $this->plugin_slug) {
            return $result;
        }
        
        $update_info = $this->request_update_info();
        
        if (!$update_info) {
            return $result;
        }
        
$plugin_info = array(
            'name' => 'Download Link Manager', // Tên plugin (bỏ chữ Pro nếu không phải bản trả phí)
            'slug' => $this->plugin_slug,
            'version' => $update_info['version'],
            
            // Sửa link website thành GitHub profile của bạn
            'author' => '<a href="https://deeaytee.xyz">' . (!empty($update_info['author']) ? $update_info['author'] : 'DeeAyTee') . '</a>',
            
            // Sửa trang chủ thành link Repository
            'homepage' => !empty($update_info['homepage']) ? $update_info['homepage'] : 'https://github.com/NguyenDucDat1987/Download-Link-Manager',
            
            // Cập nhật các thông số kỹ thuật cho hợp thời (ví dụ)
            'requires' => !empty($update_info['requires']) ? $update_info['requires'] : '6.0', // Yêu cầu WP 6.0 trở lên
            'tested' => !empty($update_info['tested']) ? $update_info['tested'] : '6.4',
            'requires_php' => !empty($update_info['requires_php']) ? $update_info['requires_php'] : '7.4', // Nên để PHP 7.4 hoặc 8.0
            
            'last_updated' => !empty($update_info['last_updated']) ? $update_info['last_updated'] : date('Y-m-d'),
            
            'sections' => array(
                'description' => 'Quản lý link tải về với trang đếm ngược, quảng cáo và thống kê chi tiết.',
                // Phần Changelog này sẽ lấy nội dung bạn viết trong GitHub Release
                'changelog' => !empty($update_info['changelog']) ? nl2br($update_info['changelog']) : 'Xem chi tiết trên trang chủ plugin.' 
            ),
            
            'download_link' => $update_info['download_url']
        );
        
        return (object) $plugin_info;
    }
    
    /**
     * Xóa cache sau khi update
     */
    public function purge_cache($upgrader, $options) {
        if ($options['action'] === 'update' && $options['type'] === 'plugin') {
            delete_transient($this->cache_key);
        }
    }
    
    /**
     * Thêm link "Xem chi tiết cập nhật"
     */
    public function plugin_row_meta($links, $file) {
        if ($file === $this->plugin_basename) {
            $update_info = $this->request_update_info();
            if ($update_info && version_compare($this->version, $update_info['version'], '<')) {
                $links[] = '<strong style="color:#d63638;">📢 Phiên bản mới: ' . $update_info['version'] . '</strong>';
            }
        }
        return $links;
    }
}
