<section class="testimonials">
    <div class="testimonials-bg">
        <div class="testimonials-header">
            <div class="testimonials-tag">WE LOVE GOOD COMPLIMENT</div>
            <h2 class="testimonials-title">What Our Customers Say</h2>
            <p class="testimonials-subtitle">Real stories from people who found their perfect fit</p>
        </div>
        
        <div class="testimonial-slider-container">
            <button class="slider-btn prev-btn" id="prevTestimonial">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            
            <div class="testimonial-slider" id="testimonialSlider">
                <div class="testimonial-slide active">
                    <div class="testimonial-icon">
                        <i class="fa-solid fa-quote-right"></i>
                    </div>
                    <p class="testimonial-quote">"Best fitted white denim shirt more white denim than expected flexible crazy soft. I never thought a simple shirt could make me feel this confident!"</p>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/avatar-1.jpg') }}" alt="Sarah" class="author-avatar">
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <span>Verified Buyer</span>
                        </div>
                    </div>
                    <div class="testimonial-rating">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                
                <div class="testimonial-slide">
                    <div class="testimonial-icon">
                        <i class="fa-solid fa-quote-right"></i>
                    </div>
                    <p class="testimonial-quote">"More than expected crazy soft, flexible and best fitted white simple denim shirt. Finally found a brand that understands my body type!"</p>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/avatar-2.jpg') }}" alt="Michelle" class="author-avatar">
                        <div class="author-info">
                            <h4>Michelle Chen</h4>
                            <span>Style Enthusiast</span>
                        </div>
                    </div>
                    <div class="testimonial-rating">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                
                <div class="testimonial-slide">
                    <div class="testimonial-icon">
                        <i class="fa-solid fa-quote-right"></i>
                    </div>
                    <p class="testimonial-quote">"Best fitted white denim shirt more than expected crazy soft, flexible and breathable. The recommendation system really works! 10/10 would recommend."</p>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/avatar-3.jpg') }}" alt="Amanda" class="author-avatar">
                        <div class="author-info">
                            <h4>Amanda Lee</h4>
                            <span>Fashion Blogger</span>
                        </div>
                    </div>
                    <div class="testimonial-rating">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            
            <button class="slider-btn next-btn" id="nextTestimonial">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
        
        <div class="slider-dots" id="testimonialDots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</section>
@push('scripts')
<script>
// Testimonial Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.testimonial-slide');
const dots = document.querySelectorAll('.dot');
const prevBtn = document.getElementById('prevTestimonial');
const nextBtn = document.getElementById('nextTestimonial');

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    currentSlide = (index + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
}

prevBtn?.addEventListener('click', () => showSlide(currentSlide - 1));
nextBtn?.addEventListener('click', () => showSlide(currentSlide + 1));

dots.forEach((dot, i) => {
    dot.addEventListener('click', () => showSlide(i));
});

// Auto slide every 5 seconds
setInterval(() => showSlide(currentSlide + 1), 5000);
</script>
@endpush