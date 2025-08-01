<div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalEditar" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-[800px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Editar Usuario</h3>
            <form id="formEditarUsuario" action="index.php?controller=usuario&action=editar" method="POST">
                <input type="hidden" id="edit_id" name="id_usuario">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="edit_nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="edit_nombre" name="nombre_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="edit_email" name="email_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_nickname" class="block text-sm font-medium text-gray-700">Nickname</label>
                        <input type="text" id="edit_nickname" name="ref_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="tel" id="edit_telefono" name="telefono_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="edit_direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <textarea id="edit_direccion" name="direccion_usuario" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
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