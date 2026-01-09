<?php
/**
 * Countdown Page Handler
 */

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Countdown {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('template_redirect', array($this, 'handle_countdown_page'));
    }
    
    public function handle_countdown_page() {
        if (!isset($_GET['dlm_countdown'])) {
            return;
        }
        
        $link_id = intval($_GET['dlm_countdown']);
        $link = DLM_Database::get_link($link_id);
        
        if (!$link) {
            wp_die('❌ Link không tồn tại hoặc đã bị xóa.');
        }
        
        // Lấy quảng cáo
        $all_ads = DLM_Database::get_ads_by_position(null, true);
        $ads_by_position = array();
        
        foreach ($all_ads as $ad) {
            $ads_by_position[$ad->position][] = $ad;
        }
        
        // Include template
        include DLM_PLUGIN_DIR . 'templates/countdown-page.php';
        exit;
    }
}