<div id="modalBackgroundAgregarCatalogo" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalAgregarCatalogo" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-4 border w-full max-w-xl shadow-lg rounded-lg bg-white">
        <div class="mb-4 border-b pb-2">
            <h3 class="text-xl font-semibold text-gray-800">Agregar Producto al Catálogo</h3>
            <p class="text-sm text-gray-500">Selecciona un producto para agregarlo al catálogo público</p>
        </div>
        <form id="formAgregarCatalogo" action="views/productos/catalogo_registrar.php" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <!-- Producto -->
                    <div class="mb-4">
                        <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                        <select id="producto_id" name="producto_id" class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 px-3 py-2 bg-gray-50" required>
                            <option value="">Seleccione un producto</option>
                            <?php
                                global $conn;
                                $sql = "SELECT 
                                            p.id_producto, 
                                            p.nombre_producto, 
                                            p.talla_producto, 
                                            p.descripcion_producto, 
                                            i.precio_venta, 
                                            i.id AS ingreso_id, 
                                            ip.url_imagen, 
                                            ip.id AS imagen_id, 
                                            ip.vista_producto 
                                        FROM 
                                            inventario_sucursal isuc
                                        JOIN 
                                            producto p ON isuc.id_producto = p.id_producto
                                        JOIN 
                                            ingreso i ON p.id_producto = i.id_producto
                                        JOIN 
                                            imagenes_producto ip ON p.id_producto = ip.producto_id
                                        WHERE 
                                            isuc.id_sucursal = 7
                                            AND isuc.cantidad > 0
                                        ORDER BY 
                                            p.nombre_producto";
                                $result = pg_query($conn, $sql);
                                while ($row = pg_fetch_assoc($result)) {
                                    echo '<option value="' . htmlspecialchars($row['id_producto']) . '" 
                                              data-precio="' . htmlspecialchars($row['precio_venta']) . '" 
                                              data-imagen="' . htmlspecialchars($row['url_imagen']) . '" 
                                              data-imagen_id="' . htmlspecialchars($row['imagen_id']) . '" 
                                              data-ingreso_id="' . htmlspecialchars($row['ingreso_id']) . '" 
                                              data-descripcion="' . htmlspecialchars($row['descripcion_producto']) . '" 
                                              data-talla="' . htmlspecialchars($row['talla_producto']) . '">' . 
                                         htmlspecialchars($row['nombre_producto']) . ' - ' . 
                                         htmlspecialchars($row['talla_producto']) . ' - ' . 
                                         htmlspecialchars($row['descripcion_producto']) . '</option>';
                                }
                            ?>
                        </select>
                        <input type="show" id="ingreso_id" name="ingreso_id" value="">
                        <input type="show" id="imagen_id" name="imagen_id" value="">
                        <input type="show" id="estado" name="estado" value="true">
                    </div>
                    <div class="mb-4">
                        <label for="precio_venta_display" class="block text-sm font-medium text-gray-700 mb-1">Precio de Venta</label>
                        <div id="precio_venta_display" class="block w-full rounded-md border border-gray-200 px-3 py-2 bg-gray-100 text-gray-700">
                            Seleccione un producto para ver el precio
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="estado_oferta" class="block text-sm font-medium text-gray-700 mb-1">Estado Oferta</label>
                        <select id="estado_oferta" name="estado_oferta" class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 px-3 py-2 bg-gray-50" required>
                            <option value="false">Sin oferta</option>
                            <option value="true">En oferta</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="limite_oferta" class="block text-sm font-medium text-gray-700 mb-1">Límite Oferta</label>
                        <input type="date" id="limite_oferta" name="limite_oferta" class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 px-3 py-2 bg-gray-50" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="oferta" class="block text-sm font-medium text-gray-700 mb-1">Descuento (%)</label>
                        <input type="number" id="oferta" name="oferta" min="0" max="100" step="0.01" class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 px-3 py-2 bg-gray-50" placeholder="Porcentaje de descuento" disabled>
                    </div>
                </div>
                <div>
                    <!-- Imagen del Producto -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                        <div class="w-full h-40 border border-gray-200 rounded-md bg-gray-50 flex items-center justify-center overflow-hidden">
                            <img id="imagen_producto" src="" alt="Imagen del producto" class="max-h-36 max-w-full object-contain" style="display:none;" />
                            <span id="placeholder_img" class="text-gray-400 text-xs">Sin imagen</span>
                        </div>
                    </div>
                    <!-- Información Adicional -->
                    <div class="border rounded-md bg-gray-50 p-3 text-sm text-gray-700">
                        <div id="info_producto">
                            <p>Selecciona un producto para ver su información detallada</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Botones -->
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="cerrarModalAgregarCatalogo()" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
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
    document.getElementById('producto_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var precio = selectedOption.getAttribute('data-precio');
        var imagen = selectedOption.getAttribute('data-imagen');
        var descripcion = selectedOption.getAttribute('data-descripcion');
        var talla = selectedOption.getAttribute('data-talla');
        var ingreso_id = selectedOption.getAttribute('data-ingreso_id');
        var imagen_id = selectedOption.getAttribute('data-imagen_id');

        // Actualiza el precio de venta
        document.getElementById('precio_venta_display').innerText = precio ? '$' + precio : 'Seleccione un producto para ver el precio';

        // Actualiza la imagen
        var imgElement = document.getElementById('imagen_producto');
        var placeholder = document.getElementById('placeholder_img');
        if (imagen) {
            imgElement.src = imagen;
            imgElement.style.display = '';
            placeholder.style.display = 'none';
        } else {
            imgElement.src = '';
            imgElement.style.display = 'none';
            placeholder.style.display = '';
        }

        // Actualiza la información del producto
        const infoProducto = document.getElementById('info_producto');
        if (descripcion && talla) {
            infoProducto.innerHTML = `
                <p><strong>Descripción:</strong> ${descripcion}</p>
                <p><strong>Talla:</strong> ${talla}</p>
                <p><strong>Precio:</strong> $${precio ? precio : '-'}</p>
            `;
        } else {
            infoProducto.innerHTML = '<p>Selecciona un producto para ver su información detallada</p>';
        }

        // Setear los campos hidden
        document.getElementById('ingreso_id').value = ingreso_id || '';
        document.getElementById('imagen_id').value = imagen_id || '';
    });

    // Habilitar/deshabilitar campos de oferta según el select
    const estadoOfertaInput = document.getElementById('estado_oferta');
    const limiteOfertaInput = document.getElementById('limite_oferta');
    const ofertaInput = document.getElementById('oferta');

    function actualizarOfertaSelectUI() {
        if (estadoOfertaInput.value === 'true') {
            limiteOfertaInput.disabled = false;
            ofertaInput.disabled = false;
            limiteOfertaInput.classList.remove('bg-gray-100', 'text-gray-400');
            ofertaInput.classList.remove('bg-gray-100', 'text-gray-400');
        } else {
            limiteOfertaInput.disabled = true;
            ofertaInput.disabled = true;
            limiteOfertaInput.classList.add('bg-gray-100', 'text-gray-400');
            ofertaInput.classList.add('bg-gray-100', 'text-gray-400');
        }
    }

    estadoOfertaInput.addEventListener('change', actualizarOfertaSelectUI);
    actualizarOfertaSelectUI();
</script>