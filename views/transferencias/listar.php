<?php
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once __DIR__ . '/../../includes/head.php'; ?>
<body id="main-content" class="ml-72 mt-20">
    <?php include_once __DIR__ . '/../../includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-6">
            <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Transferencia realizada con éxito</span>
                </div>
                <meta http-equiv="refresh" content="3;url=index.php?controller=transferencia&action=listar">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Transferencias entre Sucursales</h3>
                <div class="flex gap-2 items-center">
                    <input
                        type="text"
                        id="buscadorTransferencia"
                        placeholder="Buscar transferencia..."
                        class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <a href="index.php?controller=transferencia&action=registrar" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded">
                        Nueva Transferencia
                    </a>
                    <?php
                    $filtros = $_GET;
                    unset($filtros['controller'], $filtros['action']);
                    $queryFiltros = http_build_query($filtros);
                    ?>
                    <a href="index.php?controller=transferencia&action=exportarCSV<?php echo $queryFiltros ? '&' . $queryFiltros : ''; ?>" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-3 rounded flex items-center" title="Exportar CSV">
                        <i class="fas fa-file-csv mr-1"></i> 
                    </a>
                    <a href="index.php?controller=transferencia&action=exportarPDF<?php echo $queryFiltros ? '&' . $queryFiltros : ''; ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-3 rounded flex items-center" title="Exportar PDF">
                        <i class="fas fa-file-pdf mr-1"></i> 
                    </a>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">

            <div class="flex flex-wrap gap-2 items-center mb-4">
                <form method="get" action="index.php" class="flex gap-2 items-center">
                    <input type="hidden" name="controller" value="transferencia">
                    <input type="hidden" name="action" value="listar">
                    <input type="date" name="fecha_inicio" class="border rounded px-2 py-1 text-xs" placeholder="Desde" value="<?php echo htmlspecialchars($_GET['fecha_inicio'] ?? ''); ?>">
                    <input type="date" name="fecha_fin" class="border rounded px-2 py-1 text-xs" placeholder="Hasta" value="<?php echo htmlspecialchars($_GET['fecha_fin'] ?? ''); ?>">
                    <select name="origen" class="border rounded px-2 py-1 text-xs">
                        <option value="">Origen</option>
                        <?php foreach ($sucursales as $suc) { ?>
                            <option value="<?php echo $suc['id_sucursal']; ?>" <?php if (($_GET['origen'] ?? '') == $suc['id_sucursal']) echo 'selected'; ?>><?php echo htmlspecialchars($suc['nombre_sucursal']); ?></option>
                        <?php } ?>
                    </select>
                    <select name="destino" class="border rounded px-2 py-1 text-xs">
                        <option value="">Destino</option>
                        <?php foreach ($sucursales as $suc) { ?>
                            <option value="<?php echo $suc['id_sucursal']; ?>" <?php if (($_GET['destino'] ?? '') == $suc['id_sucursal']) echo 'selected'; ?>><?php echo htmlspecialchars($suc['nombre_sucursal']); ?></option>
                        <?php } ?>
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-3 py-1 rounded text-xs">Filtrar</button>
                    <?php if (($_GET['fecha_inicio'] ?? '') || ($_GET['fecha_fin'] ?? '') || ($_GET['origen'] ?? '') || ($_GET['destino'] ?? '')) { ?>
                        <a href="index.php?controller=transferencia&action=listar" class="ml-2 text-xs text-gray-600 underline">Limpiar filtros</a>
                    <?php } ?>
                </form>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">

            <div class="bg-white rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-2 w-20 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                <th class="px-2 py-2 w-40 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Producto</th>
                                <th class="px-2 py-2 w-40 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sucursal Origen</th>
                                <th class="px-2 py-2 w-40 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sucursal Destino</th>
                                <th class="px-2 py-2 w-20 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cantidad</th>
                                <th class="px-2 py-2 w-32 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                                <th class="px-2 py-2 w-32 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuario</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($transferencias as $row) { ?>
                                <tr>
                                    <td class="px-2 py-2 whitespace-nowrap text-xs"><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="px-2 py-2 whitespace-nowrap text-xs"><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                    <td class="px-2 py-2 whitespace-nowrap text-xs">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <?php echo htmlspecialchars($row['sucursal_origen']); ?>
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-xs">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <?php echo htmlspecialchars($row['sucursal_destino']); ?>
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-xs"><?php echo $row['cantidad']; ?></td>
                                    <td class="px-2 py-2 whitespace-nowrap text-xs"><?php echo date('d/m/Y H:i', strtotime($row['fecha_transferencia'])); ?></td>
                                    <td class="px-2 py-2 whitespace-nowrap text-xs"><?php echo htmlspecialchars($row['usuario']); ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($transferencias)): ?>
                                <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay transferencias registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionTransferencia" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <?php include __DIR__ . '/../../includes/modal_confirmar.php'; ?>
    <script src="/freestyle-shop/assets/js/modal_confirmar.js?v=1"></script>
    <script src="/freestyle-shop/assets/js/tabla_utils.js?v=1"></script>
    <script src="/freestyle-shop/assets/js/transferencia.js?v=1"></script>
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html> 