<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

$sql = "SELECT i.*, p.nombre_producto, u.nombre_usuario AS usuario
        FROM ingreso i
        JOIN producto p ON i.id_producto = p.id_producto
        JOIN usuario u ON i.id_usuario = u.id_usuario
        ORDER BY i.fecha_ingreso DESC";

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
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Listado de Ingresos</h3>
                <a href="#" onclick="abrirModalAgregarIngreso()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Agregar Ingreso
                </a>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo Total (c/IGV)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Venta Unidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilidad Esperada</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilidad Neta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Ingreso</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['ref']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['precio_costo_igv'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['precio_venta'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['utilidad_esperada'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['utilidad_neta'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['cantidad']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['fecha_ingreso']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['usuario']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include 'views/ingresos/modals/modal_agregar_ingreso.php'; ?>
    <div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
    <script>
    function abrirModalAgregarIngreso() {
        document.getElementById('modal_agregar_ingreso').classList.remove('hidden');
        document.getElementById('modalBackground').classList.remove('hidden');
    }
    function cerrarModalAgregarIngreso() {
        document.getElementById('modal_agregar_ingreso').classList.add('hidden');
        document.getElementById('modalBackground').classList.add('hidden');
    }
    </script>
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 