<?php include_once 'includes/head.php'; ?>
<?php $esCliente = true; include_once 'includes/header.php'; ?>
<body class="bg-gray-100 min-h-screen">
    <main class="pt-4 container mx-auto px-0 ">
        <h2 class="text-3xl md:text-4xl font-black uppercase tracking-wider text-center mb-4 text-black">Nuestras Ofertas</h2>
        <section class="mb-4">
            <div class="swiper ofertas-swiper overflow-hidden">
                <div class="swiper-wrapper">
                    <?php if (!empty($ofertas)) { 
                        foreach ($ofertas as $oferta) { ?>
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
                                <a href="index.php?controller=producto&action=ver&id=<?php echo $oferta['id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded mb-2 text-center transition shadow-md" style="min-width: 140px; max-width: 200px;">Ver producto</a>
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
        <section class="mb-12">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-black uppercase tracking-wider mb-2 text-black">Nuestros Productos</h2>
                <h3 class="text-lg md:text-xl font-semibold text-gray-600">Pantalón</h3>
                <div class="h-1 bg-black rounded-full mt-4 mx-auto w-24"></div>
            </div>
            <?php
            if (!empty($productos)) {
                echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">';
                foreach ($productos as $prod) {
                    echo '<div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">';
                    echo '<img src="' . htmlspecialchars($prod['url_imagen']) . '" alt="' . htmlspecialchars($prod['nombre_producto']) . '" class="w-full aspect-square object-cover mb-2 rounded">';
                    echo '<h3 class="font-bold text-lg text-center mb-1">' . htmlspecialchars($prod['nombre_producto']) . '</h3>';
                    echo '<div class="text-yellow-600 font-bold text-xl mb-2">S/ ' . number_format($prod['precio_venta'], 2) . '</div>';
                    echo '<a href="index.php?controller=producto&action=ver&id=' . $prod['id'] . '" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded transition">Ver producto</a>';
                    echo '</div>';
                }
                echo '</div>';
                echo '<div class="text-center mt-8">';
                echo '<a href="index.php?controller=categoria&action=ver&id_categoria=2" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-lg">Ver Todos los Productos</a>';
                echo '</div>';
            } else {
                echo '<div class="text-center py-8">';
                echo '<span class="text-gray-500 text-xl">No hay productos disponibles actualmente.</span>';
                echo '</div>';
            }
            ?>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module">
      import { initOfertasSwiper } from './assets/js/utils.js';
      document.addEventListener('DOMContentLoaded', () => {
        initOfertasSwiper();
      });
    </script>
    <?php include_once 'includes/footer.php'; ?>
</body>
</html> 