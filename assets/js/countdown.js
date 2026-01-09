// Countdown Timer
let timeLeft = countdownTime;
const countdownEl = document.getElementById('countdown');
const checkmarkEl = document.getElementById('checkmark');
const downloadBtn = document.getElementById('download-btn');
const passwordBox = document.getElementById('password-box');
const infoText = document.getElementById('info-text');
const circle = document.querySelector('.progress-ring-circle');
const circumference = 2 * Math.PI * 90;

// Initialize progress ring
circle.style.strokeDasharray = circumference;
circle.style.strokeDashoffset = 0;

// Countdown timer
const timer = setInterval(() => {
    timeLeft--;
    countdownEl.textContent = timeLeft;
    
    // Update progress ring
    const progress = (timeLeft / countdownTime) * circumference;
    circle.style.strokeDashoffset = circumference - progress;
    
    if (timeLeft <= 0) {
        clearInterval(timer);
        
        // Hide countdown number, show checkmark
        countdownEl.style.display = 'none';
        checkmarkEl.style.display = 'block';
        
        setTimeout(() => {
            // Update info text
            infoText.textContent = '✅ Link tải về đã sẵn sàng!';
            infoText.style.color = '#28a745';
            infoText.style.fontWeight = 'bold';
            
            // Show download button
            downloadBtn.classList.add('show');
            
            // Show password box if exists
            if (passwordBox) {
                passwordBox.classList.add('show');
            }
            
            // Track download
            fetch(ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=dlm_track_download&link_id=' + linkId
            });
        }, 500);
    }
}, 1000);