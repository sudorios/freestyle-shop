<div id="modal_agregar_ingreso" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-[800px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Agregar Ingreso</h3>
            <div class="mb-4">
                <label for="usuario_ingreso" class="block text-sm font-medium text-gray-700">Usuario</label>
                <input type="text" id="usuario_ingreso" name="usuario_ingreso" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" readonly value="<?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario'; ?>">
            </div>
            <form id="formAgregarIngreso" action="views/ingresos/ingreso_registrar.php" method="POST">
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="id_sucursal" class="block text-sm font-medium text-gray-700">Sucursal</label>
                        <select id="id_sucursal" name="id_sucursal" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                            <option value="">Seleccione...</option>
                            <?php
                            include_once '../../../conexion/cone.php';
                            $sql_suc = "SELECT id_sucursal, nombre_sucursal FROM sucursal WHERE estado_sucursal = true ORDER BY nombre_sucursal ASC";
                            $res_suc = pg_query($conn, $sql_suc);
                            while ($suc = pg_fetch_assoc($res_suc)) {
                                echo '<option value="' . $suc['id_sucursal'] . '">' . htmlspecialchars($suc['nombre_sucursal']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="ref" class="block text-sm font-medium text-gray-700">Referencia</label>
                        <input type="text" id="ref" name="ref" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                    </div>
                    <div class="mb-4">
                        <label for="id_producto" class="block text-sm font-medium text-gray-700">Producto</label>
                        <select id="id_producto" name="id_producto" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                            <option value="">Seleccione...</option>
                            <?php
                            include_once '../../../conexion/cone.php';
                            $sql_prod = "SELECT id_producto, nombre_producto, talla_producto FROM producto WHERE estado = true ORDER BY nombre_producto ASC";
                            $res_prod = pg_query($conn, $sql_prod);
                            while ($prod = pg_fetch_assoc($res_prod)) {
                                $nombre = htmlspecialchars($prod['nombre_producto']);
                                $talla = htmlspecialchars($prod['talla_producto']);
                                $texto = $nombre . ($talla ? ' - Talla: ' . $talla : '');
                                echo '<option value="' . $prod['id_producto'] . '">' . $texto . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="precio_costo" class="block text-sm font-medium text-gray-700">Precio Costo (total del paquete)</label>
                        <input type="number" step="0.01" id="precio_costo" name="precio_costo" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                    </div>
                    <div class="mb-4">
                        <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad de unidades</label>
                        <input type="number" id="cantidad" name="cantidad" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" value="1" min="1" required>
                    </div>
                    <div class="mb-4">
                        <label for="fecha_ingreso" class="block text-sm font-medium text-gray-700">Fecha de Ingreso</label>
                        <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                    </div>
                    <input type="hidden" id="precio_costo_unidad" name="precio_costo_unidad">
                    <input type="hidden" id="precio_costo_con_igv" name="precio_costo_con_igv">
                    <input type="hidden" id="precio_venta" name="precio_venta">
                    <input type="hidden" id="utilidad_esperada_unidad" name="utilidad_esperada_unidad">
                    <input type="hidden" id="utilidad_esperada_total" name="utilidad_esperada_total">
                    <input type="hidden" id="utilidad_neta_unidad" name="utilidad_neta_unidad">
                    <input type="hidden" id="utilidad_neta_total" name="utilidad_neta_total">
                    <input type="hidden" id="precio_costo_igv_paquete" name="precio_costo_igv_paquete">
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="cerrarModalAgregarIngreso()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Cancelar</button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const igv = 0.18; 
    const margen = 0.20; 

    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_ingreso').value = today;
    });

    function calcularCamposAutomaticos() {
        const precioCostoTotal = parseFloat(document.getElementById('precio_costo').value) || 0;
        const cantidad = parseInt(document.getElementById('cantidad').value) || 1;

        const precioCostoUnidad = cantidad > 0 ? +(precioCostoTotal / cantidad).toFixed(2) : 0;
        document.getElementById('precio_costo_unidad').value = precioCostoUnidad;

        const precioCostoConIgvUnidad = +(precioCostoUnidad * (1 + igv)).toFixed(2);
        document.getElementById('precio_costo_con_igv').value = precioCostoConIgvUnidad;

        const precioVenta = +(precioCostoConIgvUnidad * (1 + margen)).toFixed(2);
        document.getElementById('precio_venta').value = precioVenta;

        const utilidadEsperadaUnidad = +(precioVenta - precioCostoUnidad).toFixed(2);
        const utilidadEsperadaTotal = +(utilidadEsperadaUnidad * cantidad).toFixed(2);
        document.getElementById('utilidad_esperada_unidad').value = utilidadEsperadaUnidad;
        document.getElementById('utilidad_esperada_total').value = utilidadEsperadaTotal;

        const utilidadNetaUnidad = +(precioVenta - precioCostoConIgvUnidad).toFixed(2);
        const utilidadNetaTotal = +(utilidadNetaUnidad * cantidad).toFixed(2);
        document.getElementById('utilidad_neta_unidad').value = utilidadNetaUnidad;
        document.getElementById('utilidad_neta_total').value = utilidadNetaTotal;

        const precioCostoIgvPaquete = +(precioCostoTotal * (1 + igv)).toFixed(2);
        document.getElementById('precio_costo_igv_paquete').value = precioCostoIgvPaquete;
    }

    document.getElementById('precio_costo').addEventListener('input', calcularCamposAutomaticos);
    document.getElementById('cantidad').addEventListener('input', calcularCamposAutomaticos);
</script>
