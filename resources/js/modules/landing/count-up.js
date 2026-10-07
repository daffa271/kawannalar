// resources/js/modules/landing/count-up.js
// Angka statistik landing bergerak cepat dari 1 ke nilai akhir saat terlihat di layar.
// Markup: <span data-count-to="2345" data-count-suffix="+">2.345+</span>
// HTML sudah berisi nilai akhir (aman untuk SEO, tanpa JS, dan prefers-reduced-motion).
const counters = document.querySelectorAll('[data-count-to]');
const formatter = new Intl.NumberFormat('id-ID');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const DURATION = 1600;

const render = (el, value) => {
    el.textContent = formatter.format(value) + (el.dataset.countSuffix ?? '');
};

const animate = (el) => {
    const target = Number(el.dataset.countTo);
    const start = performance.now();

    const step = (now) => {
        const progress = Math.min(1, (now - start) / DURATION);
        const eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic: cepat di awal, halus di akhir
        render(el, Math.max(1, Math.round(eased * target)));
        if (progress < 1) requestAnimationFrame(step);
    };

    requestAnimationFrame(step);
};

if (counters.length && !reduceMotion && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            observer.unobserve(entry.target);
            animate(entry.target);
        });
    }, { threshold: 0.4 });

    counters.forEach((el) => {
        render(el, 1);
        observer.observe(el);
    });
}
