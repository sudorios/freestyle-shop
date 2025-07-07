<?php
include_once 'includes/head.php';
include_once 'includes_client/header.php';
?>

<!-- SwiperJS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<body class="bg-gray-100 min-h-screen">
    <main class="pt-24 container mx-auto px-0 md:px-4">
        <!-- Título de Ofertas -->
        <h2 class="text-3xl md:text-4xl font-black uppercase tracking-wider text-center mb-8 text-black">Nuestras Ofertas</h2>
        <!-- Carrusel de Ofertas por sección -->
        <section class="mb-12">
            <div class="swiper ofertas-swiper rounded-lg shadow-lg overflow-hidden">
                <div class="swiper-wrapper">
                    <!-- Slide 1: POLO -->
                    <div class="swiper-slide relative">
                        <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80" alt="Polo" class="w-full h-[400px] object-cover" />
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center">
                            <h3 class="text-4xl md:text-5xl font-black uppercase text-white mb-4 drop-shadow">Poleras</h3>
                            <a href="#" class="bg-pink-600 hover:bg-pink-700 text-white font-bold px-8 py-3 rounded-full text-lg uppercase shadow-lg transition">Ver ofertas</a>
                        </div>
                    </div>
                    <!-- Slide 2: PANTALONES -->
                    <div class="swiper-slide relative">
                        <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=900&q=80" alt="Pantalones" class="w-full h-[400px] object-cover" />
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center">
                            <h3 class="text-4xl md:text-5xl font-black uppercase text-white mb-4 drop-shadow">Pantalones</h3>
                            <a href="#" class="bg-pink-600 hover:bg-pink-700 text-white font-bold px-8 py-3 rounded-full text-lg uppercase shadow-lg transition">Ver ofertas</a>
                        </div>
                    </div>
                    <!-- Slide 3: ZAPATILLAS -->
                    <div class="swiper-slide relative">
                        <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=900&q=80" alt="Zapatillas" class="w-full h-[400px] object-cover" />
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center">
                            <h3 class="text-4xl md:text-5xl font-black uppercase text-white mb-4 drop-shadow">Zapatillas</h3>
                            <a href="#" class="bg-pink-600 hover:bg-pink-700 text-white font-bold px-8 py-3 rounded-full text-lg uppercase shadow-lg transition">Ver ofertas</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination mt-2"></div>
            </div>
        </section>
        <h1 class="text-3xl font-bold mb-6">Bienvenido a Freestyle Shop</h1>
        <p class="text-lg text-gray-700">Explora la mejor moda urbana y encuentra tu estilo.</p>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
      const swiper = new Swiper('.ofertas-swiper', {
        loop: true,
        autoplay: { delay: 3500, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: false,
        slidesPerView: 1,
        spaceBetween: 0,
        grabCursor: true,
        effect: 'slide',
      });
    </script>
</body>

</html>
