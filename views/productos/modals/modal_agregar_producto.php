<?php
require_once __DIR__ . '/../../../core/Database.php';
$conn = Database::getConexion();
?>
<div id="modalBackgroundAgregarProducto" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalAgregarProducto" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-3 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Agregar Producto</h3>
            <form id="formAgregarProducto" action="index.php?controller=producto&action=registrar" method="POST">
                <div class="grid grid-cols-1 gap-4">
                    <div class="mb-4">
                        <label for="agregar_ref_producto" class="block text-sm font-medium text-gray-700">Referencia</label>
                        <input type="text" id="agregar_ref_producto" name="ref_producto" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required readonly placeholder="Referencia del producto">
                    </div>
                    <div class="mb-4">
                        <label for="agregar_nombre_producto" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="agregar_nombre_producto" name="nombre_producto" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required placeholder="Nombre del producto">
                    </div>
                    <div class="mb-4">
                        <label for="descripcion_producto" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion_producto" name="descripcion_producto" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="id_subcategoria" class="block text-sm font-medium text-gray-700">Subcategoría</label>
                        <select id="id_subcategoria" name="id_subcategoria" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                            <option value="">Seleccione una subcategoría</option>
                            <?php
                                $sql = "SELECT s.id_subcategoria, s.nombre_subcategoria, c.nombre FROM subcategoria s JOIN categoria c ON s.categoria_id = c.categoria_id WHERE s.estado = true AND c.estado_categoria = true ORDER BY c.nombre, s.nombre_subcategoria ASC";
                                $result = pg_query($conn, $sql);
                                while ($row = pg_fetch_assoc($result)) {
                                    echo '<option value="' . htmlspecialchars($row['id_subcategoria']) . '">' . htmlspecialchars($row['nombre_subcategoria']) . ' (' . htmlspecialchars($row['nombre']) . ')</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="agregar_talla_producto" class="block text-sm font-medium text-gray-700">Talla</label>
                        <select id="agregar_talla_producto" name="talla_producto" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
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