<div id="bg-editarConteo" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalEditarConteo" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
  <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
    <div class="mt-3">
      <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Editar Conteo Cíclico</h3>
      <form id="formEditarConteo" method="POST" action="views/conteos_ciclicos/conteo_ciclico_editar.php">
        <input type="hidden" name="id_conteo" id="editar_id_conteo">
        <input type="hidden" name="id_producto" id="editar_id_producto">
        <input type="hidden" name="id_sucursal" id="editar_id_sucursal">
        <input type="hidden" name="cantidad_sistema" id="editar_cantidad_sistema">
        <div class="mb-4">
          <label for="editar_cantidad_real" class="block text-sm font-medium text-gray-700">Cantidad Real</label>
          <input type="number" id="editar_cantidad_real" name="cantidad_real" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition px-3 py-2 bg-gray-50" required min="0">
        </div>
        <div class="mb-4">
          <label for="editar_fecha_conteo" class="block text-sm font-medium text-gray-700">Fecha</label>
          <input type="date" id="editar_fecha_conteo" name="fecha_conteo" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition px-3 py-2 bg-gray-50" required>
        </div>
        <div class="mb-4">
          <label for="editar_estado_conteo" class="block text-sm font-medium text-gray-700">Estado</label>
          <select id="editar_estado_conteo" name="estado_conteo" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition px-3 py-2 bg-gray-50" required>
            <option value="Pendiente">Pendiente</option>
            <option value="Completado">Completado</option>
            <option value="Cancelado">Cancelado</option>
          </select>
        </div>
        <div class="mb-4">
          <label for="editar_comentarios" class="block text-sm font-medium text-gray-700">Comentarios</label>
          <textarea id="editar_comentarios" name="comentarios" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition px-3 py-2 bg-gray-50"></textarea>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" onclick="cerrarModalEditarConteo()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            Cancelar
          </button>
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>