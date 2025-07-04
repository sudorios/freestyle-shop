<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Filtros de fecha para la vista
$fecha_inicio = isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] !== '' ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) && $_GET['fecha_fin'] !== '' ? $_GET['fecha_fin'] : '';
$where = [];
if ($fecha_inicio) {
    $where[] = "k.fecha_movimiento >= '" . pg_escape_string($conn, $fecha_inicio) . "'";
}
if ($fecha_fin) {
    $where[] = "k.fecha_movimiento <= '" . pg_escape_string($conn, $fecha_fin) . "'";
}
$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT k.*, p.nombre_producto, u.nombre_usuario AS usuario
        FROM kardex k
        JOIN producto p ON k.id_producto = p.id_producto
        JOIN usuario u ON k.id_usuario = u.id_usuario
        $where_sql
        ORDER BY k.id_kardex DESC";

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
                    <form method="get" action="views/kardex/exportar_csv.php" style="display:inline;">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2" title="Exportar a CSV">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </form>
                    <form method="get" action="views/kardex/exportar_pdf.php" style="display:inline;">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2" title="Exportar a PDF">
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
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition" title="Filtrar">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <?php if ($fecha_inicio || $fecha_fin): ?>
                        <a href="kardex.php" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded shadow transition" title="Quitar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Movimiento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Movimiento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['id_kardex']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['cantidad']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $row['tipo_movimiento'] == 'ENTRADA' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo htmlspecialchars($row['tipo_movimiento']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['fecha_movimiento']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['usuario']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionKardex" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
    <script>
        function abrirModalAgregarKardex() {
            document.getElementById('modal_agregar_kardex').classList.remove('hidden');
            document.getElementById('modalBackground').classList.remove('hidden');
        }

        function cerrarModalAgregarKardex() {
            document.getElementById('modal_agregar_kardex').classList.add('hidden');
            document.getElementById('modalBackground').classList.add('hidden');
        }
    </script>
    <script src="assets/js/kardex.js"></script>
    <script src="assets/js/tabla_utils.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html>