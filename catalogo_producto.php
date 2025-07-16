<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';
require_once './views/catalogo/catalogo_queries.php';

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

$sql = getCatalogoProductosListadoQuery();
$result = pg_query($conn, $sql);
if (!$result) {
    die('Error en la consulta: ' . pg_last_error($conn));
}
?>

<?php include_once './includes/head.php'; ?>

<body id="main-content" class="ml-72 mt-20">
    <?php include_once './includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-6">
            <?php
            if (isset($_GET['success'])) {
                $msg = $_GET['msg'] ?? 'Operación exitosa';
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">' . htmlspecialchars($msg) . '</div>';
                echo '<meta http-equiv="refresh" content="1;url=catalogo_producto.php">';
            }
            if (isset($_GET['error'])) {
                $msg = $_GET['msg'] ?? 'Error en la operación';
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">' . htmlspecialchars($msg) . '</div>';
                echo '<meta http-equiv="refresh" content="1;url=catalogo_producto.php">';
            }
            ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Catálogo de Productos</h3>
                <div class="flex gap-2 items-center">
                    <input type="text" id="buscadorCatalogo" placeholder="Buscar..."
                        class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <button onclick="abrirModalAgregarCatalogo()"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Agregar Producto
                    </button>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex gap-2 items-center mb-4">
                <select id="filtroEstadoOferta" class="border rounded px-2 py-1">
                    <option value="">Todos</option>
                    <option value="true">En oferta</option>
                    <option value="false">Sin oferta</option>
                </select>
                <input type="number" id="filtroPrecioMin" placeholder="Precio mín." class="border rounded px-2 py-1"
                    min="0">
                <input type="number" id="filtroPrecioMax" placeholder="Precio máx." class="border rounded px-2 py-1"
                    min="0">
                <input type="date" id="filtroLimiteOferta" class="border rounded px-2 py-1">
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Imagen</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio Venta</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Oferta</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio con Descuento</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr data-id="<?php echo $row['id']; ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($row['url_imagen']) { ?>
                                            <button type="button"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
                                                onclick="mostrarImagenModal('<?php echo htmlspecialchars($row['url_imagen']); ?>')">Ver
                                                imagen</button>
                                        <?php } else { ?>
                                            <span class="text-gray-400">Sin imagen</span>
                                        <?php } ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($row['nombre_producto']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo ($row['precio_venta'] !== null) ? 'S/ ' . number_format($row['precio_venta'], 2) : '<span class="text-gray-400">-</span>'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo ($row['estado'] == 1 || $row['estado'] === 't') ? '<span class="text-green-600">Activo</span>' : '<span class="text-red-600">Inactivo</span>'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($row['estado_oferta'] == 1 || $row['estado_oferta'] === 't') { ?>
                                            <div>
                                                <span class="text-orange-600 font-semibold">En Oferta</span>
                                                <br>
                                                <small class="text-gray-500">Límite:
                                                    <?php echo date('d/m/Y', strtotime($row['limite_oferta'])); ?></small>
                                                <?php if ($row['oferta'] !== null) { ?>
                                                    <br>
                                                    <small class="text-red-600">Descuento:
                                                        <?php echo htmlspecialchars($row['oferta']); ?>%</small>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <span class="text-gray-400">Sin oferta</span>
                                        <?php } ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo ($row['precio_con_descuento'] !== null) ? 'S/ ' . number_format($row['precio_con_descuento'], 2) : '<span class="text-gray-400">-</span>'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if ($row['estado'] == 1 || $row['estado'] === 't') { ?>
                                            <form method="POST" class="inline-form" onsubmit="event.preventDefault();">
                                                <button 
                                                    type="button"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded shadow"
                                                    title="Desactivar"
                                                    onclick="abrirModalConfirmar({
                                                        mensaje: '¿Estás seguro que deseas desactivar este producto en el catálogo?',
                                                        action: 'views/catalogo/catalogo_desactivar.php',
                                                        id: '<?php echo $row['id']; ?>'
                                                    })"
                                                >
                                                    <i class="fas fa-ban fa-lg"></i>
                                                </button>
                                            </form>
                                        <?php } else { ?>
                                            <form method="POST" class="inline-form" onsubmit="event.preventDefault();">
                                                <button 
                                                    type="button"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded shadow"
                                                    title="Activar"
                                                    onclick="abrirModalConfirmar({
                                                        mensaje: '¿Estás seguro que deseas activar este producto en el catálogo?',
                                                        action: 'views/catalogo/catalogo_activar.php',
                                                        id: '<?php echo $row['id']; ?>'
                                                    })"
                                                >
                                                    <i class="fas fa-check-circle fa-lg"></i>
                                                </button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
            <div id="paginacionCatalogo" class="flex justify-center items-center mt-4 gap-2"></div>
        </div>
    </main>

    <?php include_once './views/catalogo/modals/modal_agregar_catalogo.php'; ?>

    <div id="modalImagen" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div class="relative bg-white rounded-lg shadow-lg p-4 max-w-2xl w-full flex flex-col items-center">
            <button onclick="cerrarModalImagen()"
                class="absolute top-2 right-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
            <img id="imagenModalGrande" src="" alt="Imagen producto" class="max-h-[70vh] w-auto rounded" />
        </div>
    </div>

    <?php include './includes/modal_confirmar.php'; ?>
    <script src="assets/js/modal_confirmar.js"></script>
    <script src="/freestyle-shop/assets/js/catalogo.js"></script>
    <script src="assets/js/tabla_utils.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html>