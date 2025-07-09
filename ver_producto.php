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
    AND (cp.estado_oferta = true OR cp.estado_oferta = 't')
    AND cp.id = $1
ORDER BY 
    cp.id ASC
LIMIT 1;";

$result = pg_query_params($conn, $sql, [$id]);
$producto = pg_fetch_assoc($result);
if (!$producto) {
    die('Producto no encontrado o no está en oferta.');
}
?>
<!DOCTYPE html>
<html lang="es">
<body class="bg-gray-100 min-h-screen">
    <main class="container mx-auto py-10 px-4 flex flex-col md:flex-row items-center md:items-start gap-0">
        <div class="w-full md:w-1/2 flex justify-center">
            <img src="<?php echo htmlspecialchars($producto['url_imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="rounded-lg shadow-lg max-w-xs md:max-w-md w-full object-cover aspect-square bg-white" />
        </div>
        <div class="hidden md:block w-px h-[420px] bg-gray-200 mx-2"></div>
        <div class="w-full md:w-1/2 flex flex-col items-start md:pl-0 md:pr-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2 uppercase"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
            <div class="flex items-center gap-2 mb-2 text-base text-gray-500 font-medium">
                <span>Categoría:</span>
                <span class="text-gray-800 font-semibold"><?php echo htmlspecialchars($producto['nombre_categoria'] ?? '-'); ?></span>
                <?php if (!empty($producto['nombre_subcategoria'])) { ?>
                    <span>/</span>
                    <span class="text-gray-800 font-semibold"><?php echo htmlspecialchars($producto['nombre_subcategoria']); ?></span>
                <?php } ?>
            </div>
            <div class="mb-4 flex items-end gap-3">
                <span class="text-lg text-gray-400 line-through">S/ <?php echo number_format($producto['precio_venta'], 2); ?></span>
                <span class="text-2xl font-extrabold text-yellow-400">S/ <?php echo number_format($producto['precio_con_descuento'], 2); ?></span>
                <span class="text-green-500 font-semibold text-base">-<?php echo htmlspecialchars($producto['oferta']); ?>% OFF</span>
            </div>
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-1">Descripción</h2>
                <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($producto['descripcion_producto'])); ?></p>
            </div>
            <form id="formCarrito" class="w-full max-w-sm" method="post" action="#" onsubmit="return false;">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
                    <div class="flex gap-2">
                        <?php foreach (["S","M","L","XL"] as $talla) { ?>
                            <button type="button" class="talla-btn px-4 py-2 border border-gray-300 rounded bg-white text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-400" data-talla="<?php echo $talla; ?>"><?php echo $talla; ?></button>
                        <?php } ?>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="cambiarCantidad(-1)" class="w-10 h-10 flex items-center justify-center rounded bg-gray-200 hover:bg-gray-300 text-xl font-bold">-</button>
                        <input type="number" id="cantidad" name="cantidad" value="1" min="1" class="w-16 text-center border border-gray-300 rounded py-2" />
                        <button type="button" onclick="cambiarCantidad(1)" class="w-10 h-10 flex items-center justify-center rounded bg-gray-200 hover:bg-gray-300 text-xl font-bold">+</button>
                    </div>
                    <div id="stockMsg" class="text-sm mt-1"></div>
                </div>
                <button id="btnCarrito" type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded transition text-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m1-9h2a2 2 0 002-2V7a2 2 0 00-2-2h-5.4"></path></svg>
                    Añadir al carrito
                </button>
            </form>
            <div id="msgCarrito" class="hidden mt-3 text-green-600 font-semibold"></div>
        </div>
    </main>
    <?php include_once './includes/footer.php'; ?>
    <script>
        // Lógica para seleccionar talla (solo visual, puedes adaptar a tu backend)
        const catalogoId = <?php echo (int)$producto['id']; ?>;
        let stockDisponible = 0;
        let tallaSeleccionada = null;

        function actualizarStock(talla) {
            fetch(`obtener_stock.php?catalogo_id=${catalogoId}&talla=${encodeURIComponent(talla)}`)
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
                        btnCarrito.disabled = false;
                        btnCarrito.textContent = 'Añadir al carrito';
                        stockMsg.textContent = `Stock disponible: ${stockDisponible}`;
                        stockMsg.className = 'text-sm text-green-600 mt-1';
                    } else {
                        stockDisponible = 0;
                        cantidadInput.value = 1;
                        cantidadInput.max = 1;
                        cantidadInput.disabled = true;
                        btnCarrito.disabled = true;
                        btnCarrito.textContent = 'Sin stock';
                        stockMsg.textContent = 'Sin stock para esta talla';
                        stockMsg.className = 'text-sm text-red-600 mt-1';
                    }
                });
        }

        document.querySelectorAll('.talla-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.talla-btn').forEach(b => b.classList.remove('bg-blue-600'));
                this.classList.add('bg-blue-600');
                tallaSeleccionada = this.getAttribute('data-talla');
                actualizarStock(tallaSeleccionada);
            });
        });

        // Lógica para cantidad
        function cambiarCantidad(delta) {
            const input = document.getElementById('cantidad');
            let val = parseInt(input.value) || 1;
            val += delta;
            if (val < 1) val = 1;
            if (stockDisponible > 0 && val > stockDisponible) val = stockDisponible;
            input.value = val;
        }

        // Lógica para añadir al carrito (solo frontend)
        document.getElementById('formCarrito').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!tallaSeleccionada || stockDisponible < 1) {
                return;
            }
            const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
            const producto = {
                id: catalogoId,
                talla: tallaSeleccionada,
                cantidad: cantidad
            };
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            // Buscar si ya existe ese producto+talla
            const idx = carrito.findIndex(item => item.id === producto.id && item.talla === producto.talla);
            if (idx >= 0) {
                // Sumar cantidad sin superar stock
                carrito[idx].cantidad = Math.min(carrito[idx].cantidad + cantidad, stockDisponible);
            } else {
                producto.cantidad = Math.min(cantidad, stockDisponible);
                carrito.push(producto);
            }
            localStorage.setItem('carrito', JSON.stringify(carrito));
            // Mensaje de éxito
            const msg = document.getElementById('msgCarrito');
            msg.textContent = '¡Producto añadido al carrito!';
            msg.classList.remove('hidden');
            setTimeout(() => { msg.classList.add('hidden'); }, 2000);
            if (typeof actualizarContadorCarrito === 'function') actualizarContadorCarrito();
        });
    </script>
</body>
</html> 