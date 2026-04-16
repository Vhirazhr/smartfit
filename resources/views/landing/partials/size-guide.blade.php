<section class="sizeguide-section">
    <div class="sizeguide-container">
        <div class="sizeguide-content">
            <h2>Quick <span>Size Guide</span></h2>
            <p>Finding your perfect fit starts with knowing your measurements. Use our quick reference to understand standard sizing.</p>
            
            <ul class="size-guide-list">
                <li><span>XS (0-2)</span><span>Bust: 30-32" | Waist: 23-24" | Hips: 33-34"</span></li>
                <li><span>S (4-6)</span><span>Bust: 33-34" | Waist: 25-26" | Hips: 35-36"</span></li>
                <li><span>M (8-10)</span><span>Bust: 35-36" | Waist: 27-28" | Hips: 37-38"</span></li>
                <li><span>L (12-14)</span><span>Bust: 37-38" | Waist: 29-31" | Hips: 39-41"</span></li>
                <li><span>XL (16-18)</span><span>Bust: 39-41" | Waist: 32-34" | Hips: 42-44"</span></li>
            </ul>
            
<a href="{{ asset('files/size-guide.pdf') }}" 
   class="btn-size-guide"
   onclick="showDownloadToast(event)">
    Get Full Size Guide →
</a>
        </div>
        <div class="sizeguide-image">
            <img src="{{ asset('images/size-guide.jpg') }}" alt="Size Guide">
            <div class="sizeguide-badge">
                <span>Free</span>
                <span>Size Consultation</span>
            </div>
        </div>
    </div>
</section>

<script>
function showDownloadToast(event) {
    event.preventDefault();
    
    const downloadLink = event.currentTarget.href;
    const toast = document.createElement('div');
    toast.className = 'sizeguide-toast success';
    toast.innerHTML = `
        <div class="toast-icon">📄</div>
        <div class="toast-content">
            <div class="toast-title">Download Started!</div>
            <div class="toast-message">Your size guide is being downloaded...</div>
        </div>
        <div class="toast-close">✕</div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    toast.querySelector('.toast-close').onclick = () => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    };
    
    setTimeout(() => {
        const link = document.createElement('a');
        link.href = downloadLink;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        toast.querySelector('.toast-icon').innerHTML = '✅';
        toast.querySelector('.toast-title').innerHTML = 'Download Complete!';
        toast.querySelector('.toast-message').innerHTML = 'Your size guide is ready! 📏';
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
        
    }, 500);
}
</script>