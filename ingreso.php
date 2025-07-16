<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';
include_once 'views/ingresos/ingreso_queries.php';
include_once 'views/ingresos/ingreso_utils.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

$where_sql = whereFechasIngreso($conn, $fecha_inicio, $fecha_fin);

$sql = getListadoIngresosQuery($where_sql);
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
                <meta http-equiv="refresh" content="3;url=ingreso.php">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Listado de Ingresos</h3>
                <div class="flex gap-2 items-center">
                    <input type="text" id="buscadorIngreso" placeholder="Buscar Movimiento..." class="border rounded px-2 py-1">
                    <a href="#" onclick="abrirModalAgregarIngreso()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Agregar Ingreso
                    </a>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex gap-2 items-center mb-6">
                <form method="get" action="ingreso.php" class="flex gap-2 items-center" style="display:inline;">
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="border rounded px-2 py-1" placeholder="Desde" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
                    <input type="date" name="fecha_fin" id="fecha_fin" class="border rounded px-2 py-1" placeholder="Hasta" value="<?php echo htmlspecialchars($fecha_fin); ?>">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition" title="Filtrar">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <?php if ($fecha_inicio || $fecha_fin): ?>
                        <a href="ingreso.php" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded shadow transition" title="Quitar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs" style="font-size: 0.85rem;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="ordenarPorColumna('tbody', 3, 'iconoOrdenFecha', 'buscadorIngreso', 10, 'paginacionIngreso')">
                                    Fecha Ingreso <span id="iconoOrdenFecha" data-asc="true">↑</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['ref']); ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_producto'] . ($row['talla_producto'] ? '(' . $row['talla_producto'] . ')' : '')); ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_sucursal'] ?? 'Sin sucursal'); ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo $row['fecha_ingreso']; ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['usuario']); ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap flex gap-1">
                                        <button onclick='mostrarCostos(<?php echo htmlspecialchars(json_encode([
                                                                            'precio_costo_igv' => $row['precio_costo_igv'],
                                                                            'precio_venta' => $row['precio_venta'],
                                                                            'utilidad_esperada' => $row['utilidad_esperada'],
                                                                            'utilidad_neta' => $row['utilidad_neta'],
                                                                            'ref' => $row['ref'],
                                                                            'nombre_producto' => $row['nombre_producto'] . ($row['talla_producto'] ? '(' . $row['talla_producto'] . ')' : ''),
                                                                            'nombre_sucursal' => $row['nombre_sucursal'] ?? 'Sin sucursal',
                                                                            'cantidad' => $row['cantidad'],
                                                                            'usuario' => $row['usuario'],
                                                                        ])); ?>)' class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-xs flex items-center gap-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick='abrirModalEditarIngresoDesdeTabla(<?php echo htmlspecialchars(json_encode([
                                            'id' => $row['id'],
                                            'ref' => $row['ref'],
                                            'nombre_producto' => $row['nombre_producto'] . ($row['talla_producto'] ? '(' . $row['talla_producto'] . ')' : ''),
                                            'nombre_sucursal' => $row['nombre_sucursal'] ?? 'Sin sucursal',
                                            'fecha_ingreso' => $row['fecha_ingreso'],
                                            'cantidad' => $row['cantidad'],
                                            'precio_costo_igv' => $row['precio_costo_igv'],
                                            'precio_venta' => $row['precio_venta'],
                                        ])); ?>)' class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs flex items-center gap-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionIngreso" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <?php include 'views/ingresos/modals/modal_agregar_ingreso.php'; ?>
    <?php include 'views/ingresos/modals/modal_costos_ingreso.php'; ?>
    <?php include 'views/ingresos/modals/modal_editar_ingreso.php'; ?>
    <div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
    <script src="assets/js/ingreso.js"></script>
    <script src="assets/js/tabla_utils.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html>