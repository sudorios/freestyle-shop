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

$sql = "SELECT i.*, p.nombre_producto, p.talla_producto, s.nombre_sucursal, u.nombre_usuario AS usuario
        FROM ingreso i
        JOIN producto p ON i.id_producto = p.id_producto
        LEFT JOIN sucursal s ON i.id_sucursal = s.id_sucursal
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
                    <table class="min-w-full divide-y divide-gray-200 text-xs" style="font-size: 0.85rem;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Ingreso</th>
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
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo $row['cantidad']; ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo $row['fecha_ingreso']; ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap"><?php echo htmlspecialchars($row['usuario']); ?></td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <button onclick="mostrarCostos(<?php echo htmlspecialchars(json_encode([
                                            'precio_costo_igv' => $row['precio_costo_igv'],
                                            'precio_venta' => $row['precio_venta'],
                                            'utilidad_esperada' => $row['utilidad_esperada'],
                                            'utilidad_neta' => $row['utilidad_neta'],
                                            'ref' => $row['ref'],
                                            'nombre_producto' => $row['nombre_producto'] . ($row['talla_producto'] ? '(' . $row['talla_producto'] . ')' : ''),
                                            'nombre_sucursal' => $row['nombre_sucursal'] ?? 'Sin sucursal',
                                            'cantidad' => $row['cantidad'],
                                            'usuario' => $row['usuario'],
                                        ])); ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-xs flex items-center gap-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include 'views/ingresos/modals/modal_agregar_ingreso.php'; ?>
    <?php include 'views/ingresos/modals/modal_costos_ingreso.php'; ?>
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
    function mostrarCostos(data) {
        document.getElementById('costos_ref').textContent = data.ref;
        document.getElementById('costos_producto').textContent = data.nombre_producto;
        document.getElementById('costos_sucursal').textContent = data.nombre_sucursal;
        document.getElementById('costos_cantidad').textContent = data.cantidad;
        document.getElementById('costos_usuario').textContent = data.usuario;
        document.getElementById('costos_precio_costo_igv').textContent = Number(data.precio_costo_igv).toFixed(2);
        document.getElementById('costos_precio_venta').textContent = Number(data.precio_venta).toFixed(2);
        document.getElementById('costos_utilidad_esperada').textContent = Number(data.utilidad_esperada).toFixed(2);
        document.getElementById('costos_utilidad_neta').textContent = Number(data.utilidad_neta).toFixed(2);
        document.getElementById('modal_costos_ingreso').classList.remove('hidden');
        document.getElementById('modalBackgroundCostos').classList.remove('hidden');
    }
    function cerrarModalCostos() {
        document.getElementById('modal_costos_ingreso').classList.add('hidden');
        document.getElementById('modalBackgroundCostos').classList.add('hidden');
    }
    </script>
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 