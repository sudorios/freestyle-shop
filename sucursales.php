<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once './conexion/cone.php';
include_once './views/sucursales/sucursales_queries.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

$sql = getAllSucursalesQuery();
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
                <meta http-equiv="refresh" content="3;url=sucursales.php">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Gestión de Sucursales</h3>
                <a href="#" onclick="abrirModalAgregarSucursal()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Agregar Sucursal
                </a>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Sucursal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono Supervisor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Supervisor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['id_sucursal']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_sucursal']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['direccion_sucursal'] ?? ''); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php
                                            switch ($row['tipo_sucursal']) {
                                                case 'almacen':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'fisica':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'online':
                                                    echo 'bg-purple-100 text-purple-800';
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?php
                                            switch ($row['tipo_sucursal']) {
                                                case 'almacen':
                                                    echo 'Centro Distribución';
                                                    break;
                                                case 'fisica':
                                                    echo 'Tienda Física';
                                                    break;
                                                case 'online':
                                                    echo 'Tienda Online';
                                                    break;
                                                default:
                                                    echo htmlspecialchars($row['tipo_sucursal']);
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['supervisor_nombre'] ?? 'Sin asignar'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['supervisor_telefono'] ?? ''); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['supervisor_email'] ?? ''); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $row['estado_sucursal'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo $row['estado_sucursal'] ? 'Activa' : 'Inactiva'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2 btn-editar"
                                            data-id="<?php echo htmlspecialchars($row['id_sucursal']); ?>"
                                            data-nombre="<?php echo htmlspecialchars($row['nombre_sucursal']); ?>"
                                            data-direccion="<?php echo htmlspecialchars($row['direccion_sucursal']); ?>"
                                            data-tipo="<?php echo htmlspecialchars($row['tipo_sucursal']); ?>"
                                            data-supervisor="<?php echo htmlspecialchars($row['id_supervisor']); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="eliminarSucursal(<?php echo $row['id_sucursal']; ?>)" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
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
    <?php include 'views/sucursales/modals/modal_agregar_sucursal.php'; ?>
    <div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
    <script>
        function abrirModalAgregarSucursal() {
            document.getElementById('modal_agregar_sucursal').classList.remove('hidden');
            document.getElementById('modalBackground').classList.remove('hidden');
        }

        function cerrarModalAgregarSucursal() {
            document.getElementById('modal_agregar_sucursal').classList.add('hidden');
            document.getElementById('modalBackground').classList.add('hidden');
        }

        function editarSucursal(id) {
            // Función para editar sucursal
            console.log('Editar sucursal:', id);
        }

        function eliminarSucursal(id) {
            // Función para eliminar sucursal
            if (confirm('¿Estás seguro de que quieres eliminar esta sucursal?')) {
                console.log('Eliminar sucursal:', id);
            }
        }
    </script>
    <?php include_once './views/sucursales/modals/modal_editar_sucursal.php'; ?>
    <?php include_once './views/sucursales/modals/modal_agregar_sucursal.php'; ?>
    <?php include './includes/modal_confirmar.php'; ?>
    <?php include_once './includes/footer.php'; ?>
    <script src="assets/js/sucursales.js"></script>
</body>

</html>