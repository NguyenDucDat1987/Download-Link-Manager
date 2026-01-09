<?php
/**
 * Database Handler
 */

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Database {
    
    public static function activate() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Bảng links
        $table_links = $wpdb->prefix . 'dlm_links';
        $sql1 = "CREATE TABLE IF NOT EXISTS {$table_links} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            download_url text NOT NULL,
            password varchar(255) DEFAULT '',
            countdown_time int(11) DEFAULT 10,
            total_clicks int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Bảng quảng cáo
        $table_ads = $wpdb->prefix . 'dlm_ads';
        $sql2 = "CREATE TABLE IF NOT EXISTS {$table_ads} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            position varchar(50) NOT NULL,
            image_url text NOT NULL,
            link_url text DEFAULT '',
            width varchar(20) DEFAULT '100%',
            height varchar(20) DEFAULT 'auto',
            status varchar(20) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Bảng thống kê
        $table_stats = $wpdb->prefix . 'dlm_stats';
        $sql3 = "CREATE TABLE IF NOT EXISTS {$table_stats} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            link_id mediumint(9) NOT NULL,
            ip_address varchar(45) NOT NULL,
            user_agent text,
            clicked_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY link_id (link_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql3);
        
        flush_rewrite_rules();
    }
    
    public static function get_links() {
        global $wpdb;
        $table = $wpdb->prefix . 'dlm_links';
        return $wpdb->get_results("SELECT * FROM {$table} ORDER BY id DESC");
    }
    
    public static function get_link($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'dlm_links';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $id));
    }
    
    public static function save_link($data, $id = 0) {
        global $wpdb;
        $table = $wpdb->prefix . 'dlm_links';
        
        if ($id > 0) {
            return $wpdb->update($table, $data, array('id' => $id));
        } else {
            return $wpdb->insert($table, $data);
        }
    }
    
    public static function delete_link($id) {
        global $wpdb;
        $table_links = $wpdb->prefix . 'dlm_links';
        $table_stats = $wpdb->prefix . 'dlm_stats';
        
        $wpdb->delete($table_stats, array('link_id' => $id));
        return $wpdb->delete($table_links, array('id' => $id));
    }
    
    public static function get_ads() {
        global $wpdb;
        $table = $wpdb->prefix . 'dlm_ads';
        return $wpdb->get_results("SELECT * FROM {$table} ORDER BY position, id");
    }
    
    public static function get_ads_by_position($position = null, $active_only = true) {
        global $wpdb;
        $table = $wpdb->prefix . 'dlm_ads';
        
        $sql = "SELECT * FROM {$table} WHERE 1=1";
        
        if ($active_only) {
            $sql .= " AND status = 'active'";
        }
        
        if ($position) {
            $sql .= $wpdb->prepare(" AND position = %s", $position);
        }
        
        $sql .= " ORDER BY position, id";
        
        return $wpdb->get_results($sql);
    }
    
    public static function save_ad($data, $id = 0) {
        global $wpdb;
        $table = $wpdb->prefix . 'dlm_ads';
        
        if ($id > 0) {
            return $wpdb->update($table, $data, array('id' => $id));
        } else {
            return $wpdb->insert($table, $data);
        }
    }
    
    public static function delete_ad($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'dlm_ads';
        return $wpdb->delete($table, array('id' => $id));
    }
    
    public static function track_download($link_id) {
        global $wpdb;
        $table_links = $wpdb->prefix . 'dlm_links';
        $table_stats = $wpdb->prefix . 'dlm_stats';
        
        // Tăng tổng lượt click
        $wpdb->query($wpdb->prepare(
            "UPDATE {$table_links} SET total_clicks = total_clicks + 1 WHERE id = %d",
            $link_id
        ));
        
        // Lưu chi tiết
        return $wpdb->insert($table_stats, array(
            'link_id' => $link_id,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
        ));
    }
    
    public static function get_stats() {
        global $wpdb;
        $table_links = $wpdb->prefix . 'dlm_links';
        $table_stats = $wpdb->prefix . 'dlm_stats';
        
        return $wpdb->get_results("
            SELECT l.id, l.title, l.total_clicks, 
                   COUNT(s.id) as today_clicks
            FROM {$table_links} l
            LEFT JOIN {$table_stats} s ON l.id = s.link_id 
                AND DATE(s.clicked_at) = CURDATE()
            GROUP BY l.id
            ORDER BY l.total_clicks DESC
        ");
    }
}