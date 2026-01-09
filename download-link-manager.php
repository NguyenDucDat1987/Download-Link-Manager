<?php
/**
 * Plugin Name: Download Link Manager
 * Plugin URI: https://deeaytee.xyz
 * Description: Quản lý link tải về với trang đếm ngược, quảng cáo và thống kê
 * Version: 2.0.3
 * Author: Đạt Nguyễn (DeeAyTee)
 * Author URI: https://deeaytee.xyz
 * Text Domain: Download-Link-Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('DLM_VERSION', '2.0.1');
define('DLM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DLM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once DLM_PLUGIN_DIR . 'includes/class-database.php';
require_once DLM_PLUGIN_DIR . 'includes/class-admin.php';
require_once DLM_PLUGIN_DIR . 'includes/class-shortcode.php';
require_once DLM_PLUGIN_DIR . 'includes/class-countdown.php';
require_once DLM_PLUGIN_DIR . 'includes/class-ajax.php';
require_once DLM_PLUGIN_DIR . 'includes/class-updater.php';

// Create directories on activation
function dlm_create_directories() {
    $dirs = array(
        DLM_PLUGIN_DIR . 'includes',
        DLM_PLUGIN_DIR . 'templates',
        DLM_PLUGIN_DIR . 'assets',
        DLM_PLUGIN_DIR . 'assets/css',
        DLM_PLUGIN_DIR . 'assets/js'
    );
    
    foreach ($dirs as $dir) {
        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }
    }
}

register_activation_hook(__FILE__, 'dlm_create_directories');

// Initialize plugin
class DLM_Plugin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Activation hook
        register_activation_hook(__FILE__, array('DLM_Database', 'activate'));
        
        // Initialize components
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    public function init() {
        // Initialize classes
        DLM_Admin::get_instance();
        DLM_Shortcode::get_instance();
        DLM_Countdown::get_instance();
        DLM_Ajax::get_instance();
        DLM_Updater::get_instance(); // Auto-update
    }
}

// Start the plugin
DLM_Plugin::get_instance();