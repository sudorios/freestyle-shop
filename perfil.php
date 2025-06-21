<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];
$query = "SELECT * FROM usuario WHERE ref_usuario = $1 OR email_usuario = $1 LIMIT 1";
$result = pg_query_params($conn, $query, array($usuario));

if (!$result || pg_num_rows($result) === 0) {
    die('No se encontró el usuario.');
}
$row = pg_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body class="ml-72 mt-16 bg-gray-100">
    <?php include_once './includes/header.php'; ?>
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-user-cog text-blue-500 mr-2"></i>Mi Perfil</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nombre</label>
                    <div class="bg-gray-100 rounded px-3 py-2"><?php echo htmlspecialchars($row['nombre_usuario']); ?></div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Email</label>
                    <div class="bg-gray-100 rounded px-3 py-2"><?php echo htmlspecialchars($row['email_usuario']); ?></div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nickname</label>
                    <div class="bg-gray-100 rounded px-3 py-2"><?php echo htmlspecialchars($row['ref_usuario']); ?></div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Rol</label>
                    <div class="bg-gray-100 rounded px-3 py-2 uppercase"><?php echo htmlspecialchars($row['rol_usuario']); ?></div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-4 mt-6">
                <a href="#"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded flex items-center justify-center btn-editar"
                    data-id="<?php echo $row['id_usuario']; ?>"
                    data-nombre="<?php echo $row['nombre_usuario']; ?>"
                    data-email="<?php echo $row['email_usuario']; ?>"
                    data-nickname="<?php echo $row['ref_usuario']; ?>"
                    data-telefono="<?php echo $row['telefono_usuario']; ?>"
                    data-direccion="<?php echo $row['direccion_usuario']; ?>"
                    data-rol="<?php echo $row['rol_usuario']; ?>"
                    data-estado="<?php echo $row['estado_usuario']; ?>">
                    <i class="fas fa-edit mr-2"></i>Editar Perfil
                </a>
                <button class="cursor-pointer bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded btn-cambiar-password"
                    data-id="<?php echo $row['id_usuario']; ?>"
                    data-nombre="<?php echo $row['nombre_usuario']; ?>">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </button>
            </div>
        </div>
    </main>
    <?php include_once 'views/usuario/modals/modal_editar_usuario.php'; ?>
    <?php include_once 'views/usuario/modals/modal_cambiar_password.php'; ?>
    <script src="views/usuario/usuarios.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html>