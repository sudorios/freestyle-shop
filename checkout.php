<?php
session_start();
require_once './conexion/cone.php';
require_once './utils/queries.php';
include_once './includes/head.php';
include_once './includes_client/header.php';

$mostrar_formulario = true;
if (isset($_GET['success']) || isset($_GET['error'])) {
    $mostrar_formulario = false;
}

$ids = isset($_GET['items']) ? explode(',', $_GET['items']) : [];
$ids = array_filter(array_map('intval', $ids));

if (empty($ids) && $mostrar_formulario) {
    echo '<div class="text-center mt-10 text-xl">No hay productos seleccionados para el checkout.</div>';
    include_once './includes/footer.php';
    exit;
}

$placeholders = implode(',', array_map(function($i) { static $c=1; return '$'.($c++); }, $ids));
$sql = getCarritoItemsByIdsQuery($placeholders);
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

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-xl mx-auto bg-white p-8 mt-10 rounded shadow">
        <?php
        if (isset($_GET['success'])) {
            echo '<div class="mb-4 p-3 bg-green-100 text-green-800 rounded font-semibold text-center">' . htmlspecialchars($_GET['msg'] ?? '¡Pedido registrado correctamente!') . '</div>';
            if (isset($_GET['id_pedido'])) {
                $id_pedido = intval($_GET['id_pedido']);
                echo '<div class="flex flex-col items-center gap-4 mt-6">';
                echo '<a href="boletas/boleta_' . $id_pedido . '.pdf" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2" download><i class="fa fa-file-pdf-o"></i> Descargar boleta PDF</a>';
                echo '<a href="index.php" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded flex items-center gap-2"><i class="fa fa-home"></i> Volver a la tienda</a>';
                echo '</div>';
            }
            echo '<meta http-equiv="refresh" content="10;url=index.php">';
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
                    <option value="tarjeta" selected>Tarjeta</option>
                </select>
            </div>
            <input type="hidden" name="observaciones" value="Pagado">
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