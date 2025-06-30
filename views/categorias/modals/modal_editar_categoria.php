<div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalEditar" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Editar Categoría</h3>
            <form id="formEditarCategoria" action="views/categorias/categoria_edit.php" method="POST">
                <input type="hidden" id="edit_id" name="id_categoria">
                <div class="mb-4">
                    <label for="edit_nombre" class="block text-sm font-medium text-gray-700">Nombre de la Categoría</label>
                    <input type="text" id="edit_nombre" name="nombre_categoria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="edit_descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="edit_descripcion" name="descripcion_categoria" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 