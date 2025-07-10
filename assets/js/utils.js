
export function initOfertasSwiper() {
  if (typeof Swiper !== 'undefined') {
    new Swiper('.ofertas-swiper', {
      loop: true,
      autoplay: { delay: 3500, disableOnInteraction: false },
      pagination: { el: '.swiper-pagination', clickable: true },
      navigation: false,
      slidesPerView: 1,
      spaceBetween: 0,
      grabCursor: true,
      effect: 'slide',
      breakpoints: {
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }
    });
  }
} 