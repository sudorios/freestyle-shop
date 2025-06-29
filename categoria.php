<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';
include_once './views/categorias/categoria_queries.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

$sql = getAllCategoriasQuery();
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
                    <span class="block sm:inline">Categoría registrada correctamente</span>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Categoría actualizada correctamente</span>
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
                                    echo "Error al actualizar la categoría";
                                    break;
                                case 2:
                                    echo "ID de categoría no válido";
                                    break;
                                case 3:
                                    echo "La categoría no existe";
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
                <h3 class="text-2xl font-bold">Listado de Categorías</h3>
                <a href="#" onclick="abrirModalAgregarCategoria()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Agregar Categoría
                </a>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = pg_fetch_assoc($result)) { ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['id_categoria']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['nombre_categoria']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['descripcion_categoria']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $row['estado_categoria'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $row['estado_categoria'] ? 'Activa' : 'Inactiva'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2 btn-editar"
                                        data-id="<?php echo $row['id_categoria']; ?>"
                                        data-nombre="<?php echo $row['nombre_categoria']; ?>"
                                        data-descripcion="<?php echo $row['descripcion_categoria']; ?>"
                                        data-estado="<?php echo $row['estado_categoria']; ?>">
                                        <i class="fas fa-edit"></i> Editar
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
    <?php include_once './views/categorias/modals/modal_editar_categoria.php'; ?>
    <?php include_once './views/categorias/modals/modal_agregar_categoria.php'; ?>
    <div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
    <script>
    function abrirModalAgregarCategoria() {
        document.getElementById('modal_agregar_categoria').classList.remove('hidden');
        document.getElementById('modalBackground').classList.remove('hidden');
    }
    function cerrarModalAgregarCategoria() {
        document.getElementById('modal_agregar_categoria').classList.add('hidden');
        document.getElementById('modalBackground').classList.add('hidden');
    }
    </script>
    <?php include_once './includes/footer.php'; ?>

    <script src="assets/js/categorias.js"></script>
</body>
</html> 