<div id="bg-editarProducto" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalEditarProducto" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
  <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
    <div class="mt-3">
      <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Editar Producto</h3>
      <form id="formEditarProducto" method="POST" action="views/productos/editar_producto.php">
        <input type="hidden" name="id_producto" id="editar_id_producto">
        <div class="mb-4">
          <label for="editar_ref_producto" class="block text-sm font-medium text-gray-700">Referencia</label>
          <input type="text" id="editar_ref_producto" name="ref_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required readonly>
        </div>
        <div class="mb-4">
          <label for="editar_nombre_producto" class="block text-sm font-medium text-gray-700">Nombre</label>
          <input type="text" id="editar_nombre_producto" name="nombre_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
          <label for="editar_descripcion_producto" class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea id="editar_descripcion_producto" name="descripcion_producto" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>
        <div class="mb-4">
          <label for="editar_id_subcategoria" class="block text-sm font-medium text-gray-700">Subcategoría</label>
          <select id="editar_id_subcategoria" name="id_subcategoria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            <!-- Opciones de subcategorías se deben cargar dinámicamente -->
          </select>
        </div>
        <div class="mb-4">
          <label for="editar_talla_producto" class="block text-sm font-medium text-gray-700">Talla</label>
          <input type="text" id="editar_talla_producto" name="talla_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div class="flex justify-end space-x-3 mt-4">
          <button type="button" onclick="cerrarModalEditarProducto()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Cancelar
          </button>
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function abrirModalEditarProducto() {
  document.getElementById('modalEditarProducto').classList.remove('hidden');
  document.getElementById('bg-editarProducto').classList.remove('hidden');
}
function cerrarModalEditarProducto() {
  document.getElementById('modalEditarProducto').classList.add('hidden');
  document.getElementById('bg-editarProducto').classList.add('hidden');
}
</script> 