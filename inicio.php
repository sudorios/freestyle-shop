<?php
include_once 'includes/head.php';
include_once 'includes_client/header.php';
include_once './conexion/cone.php';

// Consulta productos en oferta
$sql_ofertas = "SELECT 
    cp.id,
    p.nombre_producto,
    ip.url_imagen,
    i.precio_venta,
    cp.limite_oferta,
    cp.oferta,
    (i.precio_venta * (1 - (cp.oferta / 100))) AS precio_con_descuento
FROM 
    catalogo_productos cp
JOIN 
    producto p ON cp.producto_id = p.id_producto
JOIN 
    ingreso i ON cp.ingreso_id = i.id
JOIN 
    imagenes_producto ip ON cp.imagen_id = ip.id
WHERE
    cp.sucursal_id = 7
    AND (cp.estado = true OR cp.estado = 't')
    AND (cp.estado_oferta = true OR cp.estado_oferta = 't')
ORDER BY 
    cp.id ASC;";
$result_ofertas = pg_query($conn, $sql_ofertas);
?>
<!-- SwiperJS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<body class="bg-gray-100 min-h-screen">
    <main class="pt-4 container mx-auto px-0 ">
        <h2 class="text-3xl md:text-4xl font-black uppercase tracking-wider text-center mb-4 text-black">Nuestras Ofertas</h2>
        <!-- Carrusel de Ofertas dinámico -->
        <section class="mb-12">
            <div class="swiper ofertas-swiper overflow-hidden">
                <div class="swiper-wrapper">
                    <?php if ($result_ofertas && pg_num_rows($result_ofertas) > 0) { 
                        while ($oferta = pg_fetch_assoc($result_ofertas)) { ?>
                        <div class="swiper-slide relative group">
                            
                            <img src="<?php echo htmlspecialchars($oferta['url_imagen']); ?>" alt="<?php echo htmlspecialchars($oferta['nombre_producto']); ?>" class="w-full aspect-square object-cover select-none" />
                            <div class="absolute inset-0 bg-black opacity-75 z-10"></div>
                            <div class="absolute inset-0 flex flex-col justify-center items-center z-20">
                                <h3 class="text-lg md:text-xl font-black uppercase text-white mb-1 text-center drop-shadow-lg"><?php echo htmlspecialchars($oferta['nombre_producto']); ?></h3>
                                <div class="mb-2 flex flex-col items-center">
                                    <span class="text-sm text-gray-200 line-through">S/ <?php echo number_format($oferta['precio_venta'], 2); ?></span>
                                    <span class="text-2xl text-yellow-400 font-bold">S/ <?php echo number_format($oferta['precio_con_descuento'], 2); ?></span>
                                    <span class="text-xs text-green-400 font-semibold">-<?php echo htmlspecialchars($oferta['oferta']); ?>%</span>
                                </div>
                                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded mb-2 text-center transition shadow-md" style="min-width: 140px; max-width: 200px;">Ver producto</a>
                                <div class="text-xs text-gray-200">Válido hasta: <?php echo date('d/m/Y', strtotime($oferta['limite_oferta'])); ?></div>
                            </div>
                        </div>
                    <?php } 
                    } else { ?>
                        <div class="swiper-slide flex items-center justify-center h-[400px] bg-gray-200">
                            <span class="text-gray-500 text-xl">No hay productos en oferta actualmente.</span>
                        </div>
                    <?php } ?>
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
        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
      });
    </script>
</body>

</html>
