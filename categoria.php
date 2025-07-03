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
    <main>
        <div class="container mx-auto px-4 mt-6">
            <?php if (isset($_GET['success']) || isset($_GET['error'])): ?>
                <meta http-equiv="refresh" content="3;url=categoria.php">
            <?php endif; ?>
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
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6 gap-4 flex-wrap">
                <h3 class="text-2xl font-bold">Listado de Categorías</h3>
                <div class="flex gap-2 items-center">
                    <input
                        type="text"
                        id="buscadorCategoria"
                        placeholder="Buscar categoría..."
                        class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <a href="#" onclick="abrirModalAgregarCategoria()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Agregar Categoría
                    </a>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none" onclick="ordenarPorColumna('tbody', 0, 'iconoOrdenId', 'buscadorCategoria', 10, 'paginacionCategoria')">
                                ID <span id="iconoOrdenId" data-asc="true">↑</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = pg_fetch_assoc($result)) { ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['id_categoria']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['nombre_categoria']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['descripcion_categoria']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2 btn-editar"
                                        data-id="<?php echo $row['id_categoria']; ?>"
                                        data-nombre="<?php echo $row['nombre_categoria']; ?>"
                                        data-descripcion="<?php echo $row['descripcion_categoria']; ?>"
                                        data-estado="<?php echo $row['estado_categoria']; ?>">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button type="button" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"
                                        onclick="abrirModalConfirmar({mensaje: '¿Seguro que deseas eliminar esta categoría?', action: 'views/categorias/eliminar_categoria.php', id: '<?php echo $row['id_categoria']; ?>', idField: 'id_categoria'})">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div id="paginacionCategoria" class="flex justify-center items-center mt-4 gap-2"></div>
        </div>
        </div>
    </main>
    <?php include_once './views/categorias/modals/modal_editar_categoria.php'; ?>
    <?php include_once './views/categorias/modals/modal_agregar_categoria.php'; ?>
    <?php include './includes/modal_confirmar.php'; ?>
    <?php include './includes/footer.php'; ?>
    <script src="assets/js/categorias.js"></script>
    <script src="assets/js/modal_confirmar.js"></script>
    <script src="assets/js/tabla_utils.js"></script>
</body>

</html>