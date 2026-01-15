<?php

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Loader
{
    public function run()
    {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies()
    {
        // Load only if files exist
        $files = array(
            'includes/class-dlm-post-type.php',
            'includes/class-dlm-meta-boxes.php',
            'includes/class-dlm-shortcodes.php',
            'includes/class-dlm-gutenberg-block.php',
            'includes/class-dlm-settings.php',
            'includes/class-dlm-admin-ui.php'
        );

        foreach ($files as $file) {
            $filepath = DLM_PLUGIN_DIR . $file;
            if (file_exists($filepath)) {
                require_once $filepath;
            }
        }
    }

    private function define_admin_hooks()
    {
        $post_type = new DLM_Post_Type();
        add_action('init', array($post_type, 'register'));

        $meta_boxes = new DLM_Meta_Boxes();
        add_action('add_meta_boxes', array($meta_boxes, 'add_meta_boxes'));
        add_action('save_post', array($meta_boxes, 'save_meta_box_data'));

        // Settings page
        if (class_exists('DLM_Settings')) {
            $settings = new DLM_Settings();
            add_action('admin_menu', array($settings, 'add_settings_page'));
            add_action('admin_init', array($settings, 'register_settings'));
        }

        // Gutenberg block
        if (class_exists('DLM_Gutenberg_Block')) {
            $gutenberg = new DLM_Gutenberg_Block();
            add_action('init', array($gutenberg, 'register_block'));
        }

        // Admin UI for shortcode button
        if (class_exists('DLM_Admin_UI')) {
            new DLM_Admin_UI();
        }

        // Add shortcode column to downloads list
        add_filter('manage_dlm_download_posts_columns', array($this, 'add_shortcode_column'));
        add_action('manage_dlm_download_posts_custom_column', array($this, 'display_shortcode_column'), 10, 2);
    }

    public function add_shortcode_column($columns)
    {
        $new_columns = array();
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') {
                $new_columns['shortcode'] = 'Shortcode';
            }
        }
        return $new_columns;
    }

    public function display_shortcode_column($column, $post_id)
    {
        if ($column === 'shortcode') {
            $shortcode = '[download_link id="' . $post_id . '"]';
            echo '<button class="dlm-quick-copy button" data-shortcode="' . esc_attr($shortcode) . '" title="Click để copy">
                <span class="dashicons dashicons-clipboard"></span>
                Copy
            </button>';
        }
    }

    private function define_public_hooks()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        $shortcodes = new DLM_Shortcodes();
        add_shortcode('download_link', array($shortcodes, 'render_download_link'));

        $download_handler = new DLM_Download_Handler();
        $download_handler->run();
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('dlm-styles', DLM_PLUGIN_URL . 'assets/css/style.css', array(), DLM_VERSION);
        wp_enqueue_script('dlm-scripts', DLM_PLUGIN_URL . 'assets/js/download.js', array('jquery'), DLM_VERSION, true);

        wp_localize_script('dlm-scripts', 'dlmData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dlm_nonce')
        ));

        // Enqueue admin scripts on all admin pages for quick copy
        if (is_admin()) {
            wp_enqueue_style('dlm-admin-style', DLM_PLUGIN_URL . 'assets/css/admin-style.css', array(), DLM_VERSION);
            wp_enqueue_script('dlm-admin-script', DLM_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery'), DLM_VERSION, true);
        }
    }
}