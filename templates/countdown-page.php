<?php
/**
 * Countdown Page Template
 */
if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($link->title); ?> - Shutdown Computer Time</title>
    <link rel="stylesheet" href="<?php echo DLM_PLUGIN_URL; ?>assets/css/countdown.css">
</head>
<body>
    <?php if (isset($ads_by_position['header'])): ?>
        <div class="ad-header">
            <?php foreach ($ads_by_position['header'] as $ad): ?>
                <div class="ad-item">
                    <?php if ($ad->link_url): ?>
                        <a href="<?php echo esc_url($ad->link_url); ?>" target="_blank">
                            <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                        </a>
                    <?php else: ?>
                        <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="page-layout">
        <div class="ad-container ad-left">
            <?php if (isset($ads_by_position['left'])): ?>
                <?php foreach ($ads_by_position['left'] as $ad): ?>
                    <div class="ad-item">
                        <?php if ($ad->link_url): ?>
                            <a href="<?php echo esc_url($ad->link_url); ?>" target="_blank">
                                <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="countdown-container">
            <h1>Shutdown Computer Time</h1>
            
            <?php if (isset($ads_by_position['before_countdown'])): ?>
                <?php foreach ($ads_by_position['before_countdown'] as $ad): ?>
                    <div class="ad-item" style="margin-bottom:30px;">
                        <?php if ($ad->link_url): ?>
                            <a href="<?php echo esc_url($ad->link_url); ?>" target="_blank">
                                <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <div class="countdown-wrapper">
                <div class="countdown-circle">
                    <svg class="progress-ring" viewBox="0 0 200 200">
                        <circle class="progress-ring-circle" 
                                stroke-dasharray="565.48" 
                                stroke-dashoffset="0"
                                r="90" 
                                cx="100" 
                                cy="100" />
                    </svg>
                    <div class="countdown-number" id="countdown"><?php echo $link->countdown_time; ?></div>
                    <div class="checkmark" id="checkmark" style="display:none;">✓</div>
                </div>
            </div>
            
            <p class="info-text" id="info-text">Vui lòng đợi để nhận link tải về...</p>
            
            <?php if (!empty($link->password)): ?>
            <div class="password-box" id="password-box">
                <div class="password-label">🔐 Mật khẩu giải nén:</div>
                <div class="password-value"><?php echo esc_html($link->password); ?></div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($ads_by_position['after_countdown'])): ?>
                <?php foreach ($ads_by_position['after_countdown'] as $ad): ?>
                    <div class="ad-item" style="margin-top:30px;">
                        <?php if ($ad->link_url): ?>
                            <a href="<?php echo esc_url($ad->link_url); ?>" target="_blank">
                                <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <a href="<?php echo esc_url($link->download_url); ?>" 
               class="download-button" 
               id="download-btn">
                ⬇️ Tải về ngay
            </a>
        </div>
        
        <div class="ad-container ad-right">
            <?php if (isset($ads_by_position['right'])): ?>
                <?php foreach ($ads_by_position['right'] as $ad): ?>
                    <div class="ad-item">
                        <?php if ($ad->link_url): ?>
                            <a href="<?php echo esc_url($ad->link_url); ?>" target="_blank">
                                <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (isset($ads_by_position['footer'])): ?>
        <div class="ad-footer">
            <?php foreach ($ads_by_position['footer'] as $ad): ?>
                <div class="ad-item">
                    <?php if ($ad->link_url): ?>
                        <a href="<?php echo esc_url($ad->link_url); ?>" target="_blank">
                            <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                        </a>
                    <?php else: ?>
                        <img src="<?php echo esc_url($ad->image_url); ?>" alt="Quảng cáo" style="width:<?php echo $ad->width; ?>;height:<?php echo $ad->height; ?>;">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="dlm-footer-copyright">
        <p>
            © <?php echo date('Y'); ?> <strong>Download Link Manager</strong> | 
            Powered by <a href="https://deeaytee.xyz" target="_blank">Đạt Nguyễn (DeeAyTee)</a>
        </p>
    </div>
    
    <script>
        const countdownTime = <?php echo $link->countdown_time; ?>;
        const linkId = <?php echo $link_id; ?>;
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <script src="<?php echo DLM_PLUGIN_URL; ?>assets/js/countdown.js"></script>
</body>
</html>