<!DOCTYPE html>
<html lang="es">

<?php include_once __DIR__ . '/../../includes/head.php'; ?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<body id="main-content" class="ml-72 mt-20">
    <?php include_once __DIR__ . '/../../includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-6">
            <?php if (isset($_GET['success']) || isset($_GET['error'])): ?>
                <meta http-equiv="refresh" content="3;url=index.php?controller=subcategoria&action=listar">
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Subcategoría registrada correctamente</span>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Subcategoría actualizada correctamente</span>
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
                                    echo "Error al actualizar la subcategoría";
                                    break;
                                case 2:
                                    echo "ID de subcategoría no válido";
                                    break;
                                case 3:
                                    echo "La subcategoría no existe";
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
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Listado de Subcategorías</h3>
                <div class="flex gap-2 items-center">
                    <input type="text" id="buscadorSubcategoria" placeholder="Buscar subcategoría..." class="border rounded px-2 py-1">
                    <a href="#" onclick="abrirModalAgregarSubcategoria();" id="btnAbrirAgregarSubcategoria" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Agregar Subcategoría
                    </a>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none" onclick="ordenarPorColumna('tbody', 0, 'iconoOrdenSub', 'buscadorSubcategoria', 10, 'paginacionSubcategoria')">
                                ID <span id="iconoOrdenSub" data-asc="true">↑</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($subcategorias as $row) { ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['id_subcategoria']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_subcategoria']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['descripcion_subcategoria']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_categoria']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2 btn-editar"
                                        data-id="<?php echo $row['id_subcategoria']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($row['nombre_subcategoria']); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($row['descripcion_subcategoria']); ?>"
                                        data-categoria="<?php echo $row['id_categoria']; ?>">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button onclick="abrirModalConfirmar({mensaje: '¿Seguro que deseas eliminar esta subcategoría?', action: 'index.php?controller=subcategoria&action=eliminar', id: '<?php echo $row['id_subcategoria']; ?>', idField: 'id_subcategoria'})" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded btn-eliminar-subcategoria"
                                        data-id="<?php echo $row['id_subcategoria']; ?>">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div id="paginacionSubcategoria" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>
    <?php include_once __DIR__ . '/modals/modal_agregar_subcategoria.php'; ?>
    <?php include_once __DIR__ . '/modals/modal_editar_subcategoria.php'; ?>
    <?php include __DIR__ . '/../../includes/modal_confirmar.php'; ?>
    <script src="/freestyle-shop/assets/js/modal_confirmar.js?v=1"></script>
    <script src="/freestyle-shop/assets/js/subcategorias.js?v=1"></script>
    <script src="/freestyle-shop/assets/js/tabla_utils.js?v=1"></script>
</body>

</html> 