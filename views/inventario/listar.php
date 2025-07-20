<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_sucursal = isset($_GET['id_sucursal']) && $_GET['id_sucursal'] !== '' ? intval($_GET['id_sucursal']) : null;
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
                <meta http-equiv="refresh" content="3;url=index.php?controller=inventario&action=listar">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Inventario por Sucursal</h3>
                <div class="flex gap-2 items-center">
                    <form method="get" action="index.php" class="inline">
                        <input type="hidden" name="controller" value="inventario">
                        <input type="hidden" name="action" value="listar">
                        <select name="id_sucursal" onchange="this.form.submit()" class="border rounded px-2 py-1">
                            <option value="" <?php if (empty($id_sucursal)) echo 'selected'; ?>>Todos</option>
                            <?php foreach ($sucursales as $suc): ?>
                                <option value="<?php echo $suc['id_sucursal']; ?>" <?php if ($suc['id_sucursal'] == $id_sucursal) echo 'selected'; ?>><?php echo htmlspecialchars($suc['nombre_sucursal']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <input type="text" id="buscadorInventario" placeholder="Buscar Producto..." class="border rounded px-2 py-1">
                    <a href="index.php?controller=inventario&action=exportarCSV<?php echo ($id_sucursal ? '&id_sucursal=' . urlencode($id_sucursal) : ''); ?>" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2" title="Exportar a CSV">
                        <i class="fas fa-file-csv"></i>
                    </a>
                    <a href="index.php?controller=inventario&action=exportarPDF<?php echo ($id_sucursal ? '&id_sucursal=' . urlencode($id_sucursal) : ''); ?>" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2" title="Exportar a PDF">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="ordenarPorColumna('tbody', 2, 'iconoOrdenCantidad', 'buscadorInventario', 10, 'paginacionInventario')">
                                    Cantidad <span id="iconoOrdenCantidad" data-asc="true">↑</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Actualización</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($inventario as $row): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_producto'] . ($row['talla_producto'] ? '(' . $row['talla_producto'] . ')' : '')); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_sucursal']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap <?php echo ($row['cantidad'] < 10) ? 'bg-red-100 text-red-800 font-semibold' : ''; ?>"><?php echo $row['cantidad']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo isset($row['fecha_actualizacion']) ? date('d/m/Y H:i', strtotime($row['fecha_actualizacion'])) : '-'; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $estado = strtoupper($row['estado']);
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php
                                            if ($estado === 'CUADRA') {
                                                echo 'bg-green-100 text-green-800';
                                            } elseif ($estado === 'SOBRA') {
                                                echo 'bg-yellow-100 text-yellow-800';
                                            } elseif ($estado === 'FALTA') {
                                                echo 'bg-red-100 text-red-800';
                                            } else {
                                                echo 'bg-gray-100 text-gray-800';
                                            }
                                        ?>">
                                            <?php
                                                if ($estado === 'CUADRA' || $estado === 'SOBRA' || $estado === 'FALTA') {
                                                    echo ucfirst(strtolower($estado));
                                                } else {
                                                    echo 'Desconocido';
                                                }
                                            ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="index.php?controller=conteociclico&action=listar&id_producto=<?php echo $row['id_producto']; ?>&id_sucursal=<?php echo $row['id_sucursal']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded" title="Conteo Cíclico">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionInventario" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <script src="/freestyle-shop/assets/js/inventario.js?v=1"></script>
    <script src="/freestyle-shop/assets/js/tabla_utils.js?v=1"></script>
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 