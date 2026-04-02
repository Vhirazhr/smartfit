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
            
            <button class="btn-size-guide" id="sizeGuideBtn">Get Full Size Guide →</button>
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

@push('scripts')
<script>
document.getElementById('sizeGuideBtn')?.addEventListener('click', function() {
    alert('Download our complete size guide PDF with detailed measurement instructions!');
});
</script>
@endpush