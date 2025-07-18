<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';
include_once './views/pedidos/pedido_queries.php';
include_once './views/pedidos/pedido_utils.php';

$filtros = get_pedido_filters_from_get();
list($where_sql, $params) = build_pedido_where_sql_and_params($filtros);
$order_sql = get_pedido_order_sql($filtros['orden_precio']);

$sql = query_listar_pedidos($where_sql, $order_sql);
$res = pg_query_params($conn, $sql, $params);
$pedidos = [];
if ($res) {
    while ($row = pg_fetch_assoc($res)) {
        $pedidos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>
<body id="main-content" class="ml-72 mt-20">
    <?php include_once './includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-6">
            <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Operación realizada con éxito</span>
                </div>
                <meta http-equiv="refresh" content="3;url=pedido.php">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Listado de Pedidos</h3>
                <div class="flex gap-2 items-center">
                    <input type="text" id="buscadorPedido" placeholder="Buscar Pedido..." class="border rounded px-2 py-1">
                    <a href="views/pedidos/exportar_pdf_pedidos.php" target="_blank"
                       class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2"
                       title="Exportar todos a PDF">
                        <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                    </a>
                </div>
            </div>
            <form method="get" class="mb-6 flex flex-wrap gap-4 items-end bg-gray-50 p-4 rounded-lg shadow">
                <div>
                    <label class="block text-xs font-semibold mb-1">Fecha desde</label>
                    <input type="date" name="fecha_desde" value="<?= htmlspecialchars($filtros['fecha_desde']) ?>" class="border rounded px-2 py-1">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Fecha hasta</label>
                    <input type="date" name="fecha_hasta" value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>" class="border rounded px-2 py-1">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Estado</label>
                    <select name="estado" class="border rounded px-2 py-1">
                        <option value="">Todos</option>
                        <option value="PENDIENTE" <?= $filtros['estado'] === 'PENDIENTE' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="RECIBIDO" <?= $filtros['estado'] === 'RECIBIDO' ? 'selected' : '' ?>>Recibido</option>
                        <option value="CANCELADO" <?= $filtros['estado'] === 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Ordenar por precio</label>
                    <select name="orden_precio" class="border rounded px-2 py-1">
                        <option value="">Por fecha</option>
                        <option value="mayor" <?= $filtros['orden_precio'] === 'mayor' ? 'selected' : '' ?>>Mayor a menor</option>
                        <option value="menor" <?= $filtros['orden_precio'] === 'menor' ? 'selected' : '' ?>>Menor a mayor</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filtrar</button>
                </div>
            </form>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800"><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700"><?php echo htmlspecialchars($pedido['nombre_usuario'] ?? '-'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700"><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">S/ <?php echo number_format($pedido['total'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700"><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="views/pedidos/ver_pedido.php?id=<?php echo urlencode($pedido['id_pedido']); ?>" 
                                           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded shadow transition" 
                                           title="Ver detalle">
                                            <i class="fas fa-search mr-1"></i>
                                        </a>
                                        <a href="views/pedidos/exportar_pdf_pedido.php?id=<?php echo urlencode($pedido['id_pedido']); ?>" target="_blank"
                                           class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded shadow transition ml-2"
                                           title="Exportar PDF">
                                            <i class="fas fa-file-pdf mr-1"></i>
                                        </a>
                                        <?php if ($pedido['estado'] === 'PENDIENTE'): ?>
                                            <form method="post" action="views/pedidos/cambiar_estado_pedido.php" class="inline-block ml-2">
                                                <input type="hidden" name="id_pedido" value="<?= htmlspecialchars($pedido['id_pedido']) ?>">
                                                <button type="submit" name="nuevo_estado" value="RECIBIDO" title="Marcar como recibido"
                                                    class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 rounded shadow transition">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (in_array($pedido['estado'], ['PENDIENTE', 'RECIBIDO'])): ?>
                                            <form method="post" action="views/pedidos/cancelar_pedido.php" class="inline-block ml-2 form-cancelar-pedido">
                                                <input type="hidden" name="id_pedido" value="<?= htmlspecialchars($pedido['id_pedido']) ?>">
                                                <button type="submit" title="Cancelar pedido"
                                                    class="inline-flex cursor-pointer items-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded shadow transition btn-cancelar-pedido">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($pedidos)): ?>
                                <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay pedidos registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionPedidos" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <?php include './includes/modal_confirmar.php'; ?>
    <script src="assets/js/pedido.js"></script>
    <script src="assets/js/tabla_utils.js"></script>
    <script src="assets/js/modal_confirmar.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 