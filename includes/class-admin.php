<?php
/**
 * Admin Pages Handler
 */

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Download Links',
            'Download Links',
            'manage_options',
            'download-link-manager',
            array($this, 'page_links'),
            'dashicons-download',
            30
        );
        
        add_submenu_page(
            'download-link-manager',
            'Quản lý Quảng cáo',
            'Quảng cáo',
            'manage_options',
            'dlm-ads',
            array($this, 'page_ads')
        );
        
        add_submenu_page(
            'download-link-manager',
            'Thống kê',
            'Thống kê',
            'manage_options',
            'dlm-stats',
            array($this, 'page_stats')
        );
    }
    
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'download-link-manager') === false && strpos($hook, 'dlm-') === false) {
            return;
        }
        
        wp_enqueue_script('dlm-admin-js', DLM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), DLM_VERSION, true);
        wp_localize_script('dlm-admin-js', 'dlmData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dlm_nonce')
        ));
        
        wp_enqueue_style('dlm-admin-css', DLM_PLUGIN_URL . 'assets/css/admin.css', array(), DLM_VERSION);
    }
    
    public function page_links() {
        $links = DLM_Database::get_links();
        include DLM_PLUGIN_DIR . 'templates/admin-links.php';
    }
    
    public function page_ads() {
        $ads = DLM_Database::get_ads();
        include DLM_PLUGIN_DIR . 'templates/admin-ads.php';
    }
    
    public function page_stats() {
        $stats = DLM_Database::get_stats();
        include DLM_PLUGIN_DIR . 'templates/admin-stats.php';
    }
}