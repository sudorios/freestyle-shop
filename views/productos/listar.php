<!DOCTYPE html>
<html lang="es">

<?php include_once __DIR__ . '/../../includes/head.php'; ?>

<body id="main-content" class="ml-72 mt-20">
    <?php include_once __DIR__ . '/../../includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-6">
            <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Operación realizada con éxito</span>
                </div>
                <meta http-equiv="refresh" content="3;url=index.php?controller=producto&action=listar">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código:
                        <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Listado de Productos</h3>
                <div class="flex gap-2 items-center">
                    <input type="text" id="buscadorProducto" placeholder="Buscar Producto..."
                        class="border rounded px-2 py-1">
                    <a href="#" onclick="abrirModalAgregarProducto()"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Agregar Producto
                    </a>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    id="thOdernar"
                                    onclick="ordenarPorColumna('tbody', 0, 'iconoOrden', 'buscadorProducto', 10, 'paginacionProducto')">
                                    ID <span id="iconoOrden" data-asc="true">↑</span>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Referencia</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subcategoría</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Talla</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($productos as $row) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($row['id_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($row['ref_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($row['nombre_subcategoria']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($row['talla_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button
                                            class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded btn-editar"
                                            data-id="<?php echo htmlspecialchars($row['id_producto']); ?>"
                                            data-ref="<?php echo htmlspecialchars($row['ref_producto']); ?>"
                                            data-nombre="<?php echo htmlspecialchars($row['nombre_producto']); ?>"
                                            data-subcategoria="<?php echo htmlspecialchars($row['id_subcategoria']); ?>"
                                            data-talla="<?php echo htmlspecialchars($row['talla_producto']); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"
                                            class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ml-2"
                                            data-id="<?php echo $row['id_producto']; ?>" title="Eliminar"
                                            onclick="abrirModalConfirmar({mensaje: '¿Seguro que deseas eliminar este producto?', action: 'index.php?controller=producto&action=eliminar', id: '<?php echo $row['id_producto']; ?>', idField: 'id_producto'})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button type="button"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded ml-2"
                                            onclick="mostrarCodigoBarrasProducto('<?php echo htmlspecialchars($row['ref_producto']); ?>')"
                                            title="Código de Barras">
                                            <i class="fas fa-barcode"></i>
                                        </button>
                                        <button type="button"
                                            class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded ml-2"
                                            onclick="abrirModalImagenesProducto(<?php echo $row['id_producto']; ?>)"
                                            title="Imágenes">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionProducto" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <?php include __DIR__ . '/modals/modal_agregar_producto.php'; ?>
    <div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
    <div id="modalCodigoBarras" class="fixed inset-0 hidden z-40 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 flex flex-col items-center relative">
            <button onclick="cerrarModalCodigoBarras()"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-bold mb-4">Código de Barras</h3>
            <svg id="barcode"></svg>
            <button id="descargarBarcode"
                class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Imprimir</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="/freestyle-shop/assets/js/producto.js"></script>
    <script src="/freestyle-shop/assets/js/modal_confirmar.js"></script>
    <script src="/freestyle-shop/assets/js/tabla_utils.js"></script>
    <?php include __DIR__ . '/modals/modal_imagenes_producto.php'; ?>
    <?php include __DIR__ . '/modals/modal_editar_producto.php'; ?>
    <?php include __DIR__ . '/../../includes/modal_confirmar.php'; ?>
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>
</body>

</html>