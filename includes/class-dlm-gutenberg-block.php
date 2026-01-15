<?php

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Gutenberg_Block
{
    public function register_block()
    {
        // Register block script
        wp_register_script(
            'dlm-block-editor',
            DLM_PLUGIN_URL . 'assets/js/block.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
            DLM_VERSION
        );

        // Register block
        register_block_type('dlm/download-link', array(
            'editor_script' => 'dlm-block-editor',
            'render_callback' => array($this, 'render_block'),
            'attributes' => array(
                'downloadId' => array(
                    'type' => 'number',
                    'default' => 0
                ),
                'buttonText' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'style' => array(
                    'type' => 'string',
                    'default' => 'button'
                ),
                'showVersion' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'showCount' => array(
                    'type' => 'boolean',
                    'default' => false
                )
            )
        ));
    }

    public function render_block($attributes)
    {
        $shortcode = new DLM_Shortcodes();
        
        return $shortcode->render_download_link(array(
            'id' => $attributes['downloadId'],
            'text' => $attributes['buttonText'],
            'style' => $attributes['style'],
            'show_version' => $attributes['showVersion'] ? 'yes' : 'no',
            'show_count' => $attributes['showCount'] ? 'yes' : 'no'
        ));
    }
}