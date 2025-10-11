<div id="modalBackgroundAgregar" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalAgregarSubcategoria" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Agregar Subcategoría</h3>
            <form id="formAgregarSubcategoria" action="index.php?controller=subcategoria&action=registrar" method="POST">
                <div class="grid grid-cols-1 gap-4">
                    <div class="mb-4">
                        <label for="nombre_subcategoria" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="nombre_subcategoria" name="nombre_subcategoria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="descripcion_subcategoria" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion_subcategoria" name="descripcion_subcategoria" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select id="categoria_id" name="categoria_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Seleccione una categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['categoria_id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="cerrarModalAgregarSubcategoria()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function cerrarModalAgregarSubcategoria() {
    document.getElementById('modalAgregarSubcategoria').classList.add('hidden');
    document.getElementById('modalBackgroundAgregar').classList.add('hidden');
}
</script> 