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

$sql = "SELECT * FROM usuario ORDER BY id_usuario ASC ";
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
    <main >
        <div class="container mx-auto px-4 mt-6">
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Usuario registrado correctamente</span>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Usuario actualizado correctamente</span>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] == 3): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Contraseña cambiada correctamente</span>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">
                        <?php
                        if (isset($_GET['msg'])) {
                            echo htmlspecialchars($_GET['msg']);
                        } else {
                            switch ($_GET['error']) {
                                case 1:
                                    echo "Error al actualizar el usuario";
                                    break;
                                case 2:
                                    echo "ID de usuario no válido";
                                    break;
                                case 3:
                                    echo "El usuario no existe";
                                    break;
                                case 4:
                                    echo "Las contraseñas no coinciden";
                                    break;
                                case 5:
                                    echo "Error al cambiar la contraseña";
                                    break;
                                default:
                                    echo "Error desconocido";
                            }
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Listado de Usuarios</h3>
                <a href="login_add.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Agregar Usuario
                </a>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nickname</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = pg_fetch_assoc($result)) { ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['id_usuario']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['nombre_usuario']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['email_usuario']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['ref_usuario']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['telefono_usuario']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap uppercase"><?php echo $row['rol_usuario']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $row['estado_usuario'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $row['estado_usuario'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2 btn-editar"
                                        data-id="<?php echo $row['id_usuario']; ?>"
                                        data-nombre="<?php echo $row['nombre_usuario']; ?>"
                                        data-email="<?php echo $row['email_usuario']; ?>"
                                        data-nickname="<?php echo $row['ref_usuario']; ?>"
                                        data-telefono="<?php echo $row['telefono_usuario']; ?>"
                                        data-direccion="<?php echo $row['direccion_usuario']; ?>"
                                        data-rol="<?php echo $row['rol_usuario']; ?>"
                                        data-estado="<?php echo $row['estado_usuario']; ?>">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="cursor-pointer bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded btn-cambiar-password"
                                        data-id="<?php echo $row['id_usuario']; ?>"
                                        data-nombre="<?php echo $row['nombre_usuario']; ?>">
                                        <i class="fas fa-key"></i> Cambiar Contraseña
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
    <?php include_once 'views/usuario/modals/modal_editar_usuario.php'; ?>
    <?php include_once 'views/usuario/modals/modal_cambiar_password.php'; ?>
    <?php include_once './includes/footer.php'; ?>

    <script src="views/usuario/usuarios.js"></script>
</body>
</html>