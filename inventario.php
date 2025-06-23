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

$sql = "SELECT i.*, p.nombre_producto, s.nombre_sucursal
        FROM inventario_sucursal i
        JOIN producto p ON i.id_producto = p.id_producto
        JOIN sucursal s ON i.id_sucursal = s.id_sucursal
        ORDER BY s.nombre_sucursal ASC, p.nombre_producto ASC";

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
                <meta http-equiv="refresh" content="3;url=inventario.php">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Inventario por Sucursal</h3>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Costo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Venta</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_sucursal']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['cantidad']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['precio_costo'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['precio_venta'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 