<?php
/**
 * Plugin Name: Download Link Manager Pro
 * Plugin URI:  https://deeaytee.xyz/download-link-manager-pro
 * Description: Plugin quản lý link download với tracking, countdown, password và chống spam.
 * Version:     2.0.5
 * Author:      Dat Nguyen (DeeAyTee)
 * Author URI:  https://deeaytee.xyz
 * License:     GPL-2.0+
 * Text Domain: download-link-manager
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
	exit;
}

// Define plugin constants
define('DLM_VERSION', '2.0.5');
define('DLM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DLM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoloader
require_once DLM_PLUGIN_DIR . 'includes/class-dlm-loader.php';
require_once DLM_PLUGIN_DIR . 'includes/class-dlm-download-handler.php';

// Initialize the plugin
function dlm_init_plugin()
{
	// GitHub Updater
	if (is_admin()) {
		require_once DLM_PLUGIN_DIR . 'includes/class-dlm-updater.php';
		new DLM_Updater(__FILE__, 'NguyenDucDat1987', 'Download-Link-Manager');
	}

	$loader = new DLM_Loader();
	$loader->run();
}
add_action('plugins_loaded', 'dlm_init_plugin');

// Activation hook - create download tracking table
register_activation_hook(__FILE__, 'dlm_activate_plugin');

function dlm_activate_plugin()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'dlm_download_logs';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		download_id bigint(20) NOT NULL,
		ip_address varchar(100) NOT NULL,
		user_agent varchar(255) DEFAULT NULL,
		user_id bigint(20) DEFAULT NULL,
		download_date datetime DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		KEY download_id (download_id),
		KEY ip_address (ip_address),
		KEY download_date (download_date)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}