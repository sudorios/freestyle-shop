<div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modal_agregar_sucursal" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Agregar Sucursal</h3>
            <form id="formAgregarSucursal" action="index.php?controller=sucursal&action=registrar" method="POST">
                <div class="mb-4">
                    <label for="nombre_sucursal" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="nombre_sucursal" name="nombre_sucursal" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                </div>
                <div class="mb-4">
                    <label for="direccion_sucursal" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" id="direccion_sucursal" name="direccion_sucursal" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                </div>
                <div class="mb-4">
                    <label for="tipo_sucursal" class="block text-sm font-medium text-gray-700">Tipo de Sucursal</label>
                    <select id="tipo_sucursal" name="tipo_sucursal" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                        <option value="">Seleccione...</option>
                        <option value="almacen">Centro de Distribución</option>
                        <option value="fisica">Tienda Física</option>
                        <option value="online">Tienda Online</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="id_supervisor" class="block text-sm font-medium text-gray-700">Supervisor</label>
                    <select id="id_supervisor" name="id_supervisor" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($supervisores as $supervisor): ?>
                            <option value="<?php echo $supervisor['id_usuario']; ?>"><?php echo htmlspecialchars($supervisor['nombre_usuario']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="cerrarModalAgregarSucursal()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div> 