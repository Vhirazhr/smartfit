// ============================================
// MAIN.JS - SMARTfit Landing Page Animations
// ============================================

document.addEventListener('DOMContentLoaded', function() {

    // ========== 1. NAVBAR SCROLL EFFECT ==========
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // ========== 2. DISCOVER BUTTON - SCROLL TO MORPHOTYPES ==========
    const discoverBtn = document.getElementById('discoverBtn');
    if (discoverBtn) {
        discoverBtn.addEventListener('click', function() {
            const morphotypesSection = document.querySelector('.morphotypes-section');
            if (morphotypesSection) {
                morphotypesSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // ========== 3. FADE IN ON SCROLL (Reveal Animation) ==========
    const revealElements = document.querySelectorAll('.morpho-card, .step, .gallery-item, .transform-card, .expert-card, .testimonial-card, .blog-card');
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    
    revealElements.forEach(el => {
        el.classList.add('reveal-item');
        revealObserver.observe(el);
    });

    // ========== 4. COUNTER ANIMATION (Stats Section) ==========
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        const counters = document.querySelectorAll('.stat-number');
        let animated = false;
        
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !animated) {
                    animated = true;
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-count'));
                        let current = 0;
                        const increment = target / 80;
                        const updateCounter = () => {
                            current += increment;
                            if (current < target) {
                                counter.innerText = Math.floor(current);
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.innerText = target.toLocaleString();
                            }
                        };
                        updateCounter();
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        
        statsObserver.observe(statsSection);
    }

    // ========== 5. TESTIMONIAL SLIDER ==========
    const dots = document.querySelectorAll('.dot');
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    
    if (dots.length && testimonialCards.length) {
        let currentIndex = 0;
        
        function showTestimonial(index) {
            testimonialCards.forEach((card, i) => {
                card.classList.toggle('active', i === index);
            });
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
            currentIndex = index;
        }
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => showTestimonial(index));
        });
        
        // Auto slide every 5 seconds
        setInterval(() => {
            let nextIndex = (currentIndex + 1) % testimonialCards.length;
            showTestimonial(nextIndex);
        }, 5000);
    }

    // ========== 6. NEWSLETTER SUBSCRIBE ==========
    const newsletterBtn = document.querySelector('.newsletter-btn');
    const newsletterInput = document.querySelector('.newsletter-input');
    
    if (newsletterBtn && newsletterInput) {
        newsletterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const email = newsletterInput.value.trim();
            if (email === '') {
                showNotification('Please enter your email address', 'error');
            } else if (!email.includes('@') || !email.includes('.')) {
                showNotification('Please enter a valid email address', 'error');
            } else {
                showNotification('Thank you for subscribing! 🎉', 'success');
                newsletterInput.value = '';
            }
        });
    }

    // ========== 7. NOTIFICATION TOAST ==========
    function showNotification(message, type) {
        const toast = document.createElement('div');
        toast.className = `notification-toast ${type}`;
        toast.innerHTML = `
            <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ========== 8. HOVER PARALLAX EFFECT ON CARDS ==========
    const cards = document.querySelectorAll('.morpho-card, .step, .expert-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });

    // ========== 9. SMOOTH SCROLL FOR ALL ANCHOR LINKS ==========
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // ========== 10. TYPING ANIMATION FOR HERO (Opsional) ==========
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle && !heroTitle.hasAttribute('data-typed')) {
        heroTitle.setAttribute('data-typed', 'true');
        // Biarkan statis, animasi sudah dari fade
    }

    // ========== 11. NAVBAR MOBILE MENU (Jika ada) ==========
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.querySelector('.nav-links');
    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('show');
            this.classList.toggle('active');
        });
    }

    // ========== 12. BACK TO TOP BUTTON ==========
    const backToTop = document.createElement('button');
    backToTop.className = 'back-to-top';
    backToTop.innerHTML = '<i class="fa-solid fa-arrow-up"></i>';
    document.body.appendChild(backToTop);
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 500) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });
    
    backToTop.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ========== 13. MEASURE BODY INTEGRATION ==========
    const measureForm = document.getElementById('measureBodyForm');
    const demoBtn = document.getElementById('measureDemo');
    const statusEl = document.getElementById('measureBodyStatus');
    const resultBox = document.getElementById('measureBodyResult');

    if (measureForm && statusEl && resultBox) {
        const bustInput = document.getElementById('bustInput');
        const waistInput = document.getElementById('waistInput');
        const hipInput = document.getElementById('hipInput');
        const submitBtn = document.getElementById('measureSubmit');

        const morphotypeEl = document.getElementById('resultMorphotype');
        const focusEl = document.getElementById('resultFocus');
        const bRatioEl = document.getElementById('resultBRatio');
        const hRatioEl = document.getElementById('resultHRatio');
        const topsEl = document.getElementById('resultTops');
        const bottomsEl = document.getElementById('resultBottoms');
        const avoidEl = document.getElementById('resultAvoid');

        function renderList(container, items) {
            if (!container) {
                return;
            }

            container.innerHTML = '';
            (items || []).forEach((item) => {
                const li = document.createElement('li');
                li.textContent = item;
                container.appendChild(li);
            });
        }

        function parseInput(input) {
            return Number.parseFloat(input?.value || '');
        }

        async function submitMeasureBody(event) {
            event.preventDefault();

            const payload = {
                bust: parseInput(bustInput),
                waist: parseInput(waistInput),
                hip: parseInput(hipInput),
            };

            if (Number.isNaN(payload.bust) || Number.isNaN(payload.waist) || Number.isNaN(payload.hip)) {
                statusEl.textContent = 'Please fill bust, waist, and hip with valid numbers.';
                resultBox.classList.add('hidden');
                return;
            }

            statusEl.textContent = 'Analyzing your body ratio...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('/api/recommendations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await response.json();

                if (!response.ok || !json?.data?.classification) {
                    const errorMessage = json?.message || 'Unable to process measurement right now.';
                    throw new Error(errorMessage);
                }

                const classification = json.data.classification;
                const recommendation = json.data.recommendation?.recommendations || {};

                morphotypeEl.textContent = classification.label || classification.morphotype || '-';
                focusEl.textContent = recommendation.focus || 'No recommendation focus available yet.';
                bRatioEl.textContent = classification.ratios?.bust_to_waist ?? '-';
                hRatioEl.textContent = classification.ratios?.hip_to_waist ?? '-';

                renderList(topsEl, recommendation.tops);
                renderList(bottomsEl, recommendation.bottoms);
                renderList(avoidEl, recommendation.avoid);

                resultBox.classList.remove('hidden');
                statusEl.textContent = 'Analysis complete.';
            } catch (error) {
                statusEl.textContent = error.message || 'Request failed. Please try again.';
                resultBox.classList.add('hidden');
            } finally {
                submitBtn.disabled = false;
            }
        }

        measureForm.addEventListener('submit', submitMeasureBody);

        if (demoBtn) {
            demoBtn.addEventListener('click', function() {
                if (bustInput) {
                    bustInput.value = '92';
                }
                if (waistInput) {
                    waistInput.value = '72';
                }
                if (hipInput) {
                    hipInput.value = '98';
                }
                statusEl.textContent = 'Demo values loaded. Click Analyze My Morphotype.';
            });
        }
    }

});