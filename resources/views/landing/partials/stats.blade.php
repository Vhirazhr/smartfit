<section class="stats-section">
    <div class="stats-container">
        <div class="stat-item">
            <div class="stat-number" data-count="5234">0</div>
            <div class="stat-label">Happy Customers</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-count="1247">0</div>
            <div class="stat-label">Outfit Styles</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-count="96">0</div>
            <div class="stat-label">Accuracy Rate</div>
            <div class="stat-label">%</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-count="15">0</div>
            <div class="stat-label">Expert Stylists</div>
        </div>
    </div>
</section>

@push('scripts')
<script>
const counters = document.querySelectorAll('.stat-number');
const speed = 200;

const animateCounter = () => {
    counters.forEach(counter => {
        const updateCount = () => {
            const target = parseInt(counter.getAttribute('data-count'));
            const count = parseInt(counter.innerText);
            const increment = target / speed;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 20);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        updateCount();
    });
};

const statsSection = document.querySelector('.stats-section');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounter();
            observer.unobserve(entry.target);
        }
    });
});
observer.observe(statsSection);
</script>
@endpush