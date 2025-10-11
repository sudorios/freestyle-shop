<div id="bg-editarProducto" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalEditarProducto" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
  <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
    <div class="mt-3">
      <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Editar Producto</h3>
      <form id="formEditarProducto" method="POST" action="index.php?controller=producto&action=editar">
        <input type="hidden" name="id_producto" id="editar_id_producto">
        
        <div class="mb-4">
          <label for="editar_ref_producto" class="block text-sm font-medium text-gray-700">Referencia</label>
          <input type="text" id="editar_ref_producto" name="ref_producto" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required readonly placeholder="Referencia del producto">
        </div>

        <div class="mb-4">
          <label for="editar_nombre_producto" class="block text-sm font-medium text-gray-700">Nombre</label>
          <input type="text" id="editar_nombre_producto" name="nombre_producto" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required placeholder="Nombre del producto">
        </div>

        <div class="mb-4">
          <label for="editar_id_subcategoria" class="block text-sm font-medium text-gray-700">Subcategoría</label>
          <select id="editar_id_subcategoria" name="id_subcategoria" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
            <option value="">Seleccione una subcategoría</option>
            <?php
              include_once './conexion/cone.php';
              $sql = "SELECT s.id_subcategoria, s.nombre_subcategoria, c.nombre FROM subcategoria s JOIN categoria c ON s.categoria_id = c.categoria_id WHERE s.estado = true AND c.estado_categoria = true ORDER BY c.nombre, s.nombre_subcategoria ASC";
              $result = pg_query($conn, $sql);
              while ($row = pg_fetch_assoc($result)) {
                echo '<option value="' . htmlspecialchars($row['id_subcategoria']) . '">' . htmlspecialchars($row['nombre_subcategoria']) . ' (' . htmlspecialchars($row['nombre']) . ')</option>';
              }
            ?>
          </select>
        </div>

        <div class="mb-4">
          <label for="editar_talla_producto" class="block text-sm font-medium text-gray-700">Talla</label>
          <select id="editar_talla_producto" name="talla_producto" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
            <option value="">Seleccione una talla</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
            <option value="XXL">XXL</option>
          </select>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" onclick="cerrarModalEditarProducto()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
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
