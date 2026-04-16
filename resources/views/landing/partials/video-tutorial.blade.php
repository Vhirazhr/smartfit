<!-- Video Tutorial Section - How To Measure Your Body -->
<section class="video-tutorial-section">
    <div class="video-tutorial-container">
        <div class="video-tutorial-header">
            <span class="section-badge">📹 Video Tutorial</span>
            <h2>Confused About <span>How to Measure Your Body?</span></h2>
            <p>Follow this practical video guide to get accurate measurements. Make sure the measuring tape is not too loose or too tight!</p>
        </div>

        <div class="video-tutorial-wrapper">
            <!-- Thumbnail + Play Button -->
            <div class="video-thumbnail" onclick="openVideoModal()">
                <img src="https://img.youtube.com/vi/3ZSfY87v6pM/maxresdefault.jpg" alt="Video Thumbnail - How to Measure Your Body">
                <div class="play-overlay">
                    <div class="play-button">
                        <i class="fa-solid fa-play"></i>
                    </div>
                </div>
                <div class="video-duration">
                    <i class="fa-regular fa-clock"></i> 3:45
                </div>
            </div>

            <!-- Video Info -->
            <div class="video-info">
                <div class="video-title">
                    <i class="fa-brands fa-youtube"></i>
                    <span>How To Measure Your Body Using A Measuring Tape</span>
                </div>
                <div class="video-channel">
                    <i class="fa-regular fa-circle-user"></i>
                    <span>Channel: <strong>House of Tabois</strong></span>
                </div>
                <div class="video-description">
                    <p>This video shows a quick way to take basic body measurements for fashion, fitness, modeling, and much more. Perfect for beginners!</p>
                </div>
                <div class="video-tips">
                    <div class="tip-item">
                        <i class="fa-regular fa-lightbulb"></i>
                        <span>Use a flexible measuring tape (tailor tape)</span>
                    </div>
                    <div class="tip-item">
                        <i class="fa-regular fa-lightbulb"></i>
                        <span>Measure in thin underwear</span>
                    </div>
                    <div class="tip-item">
                        <i class="fa-regular fa-lightbulb"></i>
                        <span>Stand straight and relaxed while measuring</span>
                    </div>
                </div>
                <div class="video-credit">
                    <i class="fa-regular fa-copyright"></i> Credit: YouTube/Daniela Tabois
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Popup for Video (NO AUTOPLAY) -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <div class="video-modal-header">
                <h3>📏 How to Measure Your Body Correctly</h3>
                <span class="video-modal-close" onclick="closeVideoModal()">&times;</span>
            </div>
            <div class="video-modal-body">
                <div class="video-responsive">
                    <iframe 
                        id="youtubeFrame"
                        width="100%" 
                        height="100%" 
                        src="https://www.youtube.com/embed/3ZSfY87v6pM?autoplay=0&rel=0"
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        referrerpolicy="strict-origin-when-cross-origin" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function openVideoModal() {
        const modal = document.getElementById('videoModal');
        const iframe = document.getElementById('youtubeFrame');
        
        iframe.src = "https://www.youtube.com/embed/3ZSfY87v6pM?autoplay=0&rel=0";
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        const iframe = document.getElementById('youtubeFrame');
        
        iframe.src = '';
        
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    window.onclick = function(event) {
        const modal = document.getElementById('videoModal');
        if (event.target === modal) {
            closeVideoModal();
        }
    }
</script>