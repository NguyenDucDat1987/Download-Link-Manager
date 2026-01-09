<?php
/**
 * Shortcode Handler
 */

if (!defined('ABSPATH')) {
    exit;
}

class DLM_Shortcode {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('download_link', array($this, 'render_shortcode'));
        add_action('media_buttons', array($this, 'add_shortcode_button'));
        add_action('admin_footer', array($this, 'add_shortcode_popup'));
    }
    
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'text' => 'Tải về ngay'
        ), $atts);
        
        $link_id = intval($atts['id']);
        
        if ($link_id <= 0) {
            return '<span style="color:red;">❌ Link ID không hợp lệ</span>';
        }
        
        // Kiểm tra link có tồn tại không
        $link = DLM_Database::get_link($link_id);
        if (!$link) {
            return '<span style="color:red;">❌ Link không tồn tại</span>';
        }
        
        $countdown_url = add_query_arg('dlm_countdown', $link_id, home_url('/'));
        
        return sprintf(
            '<a href="%s" target="_blank" class="dlm-download-button" style="display:inline-block;padding:12px 30px;background:linear-gradient(135deg,#667eea 0%%,#764ba2 100%%);color:#fff;text-decoration:none;border-radius:50px;font-weight:bold;transition:all 0.3s;box-shadow:0 4px 15px rgba(102,126,234,0.4);">%s</a>',
            esc_url($countdown_url),
            esc_html($atts['text'])
        );
    }
    
    public function add_shortcode_button() {
        $screen = get_current_screen();
        if (!$screen || !in_array($screen->base, array('post', 'page'))) {
            return;
        }
        
        echo '<button type="button" class="button button-primary" id="dlm-add-shortcode" style="margin-left:5px;">
                <span class="dashicons dashicons-download" style="margin-top:3px;"></span> 
                Chèn Link Tải Về
              </button>';
    }
    
    public function add_shortcode_popup() {
        $screen = get_current_screen();
        if (!$screen || !in_array($screen->base, array('post', 'page'))) {
            return;
        }
        
        $links = DLM_Database::get_links();
        ?>
        <div id="dlm-shortcode-popup" style="display:none;">
            <div class="dlm-popup-overlay"></div>
            <div class="dlm-popup-content">
                <span class="dlm-close-x" id="dlm-close-x">&times;</span>
                <h2 style="margin-bottom:20px;">📥 Chèn Link Tải Về</h2>
                
                <?php if (empty($links)): ?>
                    <p style="color:#d63638;padding:20px;background:#fff0f0;border-radius:8px;text-align:center;">
                        ⚠️ Bạn chưa tạo link nào. <a href="<?php echo admin_url('admin.php?page=download-link-manager'); ?>">Tạo link ngay</a>
                    </p>
                <?php else: ?>
                    <table style="width:100%;margin-bottom:20px;">
                        <tr>
                            <td style="padding:10px 0;width:120px;"><strong>Chọn link:</strong></td>
                            <td>
                                <select id="dlm-link-select" style="width:100%;padding:10px;font-size:14px;border:2px solid #667eea;border-radius:8px;">
                                    <option value="">-- Chọn link --</option>
                                    <?php foreach ($links as $link): ?>
                                        <option value="<?php echo $link->id; ?>"><?php echo esc_html($link->title); ?> (ID: <?php echo $link->id; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 0;"><strong>Text nút:</strong></td>
                            <td>
                                <input type="text" id="dlm-button-text" value="Tải về ngay" style="width:100%;padding:10px;font-size:14px;border:2px solid #667eea;border-radius:8px;">
                            </td>
                        </tr>
                    </table>
                    
                    <div style="background:#f0f0f1;padding:15px;border-radius:8px;margin-bottom:20px;">
                        <strong>Preview:</strong><br>
                        <code id="shortcode-preview" style="background:#fff;padding:8px;display:inline-block;margin-top:8px;border-radius:4px;">[download_link id="?" text="Tải về ngay"]</code>
                    </div>
                <?php endif; ?>
                
                <div style="text-align:right;margin-top:20px;">
                    <?php if (!empty($links)): ?>
                        <button class="button button-primary button-large" id="dlm-insert-shortcode" style="background:#667eea;border:none;padding:12px 30px;font-size:16px;">
                            ✅ Chèn vào bài viết
                        </button>
                    <?php endif; ?>
                    <button class="button button-large" id="dlm-close-popup" style="padding:12px 30px;margin-left:10px;">Đóng</button>
                </div>
            </div>
        </div>
        
        <style>
            .dlm-popup-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.75);
                z-index: 100000;
                animation: fadeIn 0.3s;
            }
            .dlm-popup-content {
                position: fixed;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 35px;
                border-radius: 15px;
                z-index: 100001;
                min-width: 550px;
                max-width: 90%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.4);
                animation: slideUp 0.3s;
            }
            .dlm-close-x {
                position: absolute;
                top: 15px; right: 20px;
                font-size: 32px;
                color: #999;
                cursor: pointer;
                line-height: 1;
                transition: color 0.3s;
            }
            .dlm-close-x:hover { color: #d63638; }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { transform: translate(-50%, -40%); opacity: 0; }
                to { transform: translate(-50%, -50%); opacity: 1; }
            }
            @keyframes slideDown {
                from { transform: translate(-50%, -20px); opacity: 0; }
                to { transform: translate(-50%, 0); opacity: 1; }
            }
            @media (max-width: 768px) {
                .dlm-popup-content {
                    min-width: auto;
                    width: 90%;
                    padding: 25px;
                }
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('#dlm-add-shortcode').on('click', function(e) {
                e.preventDefault();
                $('#dlm-shortcode-popup').fadeIn(200);
            });
            
            $('#dlm-close-popup, #dlm-close-x, .dlm-popup-overlay').on('click', function() {
                $('#dlm-shortcode-popup').fadeOut(200);
            });
            
            $('.dlm-popup-content').on('click', function(e) {
                e.stopPropagation();
            });
            
            $('#dlm-link-select, #dlm-button-text').on('change keyup', function() {
                var linkId = $('#dlm-link-select').val() || '?';
                var btnText = $('#dlm-button-text').val() || 'Tải về ngay';
                $('#shortcode-preview').text('[download_link id="' + linkId + '" text="' + btnText + '"]');
            });
            
            $('#dlm-insert-shortcode').on('click', function() {
                var linkId = $('#dlm-link-select').val();
                var btnText = $('#dlm-button-text').val() || 'Tải về ngay';
                
                if (!linkId) {
                    alert('⚠️ Vui lòng chọn link!');
                    return;
                }
                
                var shortcode = '[download_link id="' + linkId + '" text="' + btnText + '"]';
                
                // TinyMCE
                if (typeof tinymce !== 'undefined' && tinymce.activeEditor && !tinymce.activeEditor.isHidden()) {
                    tinymce.activeEditor.insertContent(shortcode);
                } 
                // Gutenberg
                else if (typeof wp !== 'undefined' && wp.data && wp.data.select('core/block-editor')) {
                    var blocks = wp.blocks;
                    var data = wp.data;
                    var newBlock = blocks.createBlock('core/shortcode', { text: shortcode });
                    data.dispatch('core/block-editor').insertBlocks(newBlock);
                }
                // Textarea fallback
                else {
                    var textarea = $('#content');
                    if (textarea.length) {
                        var cursorPos = textarea.prop('selectionStart');
                        var v = textarea.val();
                        textarea.val(v.substring(0, cursorPos) + shortcode + v.substring(cursorPos));
                    }
                }
                
                $('#dlm-shortcode-popup').fadeOut(200);
                
                $('body').append('<div id="dlm-success-msg" style="position:fixed;top:50px;left:50%;transform:translateX(-50%);background:#46b450;color:white;padding:15px 30px;border-radius:8px;z-index:999999;box-shadow:0 4px 15px rgba(0,0,0,0.3);animation:slideDown 0.3s;">✅ Đã chèn shortcode thành công!</div>');
                setTimeout(function() {
                    $('#dlm-success-msg').fadeOut(300, function() { $(this).remove(); });
                }, 2500);
            });
            
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#dlm-shortcode-popup').is(':visible')) {
                    $('#dlm-shortcode-popup').fadeOut(200);
                }
            });
        });
        </script>
        <?php
    }
}