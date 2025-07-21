<div id="modalBackgroundEditarSucursal" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalEditarSucursal" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Editar Sucursal</h3>
            <form id="formEditarSucursal" action="index.php?controller=sucursal&action=editar" method="POST">
                <input type="hidden" id="edit_id_sucursal" name="id_sucursal">
                <div class="mb-4">
                    <label for="edit_nombre_sucursal" class="block text-sm font-medium text-gray-700">Nombre de la Sucursal</label>
                    <input type="text" id="edit_nombre_sucursal" name="nombre_sucursal" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                </div>
                <div class="mb-4">
                    <label for="edit_direccion_sucursal" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" id="edit_direccion_sucursal" name="direccion_sucursal" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                </div>
                <div class="mb-4">
                    <label for="edit_tipo_sucursal" class="block text-sm font-medium text-gray-700">Tipo de Sucursal</label>
                    <select id="edit_tipo_sucursal" name="tipo_sucursal" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                        <option value="">Seleccione...</option>
                        <option value="almacen">Centro de Distribución</option>
                        <option value="fisica">Tienda Física</option>
                        <option value="online">Tienda Online</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="edit_id_supervisor" class="block text-sm font-medium text-gray-700">Supervisor</label>
                    <select id="edit_id_supervisor" name="id_supervisor" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($supervisores as $supervisor): ?>
                            <option value="<?php echo $supervisor['id_usuario']; ?>"><?php echo htmlspecialchars($supervisor['nombre_usuario']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalEditarSucursal()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
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
<script>
function cerrarModalEditarSucursal() {
    document.getElementById('modalEditarSucursal').classList.add('hidden');
    document.getElementById('modalBackgroundEditarSucursal').classList.add('hidden');
}
</script> 