<div id="modalBackgroundAgregarProducto" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalAgregarProducto" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Agregar Producto</h3>
            <form id="formAgregarProducto" action="views/productos/producto_registrar.php" method="POST">
                <div class="grid grid-cols-1 gap-4">
                    <div class="mb-4">
                        <label for="ref_producto" class="block text-sm font-medium text-gray-700">Referencia</label>
                        <input type="text" id="ref_producto" name="ref_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly required>
                    </div>
                    <div class="mb-4">
                        <label for="nombre_producto" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="nombre_producto" name="nombre_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="descripcion_producto" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion_producto" name="descripcion_producto" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="id_subcategoria" class="block text-sm font-medium text-gray-700">Subcategoría</label>
                        <select id="id_subcategoria" name="id_subcategoria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Seleccione una subcategoría</option>
                            <?php
                            global $conn;
                            $subcategorias = pg_query($conn, "SELECT * FROM subcategoria ORDER BY nombre_subcategoria");
                            while ($sub = pg_fetch_assoc($subcategorias)):
                            ?>
                                <option value="<?php echo $sub['id_subcategoria']; ?>"><?php echo htmlspecialchars($sub['nombre_subcategoria']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="talla_producto" class="block text-sm font-medium text-gray-700">Talla</label>
                        <select id="talla_producto" name="talla_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Seleccione una talla</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="cerrarModalAgregarProducto()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function abrirModalAgregarProducto() {
    document.getElementById('modalAgregarProducto').classList.remove('hidden');
    document.getElementById('modalBackgroundAgregarProducto').classList.remove('hidden');
    fetch('views/productos/generar_referencia.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('ref_producto').value = data.referencia;
            } else {
                document.getElementById('ref_producto').value = '';
                alert('No se pudo generar una referencia única. Intente de nuevo.');
            }
        })
        .catch(() => {
            document.getElementById('ref_producto').value = '';
            alert('Error de conexión al generar la referencia.');
        });
}

function cerrarModalAgregarProducto() {
    document.getElementById('modalAgregarProducto').classList.add('hidden');
    document.getElementById('modalBackgroundAgregarProducto').classList.add('hidden');
}
</script> 