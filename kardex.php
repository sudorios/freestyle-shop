<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';
include_once './utils/queries.php';
include_once 'views/kardex/kardex_queries.php';
include_once 'views/kardex/kardex_utils.php';


$fecha_inicio = isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] !== '' ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) && $_GET['fecha_fin'] !== '' ? $_GET['fecha_fin'] : '';
$id_sucursal = isset($_GET['id_sucursal']) && $_GET['id_sucursal'] !== '' ? $_GET['id_sucursal'] : '';

$where_sql = getWhereFechasKardex($conn, $fecha_inicio, $fecha_fin);
if ($id_sucursal && $id_sucursal !== 'todas') {
    $where_sql .= ($where_sql ? ' AND ' : 'WHERE ') . "s.id_sucursal = '" . pg_escape_string($conn, $id_sucursal) . "'";
}

$sql_suc = getSucursalesActivasQuery();
$res_suc = pg_query($conn, $sql_suc);
$sucursales = [];
while ($row_suc = pg_fetch_assoc($res_suc)) {
    $sucursales[] = $row_suc;
}

$sql = getKardexListadoQuery($where_sql);
$result = pg_query($conn, $sql);

if (!$result) {
    die('Error en la consulta: ' . pg_last_error($conn));
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
                <meta http-equiv="refresh" content="3;url=kardex.php">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Movimiento de Productos</h3>
                <div class="flex gap-2 items-center">
                    <input type="text" id="buscadorKardex" placeholder="Buscar Movimiento..." class="border rounded px-2 py-1">
                    <form method="get" action="views/kardex/exportar_csv.php" style="display:inline;">
                        <button type="submit" class="bg-green-600 cursor-pointer hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2" title="Exportar a CSV">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </form>
                    <form method="get" action="views/kardex/exportar_pdf.php" style="display:inline;">
                        <button type="submit" class="bg-red-600 cursor-pointer hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2" title="Exportar a PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </form>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex gap-2 items-center">
                <form method="get" action="kardex.php" class="flex gap-2 items-center" style="display:inline;">
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="border rounded px-2 py-1" placeholder="Desde" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
                    <input type="date" name="fecha_fin" id="fecha_fin" class="border rounded px-2 py-1" placeholder="Hasta" value="<?php echo htmlspecialchars($fecha_fin); ?>">
                    <select name="id_sucursal" id="id_sucursal" class="border rounded px-2 py-1">
                        <option value="todas" <?php if ($id_sucursal === '' || $id_sucursal === 'todas') echo 'selected'; ?>>Todas las sucursales</option>
                        <?php foreach ($sucursales as $suc) { ?>
                            <option value="<?php echo $suc['id_sucursal']; ?>" <?php if ($id_sucursal == $suc['id_sucursal']) echo 'selected'; ?>><?php echo htmlspecialchars($suc['nombre_sucursal']); ?></option>
                        <?php } ?>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 cursor-pointer text-white font-bold py-2 px-4 rounded shadow transition" title="Filtrar">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <?php if ($fecha_inicio || $fecha_fin || ($id_sucursal && $id_sucursal !== 'todas')): ?>
                        <a href="kardex.php" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded shadow transition" title="Quitar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm" style="max-width: 1300px; margin:auto;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Tipo Movimiento</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer" id="thOdernar" onclick="ordenarPorColumna('tbody', 0, 'iconoOrden', 'buscadorProducto', 10, 'paginacionProducto')">
                                    Fecha Movimiento <span id="iconoOrden" data-asc="true">↑</span>
                                </th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['id_kardex']); ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_sucursal'] ?? 'Sin sucursal'); ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo $row['cantidad']; ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $row['tipo_movimiento'] == 'ENTRADA' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo htmlspecialchars($row['tipo_movimiento']); ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo $row['fecha_movimiento']; ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['usuario']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionKardex" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <script src="assets/js/kardex.js"></script>
    <script src="assets/js/tabla_utils.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html>