<?php
include_once './includes/head.php';
include_once './includes_client/header.php';
include_once './conexion/cone.php';
include_once './utils/queries.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Producto no válido.');
}

$producto = obtenerProductoPorId($conn, $id);
if (!$producto) {
    die('Producto no encontrado o no está en oferta.');
}

$tallas_disponibles = obtenerTallasPorCatalogoId($conn, $id);
?>
<!DOCTYPE html>
<html lang="es">

<body class="bg-gray-100 min-h-screen">
    <main class="container mx-auto py-10 px-4 flex flex-col md:flex-row items-center md:items-start gap-0">
        <div class="w-full md:w-1/2 flex flex-col items-center">
            <?php
            $imagenes = obtenerImagenesPorProductoId($conn, $producto['producto_id']);
            ?>
            <?php if (count($imagenes) > 0): ?>
                <div class="mb-4">
                    <img id="imgPrincipal" src="<?php echo htmlspecialchars($imagenes[0]['url_imagen']); ?>"
                        alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                        class="rounded-lg shadow-lg max-w-xs md:max-w-md w-full object-cover aspect-square bg-white border border-gray-200" />
                </div>
                <?php if (count($imagenes) > 1): ?>
                    <div class="mb-2 w-full max-w-md">
                        <div class="text-sm font-semibold text-gray-700 mb-1">Galería de imágenes</div>
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 p-2 rounded bg-gray-100">
                            <?php foreach ($imagenes as $idx => $img): ?>
                                <img src="<?php echo htmlspecialchars($img['url_imagen']); ?>"
                                    alt="Miniatura <?php echo $idx+1; ?>"
                                    class="w-16 h-16 object-cover rounded border border-gray-300 shadow-sm cursor-pointer hover:scale-105 transition-transform duration-150 bg-white"
                                    onclick="document.getElementById('imgPrincipal').src=this.src" />
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <img src="<?php echo htmlspecialchars($producto['url_imagen']); ?>"
                    alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                    class="rounded-lg shadow-lg max-w-xs md:max-w-md w-full object-cover aspect-square bg-white" />
            <?php endif; ?>
        </div>
        <div class="hidden md:block w-px h-[420px] bg-gray-200 mx-2"></div>
        <div class="w-full md:w-1/2 flex flex-col items-start md:pl-0 md:pr-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2 uppercase">
                <?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
            <div class="flex items-center gap-2 mb-2 text-base text-gray-500 font-medium">
                <span>Categoría:</span>
                <span
                    class="text-gray-800 font-semibold"><?php echo htmlspecialchars($producto['nombre_categoria'] ?? '-'); ?></span>
                <?php if (!empty($producto['nombre_subcategoria'])) { ?>
                    <span>/</span>
                    <span
                        class="text-gray-800 font-semibold"><?php echo htmlspecialchars($producto['nombre_subcategoria']); ?></span>
                <?php } ?>
            </div>
            <div class="mb-4 flex items-end gap-3">
                <?php if (!empty($producto['oferta']) && $producto['oferta'] > 0): ?>
                    <span class="text-lg text-gray-400 line-through">S/
                        <?= number_format($producto['precio_venta'], 2); ?></span>
                    <span class="text-2xl font-extrabold text-yellow-400">S/
                        <?= number_format($producto['precio_con_descuento'], 2); ?></span>
                    <span class="text-green-500 font-semibold text-base">
                        -<?= htmlspecialchars($producto['oferta']); ?>% OFF
                    </span>
                <?php else: ?>
                    <span class="text-2xl font-extrabold text-gray-900">S/
                        <?= number_format($producto['precio_venta'], 2); ?></span>
                <?php endif; ?>
            </div>
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-1">Descripción</h2>
                <p class="text-gray-700 leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($producto['descripcion_producto'])); ?></p>
            </div>
            <form id="formCarrito" class="w-full max-w-sm" method="post" action="#" onsubmit="return false;">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
                    <div class="flex gap-2">
                        <?php if (count($tallas_disponibles) > 0) { ?>
                            <?php foreach ($tallas_disponibles as $talla) { ?>
                                <button type="button"
                                    class="talla-btn px-4 py-2 border border-gray-300 rounded bg-white text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    data-talla="<?php echo htmlspecialchars($talla); ?>"><?php echo htmlspecialchars($talla); ?></button>
                            <?php } ?>
                        <?php } else { ?>
                            <span class="text-red-600 font-semibold">Sin stock</span>
                        <?php } ?>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="cambiarCantidad(-1)"
                            class="w-10 h-10 flex items-center justify-center rounded bg-gray-200 hover:bg-gray-300 text-xl font-bold">-</button>
                        <input type="number" id="cantidad" name="cantidad" value="1" min="1"
                            class="w-16 text-center border border-gray-300 rounded py-2" />
                        <button type="button" onclick="cambiarCantidad(1)"
                            class="w-10 h-10 flex items-center justify-center rounded bg-gray-200 hover:bg-gray-300 text-xl font-bold">+</button>
                    </div>
                    <div id="stockMsg" class="text-sm mt-1"></div>
                </div>
                <button id="btnCarrito" type="submit"
                    class="w-full bg-gray-300 text-gray-400 font-bold py-3 rounded transition text-lg flex items-center justify-center gap-2 cursor-not-allowed"
                    disabled>
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m1-9h2a2 2 0 002-2V7a2 2 0 00-2-2h-5.4">
                        </path>
                    </svg>
                    Añadir al carrito
                </button>
            </form>
            <div id="msgCarrito" class="hidden mt-3 text-green-600 font-semibold"></div>
        </div>
    </main>
    <?php include_once './includes/footer.php'; ?>
    <script>
        window.catalogoId = <?php echo (int) $producto['id']; ?>;
    </script>
    <script src="assets/js/ver_producto.js"></script>
</body>

</html>