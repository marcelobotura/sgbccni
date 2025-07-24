// Caminho: frontend/assets/js/pages/public.js

document.addEventListener('DOMContentLoaded', () => {
  // 🌗 Alternância entre tema claro e escuro
  const temaToggle = document.getElementById('tema-toggle');
  if (temaToggle) {
    temaToggle.addEventListener('click', () => {
      const atual = document.body.getAttribute('data-bs-theme');
      const novo = atual === 'dark' ? 'light' : 'dark';
      document.body.setAttribute('data-bs-theme', novo);
      document.cookie = 'modo_tema=' + novo;
      temaToggle.classList.toggle('bi-sun-fill');
      temaToggle.classList.toggle('bi-moon-stars-fill');
    });
  }

  // 🧭 Inicialização dos carrosséis Swiper
  const swipers = document.querySelectorAll('.mySwiper');
  swipers.forEach(swiperContainer => {
    new Swiper(swiperContainer, {
      slidesPerView: 2,
      spaceBetween: 20,
      breakpoints: {
        576: { slidesPerView: 3 },
        768: { slidesPerView: 4 },
        992: { slidesPerView: 5 },
        1200: { slidesPerView: 6 }
      },
      loop: true,
      autoplay: { delay: 3500 },
    });
  });

  // 🔎 Foco automático no campo de busca, se presente
  const campoBusca = document.querySelector('.form-busca-home input[name="q"]');
  if (campoBusca) {
    campoBusca.focus();
  }
});
