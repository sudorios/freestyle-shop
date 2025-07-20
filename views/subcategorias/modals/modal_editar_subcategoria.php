<div id="modalBackgroundEditar" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalEditarSubcategoria" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Editar Subcategoría</h3>
            <form id="formEditarSubcategoria" action="index.php?controller=subcategoria&action=editar" method="POST" onsubmit="console.log('Enviando formulario a:', this.action);">
                <input type="hidden" id="edit_id_subcategoria" name="id_subcategoria">
                <div class="mb-4">
                    <label for="edit_nombre_subcategoria" class="block text-sm font-medium text-gray-700">Nombre de la Subcategoría</label>
                    <input type="text" id="edit_nombre_subcategoria" name="nombre_subcategoria" required
                        class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50">
                </div>
                <div class="mb-4">
                    <label for="edit_descripcion_subcategoria" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="edit_descripcion_subcategoria" name="descripcion_subcategoria" rows="4"
                        class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50"></textarea>
                </div>
                <div class="mb-4">
                    <label for="edit_id_categoria" class="block text-sm font-medium text-gray-700">Categoría</label>
                    <select id="edit_id_categoria" name="id_categoria" required
                        class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50">
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['id_categoria']; ?>"><?php echo htmlspecialchars($cat['nombre_categoria']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalEditarSubcategoria()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

 