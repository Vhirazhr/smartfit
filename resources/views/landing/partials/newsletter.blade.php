<section class="newsletter">
    <div class="newsletter-content">
        <h2 class="newsletter-title">Subscribe our newsletter</h2>
        <div class="newsletter-form">
            <input type="email" placeholder="Your e-mail address here" class="newsletter-input">
            <button class="newsletter-btn">Subscribe</button>
        </div>
        <p class="newsletter-note">Get weekly fashion tips and exclusive offers</p>
    </div>
</section>

@push('styles')
<style>
.newsletter-note {
    margin-top: 20px;
    font-size: 12px;
    color: #838383;
    font-family: 'Jost', sans-serif;
}
</style>
@endpush

@push('scripts')
<script>
const newsletterBtn = document.querySelector('.newsletter-btn');
const newsletterInput = document.querySelector('.newsletter-input');

if (newsletterBtn && newsletterInput) {
    newsletterBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const email = newsletterInput.value.trim();
        
        if (email === '') {
            alert('Please enter your email address');
        } else if (!email.includes('@') || !email.includes('.')) {
            alert('Please enter a valid email address');
        } else {
            alert('Thank you for subscribing!');
            newsletterInput.value = '';
        }
    });
}
</script>
@endpush