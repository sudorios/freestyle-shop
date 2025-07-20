<div id="bg-editarIngreso" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalEditarIngreso" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-[800px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Editar Ingreso</h3>
            <form id="formEditarIngreso" action="index.php?controller=ingreso&action=editar" method="POST">
                <input type="hidden" name="id_ingreso" id="editar_id_ingreso">
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="editar_sucursal_ingreso" class="block text-sm font-medium text-gray-700">Sucursal</label>
                        <input type="text" id="editar_sucursal_ingreso" name="sucursal_ingreso" class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="editar_ref_ingreso" class="block text-sm font-medium text-gray-700">Referencia</label>
                        <input type="text" id="editar_ref_ingreso" name="ref_ingreso" class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="editar_producto_ingreso" class="block text-sm font-medium text-gray-700">Producto</label>
                        <input type="text" id="editar_producto_ingreso" name="producto_ingreso" class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="editar_precio_costo_igv" class="block text-sm font-medium text-gray-700">Precio Costo IGV</label>
                        <input type="number" step="0.01" id="editar_precio_costo_igv" name="precio_costo_igv" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                    </div>
                    <div class="mb-4">
                        <label for="editar_cantidad_ingreso" class="block text-sm font-medium text-gray-700">Cantidad de unidades</label>
                        <input type="number" id="editar_cantidad_ingreso" name="cantidad_ingreso" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" min="1" required>
                    </div>
                    <div class="mb-4">
                        <label for="editar_fecha_ingreso" class="block text-sm font-medium text-gray-700">Fecha de Ingreso</label>
                        <input type="date" id="editar_fecha_ingreso" name="fecha_ingreso" class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50" required>
                    </div>
                    <input type="hidden" id="editar_precio_venta" name="precio_venta">
                    <input type="hidden" id="editar_precio_costo_unidad" name="precio_costo_unidad">
                    <input type="hidden" id="editar_precio_costo_con_igv" name="precio_costo_con_igv">
                    <input type="hidden" id="editar_utilidad_esperada_unidad" name="utilidad_esperada_unidad">
                    <input type="hidden" id="editar_utilidad_esperada_total" name="utilidad_esperada_total">
                    <input type="hidden" id="editar_utilidad_neta_unidad" name="utilidad_neta_unidad">
                    <input type="hidden" id="editar_utilidad_neta_total" name="utilidad_neta_total">
                    <input type="hidden" id="editar_precio_costo_igv_paquete" name="precio_costo_igv_paquete">
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="cerrarModalEditarIngreso()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Cancelar</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const igvEdit = 0.18;
    const margenEdit = 0.20;
    function calcularCamposAutomaticosEditar() {
        const precioCostoIgv = parseFloat(document.getElementById('editar_precio_costo_igv').value) || 0;
        const cantidad = parseInt(document.getElementById('editar_cantidad_ingreso').value) || 1;
        const precioCostoUnidad = cantidad > 0 ? +(precioCostoIgv / (1 + igvEdit) / cantidad).toFixed(2) : 0;
        document.getElementById('editar_precio_costo_unidad').value = precioCostoUnidad;
        document.getElementById('editar_precio_costo_con_igv').value = +(precioCostoIgv / cantidad).toFixed(2);
        const precioVenta = +( (precioCostoIgv / cantidad) * (1 + margenEdit) ).toFixed(2);
        document.getElementById('editar_precio_venta').value = precioVenta;
        const utilidadEsperadaUnidad = +(precioVenta - precioCostoUnidad).toFixed(2);
        const utilidadEsperadaTotal = +(utilidadEsperadaUnidad * cantidad).toFixed(2);
        document.getElementById('editar_utilidad_esperada_unidad').value = utilidadEsperadaUnidad;
        document.getElementById('editar_utilidad_esperada_total').value = utilidadEsperadaTotal;
        const utilidadNetaUnidad = +(precioVenta - (precioCostoIgv / cantidad)).toFixed(2);
        const utilidadNetaTotal = +(utilidadNetaUnidad * cantidad).toFixed(2);
        document.getElementById('editar_utilidad_neta_unidad').value = utilidadNetaUnidad;
        document.getElementById('editar_utilidad_neta_total').value = utilidadNetaTotal;
        document.getElementById('editar_precio_costo_igv_paquete').value = +(precioCostoIgv).toFixed(2);
    }
    document.getElementById('editar_precio_costo_igv').addEventListener('input', calcularCamposAutomaticosEditar);
    document.getElementById('editar_cantidad_ingreso').addEventListener('input', calcularCamposAutomaticosEditar);
    document.getElementById('modalEditarIngreso').addEventListener('show', calcularCamposAutomaticosEditar);
</script> 