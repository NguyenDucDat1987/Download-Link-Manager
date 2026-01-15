<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo isset($download->post_title) ? esc_html($download->post_title) : 'Download'; ?> - Download</title>
    <?php wp_head(); ?>
    <style>
        body.dlm-download-page {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dlm-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .dlm-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .dlm-header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }

        .dlm-header .version {
            opacity: 0.9;
            font-size: 14px;
        }

        .dlm-content {
            padding: 30px;
        }

        .dlm-ad-zone {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            min-height: 100px;
        }

        .dlm-countdown-box {
            text-align: center;
            padding: 40px 20px;
        }

        .dlm-countdown-timer {
            font-size: 72px;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }

        .dlm-countdown-text {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }

        .dlm-download-section {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .dlm-download-section.active {
            display: block;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dlm-download-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .dlm-download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .dlm-password-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .dlm-password-box h3 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 16px;
        }

        .dlm-password-code {
            background: white;
            border: 1px solid #ffc107;
            padding: 10px 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            user-select: all;
        }

        .dlm-info {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }

        .dlm-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="dlm-download-page">
    <div class="dlm-container">
        <div class="dlm-header">
            <h1><?php echo esc_html($download->post_title); ?></h1>
            <?php if ($file_version): ?>
                <div class="version">Phiên bản: <?php echo esc_html($file_version); ?></div>
            <?php endif; ?>
        </div>

        <div class="dlm-content">
            <?php if ($ad_code): ?>
                <div class="dlm-ad-zone">
                    <?php echo $ad_code; ?>
                </div>
            <?php endif; ?>

            <?php if ($countdown_time > 0): ?>
                <div class="dlm-countdown-box" id="countdownBox">
                    <div class="dlm-countdown-text">Vui lòng đợi để hiển thị link download</div>
                    <div class="dlm-countdown-timer" id="countdownTimer"><?php echo $countdown_time; ?></div>
                    <div class="dlm-spinner"></div>
                </div>
            <?php endif; ?>

            <div class="dlm-download-section <?php echo $countdown_time <= 0 ? 'active' : ''; ?>" id="downloadSection">
                <a href="#" class="dlm-download-btn" id="downloadBtn">
                    📥 Tải Xuống Ngay
                </a>

                <?php if ($file_password): ?>
                    <div class="dlm-password-box">
                        <h3>🔐 Mật khẩu giải nén:</h3>
                        <div class="dlm-password-code"><?php echo esc_html($file_password); ?></div>
                    </div>
                <?php endif; ?>

                <div class="dlm-info">
                    <strong>💡 Lưu ý:</strong> Nếu link không tự động tải, vui lòng click vào nút download ở trên.
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const countdownTime = <?php echo intval($countdown_time); ?>;
            const downloadId = <?php echo intval($download_id); ?>;
            const fileUrl = <?php echo json_encode($file_url); ?>;

            if (countdownTime > 0) {
                let timeLeft = countdownTime;
                const timerElement = document.getElementById('countdownTimer');
                const countdownBox = document.getElementById('countdownBox');
                const downloadSection = document.getElementById('downloadSection');

                const countdown = setInterval(function () {
                    timeLeft--;
                    timerElement.textContent = timeLeft;

                    if (timeLeft <= 0) {
                        clearInterval(countdown);
                        countdownBox.style.display = 'none';
                        downloadSection.classList.add('active');
                    }
                }, 1000);
            }

            // Handle download button click
            document.getElementById('downloadBtn').addEventListener('click', function (e) {
                e.preventDefault();

                // Track download via AJAX
                const formData = new FormData();
                formData.append('action', 'dlm_track_download');
                formData.append('download_id', downloadId);
                formData.append('nonce', dlmData.nonce);

                fetch(dlmData.ajaxurl, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to file
                            window.location.href = data.data.file_url;
                        }
                    })
                    .catch(error => {
                        // Fallback: direct download
                        window.location.href = fileUrl;
                    });
            });
        })();
    </script>

    <?php wp_footer(); ?>
</body>

</html>