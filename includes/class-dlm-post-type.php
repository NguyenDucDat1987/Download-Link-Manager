<?php

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Post_Type
{
    public function register()
    {
        $labels = array(
            'name' => _x('Downloads', 'Post Type General Name', 'download-link-manager'),
            'singular_name' => _x('Download', 'Post Type Singular Name', 'download-link-manager'),
            'menu_name' => __('Downloads', 'download-link-manager'),
            'name_admin_bar' => __('Download', 'download-link-manager'),
            'archives' => __('Download Archives', 'download-link-manager'),
            'attributes' => __('Download Attributes', 'download-link-manager'),
            'parent_item_colon' => __('Parent Download:', 'download-link-manager'),
            'all_items' => __('Tất Cả Downloads', 'download-link-manager'),
            'add_new_item' => __('Thêm Download Mới', 'download-link-manager'),
            'add_new' => __('Thêm Mới', 'download-link-manager'),
            'new_item' => __('Download Mới', 'download-link-manager'),
            'edit_item' => __('Sửa Download', 'download-link-manager'),
            'update_item' => __('Cập Nhật Download', 'download-link-manager'),
            'view_item' => __('Xem Download', 'download-link-manager'),
            'view_items' => __('Xem Downloads', 'download-link-manager'),
            'search_items' => __('Tìm Download', 'download-link-manager'),
            'not_found' => __('Không tìm thấy', 'download-link-manager'),
            'not_found_in_trash' => __('Không có trong thùng rác', 'download-link-manager'),
        );
        
        $args = array(
            'label' => __('Download', 'download-link-manager'),
            'description' => __('Quản lý link download', 'download-link-manager'),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array(),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-download',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
        );
        
        register_post_type('dlm_download', $args);
    }
}