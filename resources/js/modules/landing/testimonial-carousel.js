// resources/js/modules/landing/testimonial-carousel.js
// Logic dasar untuk dots indicator testimoni (bisa dikembangkan jadi slider penuh)
document.querySelectorAll('[data-carousel-dots] span').forEach((dot, index) => {
    dot.addEventListener('click', () => {
        document.querySelectorAll('[data-carousel-dots] span').forEach((d) => {
            d.classList.remove('bg-[#F5821F]');
            d.classList.add('bg-gray-300');
        });
        dot.classList.remove('bg-gray-300');
        dot.classList.add('bg-[#F5821F]');
        // TODO: geser tampilan card testimoni sesuai index terpilih
    });
});
