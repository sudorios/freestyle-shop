<?php
session_start();
require_once './utils/queries.php';
check_rol(['developer','admin']);
include_once './conexion/cone.php';

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

$sql = "SELECT * FROM usuario WHERE rol_usuario = 'cliente' ORDER BY id_usuario ASC";
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
                <meta http-equiv="refresh" content="3;url=cliente.php">
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] == 3): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Contraseña cambiada correctamente</span>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Listado de Clientes</h3>
                <div class="flex gap-2 items-center">
                    <input type="text" id="buscadorCliente" placeholder="Buscar Cliente..." class="border rounded px-2 py-1">
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" id="thOdernar" onclick="ordenarPorColumna('tbody', 0, 'iconoOrden', 'buscadorCliente', 10, 'paginacionCliente')">
                                    ID <span id="iconoOrden" data-asc="true">↑</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['id_usuario']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['email_usuario']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['telefono_usuario'] ?? '-'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap uppercase"><?php echo htmlspecialchars($row['rol_usuario']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($row['estado_usuario']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded btn-editar"
                                            data-id="<?php echo htmlspecialchars($row['id_usuario']); ?>"
                                            data-nombre="<?php echo htmlspecialchars($row['nombre_usuario']); ?>"
                                            data-email="<?php echo htmlspecialchars($row['email_usuario']); ?>"
                                            data-telefono="<?php echo htmlspecialchars($row['telefono_usuario'] ?? ''); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="cursor-pointer bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded ml-2 btn-cambiar-password"
                                            data-id="<?php echo htmlspecialchars($row['id_usuario']); ?>"
                                            data-nombre="<?php echo htmlspecialchars($row['nombre_usuario']); ?>">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <button type="button" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ml-2" data-id="<?php echo $row['id_usuario']; ?>" title="Eliminar" onclick="abrirModalConfirmar({mensaje: '¿Seguro que deseas eliminar este cliente?', action: 'views/cliente/cliente_eliminar.php', id: '<?php echo $row['id_usuario']; ?>', idField: 'id_usuario'})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionCliente" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <script src="assets/js/modal_confirmar.js"></script>
    <script src="assets/js/tabla_utils.js"></script>
    <?php include 'includes/modal_confirmar.php'; ?>
    <?php include 'views/usuario/modals/modal_cambiar_password.php'; ?>
    <script src="views/usuario/usuarios.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html> 