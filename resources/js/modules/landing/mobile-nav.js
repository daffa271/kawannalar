// resources/js/modules/landing/mobile-nav.js
// Toggle menu mobile khusus navbar guest (landing/login/register)
const toggleBtn = document.getElementById('navMobileToggle');
const menu = document.getElementById('navMobileMenu');

if (toggleBtn && menu) {
    toggleBtn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
}
