<?php
session_start();
require_once './conexion/cone.php';
include_once './includes/head.php';
include_once './includes_client/header.php';

// Mostrar solo el mensaje si hay success o error
$mostrar_formulario = true;
if (isset($_GET['success']) || isset($_GET['error'])) {
    $mostrar_formulario = false;
}

// Obtener los IDs de los items seleccionados por GET
$ids = isset($_GET['items']) ? explode(',', $_GET['items']) : [];
$ids = array_filter(array_map('intval', $ids));

if (empty($ids) && $mostrar_formulario) {
    echo '<div class="text-center mt-10 text-xl">No hay productos seleccionados para el checkout.</div>';
    include_once './includes/footer.php';
    exit;
}

// Consulta mejorada: incluye imagen y talla
$placeholders = implode(',', array_map(function($i) { static $c=1; return '$'.($c++); }, $ids));
$sql = "SELECT ci.id, ci.producto_id, ci.cantidad, ci.precio_unitario, ci.talla, p.nombre_producto, ip.url_imagen
        FROM carrito_items ci
        JOIN producto p ON ci.producto_id = p.id_producto
        LEFT JOIN catalogo_productos cp ON cp.producto_id = p.id_producto
        LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id
        WHERE ci.id IN ($placeholders) AND ci.estado = 'activo'";
$res = $ids ? pg_query_params($conn, $sql, $ids) : false;
$carrito = [];
$total = 0;
if ($res) {
    while ($item = pg_fetch_assoc($res)) {
        $carrito[] = $item;
        $total += $item['cantidad'] * $item['precio_unitario'];
    }
}
$envio = $total >= 99 ? 0 : 15;
$total_final = $total + $envio;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Finalizar compra</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-xl mx-auto bg-white p-8 mt-10 rounded shadow">
        <?php
        if (isset($_GET['success'])) {
            echo '<div class="mb-4 p-3 bg-green-100 text-green-800 rounded font-semibold text-center">' . htmlspecialchars($_GET['msg'] ?? '¡Pedido registrado correctamente!') . '</div>';
        } elseif (isset($_GET['error'])) {
            echo '<div class="mb-4 p-3 bg-red-100 text-red-800 rounded font-semibold text-center">' . htmlspecialchars($_GET['msg'] ?? 'Ocurrió un error al registrar el pedido.') . '</div>';
        }
        ?>
        <?php if ($mostrar_formulario): ?>
        <h1 class="text-2xl font-bold mb-6">Finalizar compra</h1>
        <form id="form-pedido" method="POST" action="views/pedidos/registrar_pedido.php">
            <div class="mb-4">
                <label for="direccion_envio" class="block font-semibold mb-1">Dirección de envío</label>
                <input type="text" name="direccion_envio" id="direccion_envio" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="metodo_pago" class="block font-semibold mb-1">Método de pago</label>
                <select name="metodo_pago" id="metodo_pago" class="w-full border p-2 rounded" required>
                    <option value="">Selecciona método de pago</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="efectivo">Efectivo</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="observaciones" class="block font-semibold mb-1">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="w-full border p-2 rounded"></textarea>
            </div>
            <div class="mb-6">
                <h2 class="font-bold mb-2">Resumen del carrito</h2>
                <div class="divide-y">
                <?php foreach ($carrito as $item): ?>
                    <div class="flex items-center py-3 gap-4">
                        <div class="w-16 h-16 flex-shrink-0 bg-gray-100 flex items-center justify-center rounded">
                            <img src="<?= htmlspecialchars($item['url_imagen'] ?? 'https://via.placeholder.com/64x64?text=IMG') ?>" alt="img" class="object-contain w-full h-full rounded" />
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900"><?= htmlspecialchars($item['nombre_producto']) ?></div>
                            <?php if (!empty($item['talla'])): ?>
                                <div class="text-xs text-gray-500">Talla: <?= htmlspecialchars($item['talla']) ?></div>
                            <?php endif; ?>
                            <div class="text-xs text-gray-500">Precio unitario: S/ <?= number_format($item['precio_unitario'], 2) ?></div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">x<?= $item['cantidad'] ?></div>
                            <div class="text-sm text-gray-700">S/ <?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?></div>
                        </div>
                        <!-- Input oculto para enviar los datos de los productos -->
                        <input type="hidden" name="productos[]" value='<?= json_encode([
                            "id_producto" => $item["producto_id"],
                            "cantidad" => $item["cantidad"],
                            "precio_unitario" => $item["precio_unitario"],
                            "id_carrito_item" => $item["id"]
                        ]) ?>'>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="flex justify-between mt-4 text-lg font-bold">
                    <span>Subtotal</span>
                    <span>S/ <?= number_format($total, 2) ?></span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span>Envío</span>
                    <span><?= $envio == 0 ? 'Gratis' : 'S/ 15.00' ?></span>
                </div>
                <div class="flex justify-between mt-2 text-xl font-bold border-t pt-2">
                    <span>Total</span>
                    <span>S/ <?= number_format($total_final, 2) ?></span>
                </div>
                <div class="text-xs text-gray-400 mb-2">(IGV incluido)</div>
            </div>
            <input type="hidden" name="total" value="<?= $total_final ?>">
            <button type="submit" class="w-full bg-black hover:bg-gray-900 text-white font-bold py-3 rounded text-lg flex items-center justify-center gap-2 mb-2 cursor-pointer">CONFIRMAR PEDIDO <span class="ml-2">→</span></button>
        </form>
        <?php endif; ?>
        <div id="mensaje-resultado" class="mt-4 text-center"></div>
    </div>
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 