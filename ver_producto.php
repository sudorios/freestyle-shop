<?php
include_once './includes/head.php';
include_once './includes_client/header.php';
include_once './conexion/cone.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Producto no válido.');
}

$sql = "SELECT 
    cp.id,
    p.nombre_producto,
    p.descripcion_producto,
    p.talla_producto,
    c.nombre_categoria,
    s.nombre_subcategoria,
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
LEFT JOIN 
    subcategoria s ON p.id_subcategoria = s.id_subcategoria
LEFT JOIN 
    categoria c ON s.id_categoria = c.id_categoria
WHERE
    cp.sucursal_id = 7
    AND (cp.estado = true OR cp.estado = 't')
    AND cp.id = $1
ORDER BY 
    cp.id ASC
LIMIT 1;";

$result = pg_query_params($conn, $sql, [$id]);
$producto = pg_fetch_assoc($result);
if (!$producto) {
    die('Producto no encontrado o no está en oferta.');
}

$sql_tallas = "SELECT DISTINCT p.talla_producto  
FROM catalogo_productos cp
JOIN producto p ON cp.producto_id = p.id_producto
JOIN inventario_sucursal isuc ON p.id_producto = isuc.id_producto
WHERE cp.sucursal_id = 7  
  AND isuc.cantidad > 0  
  AND cp.id = $1
ORDER BY p.talla_producto ASC;";
$res_tallas = pg_query_params($conn, $sql_tallas, [$id]);
$tallas_disponibles = [];
if ($res_tallas) {
    while ($row = pg_fetch_assoc($res_tallas)) {
        if (!empty($row['talla_producto'])) {
            $tallas_disponibles[] = $row['talla_producto'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<body class="bg-gray-100 min-h-screen">
    <main class="container mx-auto py-10 px-4 flex flex-col md:flex-row items-center md:items-start gap-0">
        <div class="w-full md:w-1/2 flex justify-center">
            <img src="<?php echo htmlspecialchars($producto['url_imagen']); ?>"
                alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                class="rounded-lg shadow-lg max-w-xs md:max-w-md w-full object-cover aspect-square bg-white" />
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
        const catalogoId = <?php echo (int) $producto['id']; ?>;
        let stockDisponible = 0;
        let tallaSeleccionada = null;

        function actualizarBtnCarrito(habilitar) {
            const btn = document.getElementById('btnCarrito');
            if (habilitar) {
                btn.disabled = false;
                btn.classList.remove('bg-gray-300', 'text-gray-400', 'cursor-not-allowed');
                btn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'cursor-pointer');
            } else {
                btn.disabled = true;
                btn.classList.add('bg-gray-300', 'text-gray-400', 'cursor-not-allowed');
                btn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'cursor-pointer');
            }
        }
        actualizarBtnCarrito(false);

        function actualizarStock(talla) {
            fetch(`utils/obtener_stock.php?catalogo_id=${catalogoId}&talla=${encodeURIComponent(talla)}`)
                .then(res => res.json())
                .then(data => {
                    const cantidadInput = document.getElementById('cantidad');
                    const btnCarrito = document.getElementById('btnCarrito');
                    const stockMsg = document.getElementById('stockMsg');
                    if (data.success && data.cantidad > 0) {
                        stockDisponible = data.cantidad;
                        cantidadInput.max = stockDisponible;
                        if (parseInt(cantidadInput.value) > stockDisponible) {
                            cantidadInput.value = stockDisponible;
                        }
                        cantidadInput.disabled = false;
                        actualizarBtnCarrito(true);
                        btnCarrito.textContent = 'Añadir al carrito';
                        stockMsg.textContent = `Stock disponible: ${stockDisponible}`;
                        stockMsg.className = 'text-sm text-green-600 mt-1';
                    } else {
                        stockDisponible = 0;
                        cantidadInput.value = 1;
                        cantidadInput.max = 1;
                        cantidadInput.disabled = true;
                        actualizarBtnCarrito(false);
                        btnCarrito.textContent = 'Sin stock';
                        stockMsg.textContent = 'Sin stock para esta talla';
                        stockMsg.className = 'text-sm text-red-600 mt-1';
                    }
                });
        }

        document.querySelectorAll('.talla-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.talla-btn').forEach(b => b.classList.remove('bg-blue-600'));
                this.classList.add('bg-blue-600');
                tallaSeleccionada = this.getAttribute('data-talla');
                actualizarStock(tallaSeleccionada);
            });
        });

        function cambiarCantidad(delta) {
            const input = document.getElementById('cantidad');
            let val = parseInt(input.value) || 1;
            val += delta;
            if (val < 1) val = 1;
            if (stockDisponible > 0 && val > stockDisponible) val = stockDisponible;
            input.value = val;
        }

        document.getElementById('formCarrito').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!tallaSeleccionada || stockDisponible < 1) {
                return;
            }
            const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
            fetch('views/carrito/carrito_registrar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `catalogo_id=${encodeURIComponent(catalogoId)}&talla=${encodeURIComponent(tallaSeleccionada)}&cantidad=${encodeURIComponent(cantidad)}`
            })
                .then(res => res.json())
                .then(data => {
                    const msg = document.getElementById('msgCarrito');
                    if (data.success) {
                        msg.textContent = '¡Producto agregado al carrito exitosamente!';
                        msg.classList.remove('hidden', 'text-red-600');
                        msg.classList.add('text-green-600');
                        if (typeof actualizarContadorCarritoAjax === 'function') actualizarContadorCarritoAjax();
                    } else {
                        msg.textContent = data.error || 'Error al añadir al carrito';
                        msg.classList.remove('hidden', 'text-green-600');
                        msg.classList.add('text-red-600');
                    }
                    setTimeout(() => { msg.classList.add('hidden'); }, 3000);
                })
                .catch(() => {
                    const msg = document.getElementById('msgCarrito');
                    msg.textContent = 'Error de conexión con el servidor';
                    msg.classList.remove('hidden', 'text-green-600');
                    msg.classList.add('text-red-600');
                    setTimeout(() => { msg.classList.add('hidden'); }, 2000);
                });
        });
    </script>
</body>

</html>