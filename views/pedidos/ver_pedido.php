<?php
session_start();
include_once '../../includes/head.php';
include_once '../../conexion/cone.php';

$id_pedido = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_pedido <= 0) {
    die('<div class="text-center mt-10 text-xl">Pedido no válido.</div>');
}

$sql = "SELECT p.id_pedido, u.nombre_usuario, p.fecha, p.total, p.estado, p.direccion_envio, p.metodo_pago, p.observaciones
        FROM pedido p
        LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
        WHERE p.id_pedido = $1";
$res = pg_query_params($conn, $sql, [$id_pedido]);
$pedido = pg_fetch_assoc($res);
if (!$pedido) {
    die('<div class="text-center mt-10 text-xl">Pedido no encontrado.</div>');
}

$sql_det = "SELECT dp.id_detalle, dp.cantidad, dp.precio_unitario, dp.subtotal, p.nombre_producto, p.talla_producto
            FROM detalle_pedido dp
            JOIN producto p ON dp.id_producto = p.id_producto
            WHERE dp.id_pedido = $1";
$res_det = pg_query_params($conn, $sql_det, [$id_pedido]);
$productos = [];
if ($res_det) {
    while ($row = pg_fetch_assoc($res_det)) {
        $productos[] = $row;
    }
}
?>

<body id="main-content" class="ml-72 mt-20">
    <?php include_once '../../includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-20 flex flex-col items-center">
            <div class="w-full max-w-2xl bg-white p-8 rounded shadow">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-center">Detalle del Pedido #<?= htmlspecialchars($pedido['id_pedido']) ?></h1>
                    <a href="exportar_pdf_pedido.php?id=<?= urlencode($pedido['id_pedido']) ?>" target="_blank"
                       class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2"
                       title="Exportar PDF">
                        <i class="fas fa-file-pdf mr-2"></i> PDF
                    </a>
                </div>
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-2"><span class="font-semibold">Usuario:</span>
                            <?= htmlspecialchars($pedido['nombre_usuario'] ?? '-') ?></div>
                        <div class="mb-2"><span class="font-semibold">Fecha:</span>
                            <?= htmlspecialchars($pedido['fecha']) ?></div>
                        <div class="mb-2"><span class="font-semibold">Estado:</span>
                            <?= htmlspecialchars($pedido['estado']) ?></div>
                        <div class="mb-2"><span class="font-semibold">Total:</span> S/
                            <?= number_format($pedido['total'], 2) ?></div>
                    </div>
                    <div>
                        <div class="mb-2"><span class="font-semibold">Dirección de envío:</span>
                            <?= htmlspecialchars($pedido['direccion_envio'] ?? '-') ?></div>
                        <div class="mb-2"><span class="font-semibold">Método de pago:</span>
                            <?= htmlspecialchars($pedido['metodo_pago'] ?? '-') ?></div>
                        <div class="mb-2"><span class="font-semibold">Observaciones:</span>
                            <?= htmlspecialchars($pedido['observaciones'] ?? '-') ?></div>
                    </div>
                </div>
                <h2 class="text-xl font-bold mb-4 text-center">Productos</h2>
                <div class="bg-gray-50 rounded-lg shadow overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Talla</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio
                                    unitario</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($productos as $prod): ?>
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-semibold text-center">
                                        <?= htmlspecialchars($prod['nombre_producto']) ?></td>
                                    <td class="px-4 py-2 text-gray-700 text-center">
                                        <?= htmlspecialchars($prod['talla_producto'] ?? '-') ?></td>
                                    <td class="px-4 py-2 text-gray-700 text-center">
                                        <?= htmlspecialchars($prod['cantidad']) ?></td>
                                    <td class="px-4 py-2 text-gray-700 text-center">S/
                                        <?= number_format($prod['precio_unitario'], 2) ?></td>
                                    <td class="px-4 py-2 text-gray-700 text-center">S/
                                        <?= number_format($prod['subtotal'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($productos)): ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No hay productos en este
                                        pedido.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 text-center">
                    <a href="../../pedido.php"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Volver al
                        listado</a>
                </div>
            </div>
        </div>
    </main>
    <?php include_once '../../includes/footer.php'; ?>
</body>

</html> 