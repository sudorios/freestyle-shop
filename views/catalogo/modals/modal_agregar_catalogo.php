<div id="modalBackgroundAgregarCatalogo" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalAgregarCatalogo" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-4 border w-full max-w-xl shadow-lg rounded-lg bg-white">
        <div class="mb-4 border-b pb-2">
            <h3 class="text-xl font-semibold text-gray-800">Agregar Producto al Catálogo</h3>
            <p class="text-sm text-gray-500">Selecciona un producto para agregarlo al catálogo público</p>
        </div>
        <form id="formAgregarCatalogo" action="views/catalogo/catalogo_registrar.php" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label for="buscador_producto" class="block text-sm font-medium text-gray-700 mb-1">Buscar
                            producto</label>
                        <input type="text" id="buscador_producto" autocomplete="off"
                            class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 px-3 py-2 mb-2 bg-gray-50"
                            placeholder="Buscar por nombre...">
                        <div id="sugerencias_producto"
                            class="absolute z-40 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden">
                        </div>
                    </div>
                    <input type="hidden" id="producto_id" name="producto_id" value="">
                    <input type="hidden" id="ingreso_id" name="ingreso_id" value="">
                    <input type="hidden" id="imagen_id" name="imagen_id" value="">
                    <input type="hidden" id="estado" name="estado" value="true">
                    <div class="mb-4">
                        <label for="precio_venta_display" class="block text-sm font-medium text-gray-700 mb-1">Precio de
                            Venta</label>
                        <div id="precio_venta_display"
                            class="block w-full rounded-md border border-gray-200 px-3 py-2 bg-gray-100 text-gray-700">
                            Seleccione un producto para ver el precio
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="estado_oferta" class="block text-sm font-medium text-gray-700 mb-1">Estado
                            Oferta</label>
                        <select id="estado_oferta" name="estado_oferta"
                            class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 px-3 py-2 bg-gray-50"
                            required>
                            <option value="false">Sin oferta</option>
                            <option value="true">En oferta</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="limite_oferta" class="block text-sm font-medium text-gray-700 mb-1">Límite
                            Oferta</label>
                        <input type="date" id="limite_oferta" name="limite_oferta"
                            class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 px-3 py-2 bg-gray-50"
                            disabled>
                    </div>
                    <div class="mb-4">
                        <label for="oferta" class="block text-sm font-medium text-gray-700 mb-1">Descuento (%)</label>
                        <input type="number" id="oferta" name="oferta" min="0" max="100" step="0.01"
                            class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 px-3 py-2 bg-gray-50"
                            placeholder="Porcentaje de descuento" disabled>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                        <div
                            class="w-full h-40 border border-gray-200 rounded-md bg-gray-50 flex items-center justify-center overflow-hidden">
                            <img id="imagen_producto" src="" alt="Imagen del producto"
                                class="max-h-36 max-w-full object-contain" style="display:none;" />
                            <span id="placeholder_img" class="text-gray-400 text-xs">Sin imagen</span>
                        </div>
                    </div>
                    <div class="border rounded-md bg-gray-50 p-3 text-sm text-gray-700">
                        <div id="info_producto">
                            <p>Selecciona un producto para ver su información detallada</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="cerrarModalAgregarCatalogo()"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Agregar al Catálogo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const productos = [
        <?php
        global $conn;
        include_once(__DIR__ . '/../catalogo_queries.php');
        $sql = getProductoQuery();
        $result = pg_query($conn, $sql);
        $first = true;
        while ($row = pg_fetch_assoc($result)) {
            if (!$first)
                echo ",\n";
            else
                $first = false;
            echo json_encode([
                'id_producto' => $row['id_producto'],
                'nombre_producto' => $row['nombre_producto'],
                'talla_producto' => $row['talla_producto'],
                'descripcion_producto' => $row['descripcion_producto'],
                'precio_venta' => $row['precio_venta'],
                'ingreso_id' => $row['ingreso_id'],
                'url_imagen' => $row['url_imagen'],
                'imagen_id' => $row['imagen_id'],
            ]);
        }
        ?>
    ];
</script>
<script src="/freestyle-shop/assets/js/catalogo.js"></script>