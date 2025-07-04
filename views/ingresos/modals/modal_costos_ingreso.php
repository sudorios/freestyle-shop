<div id="modalBackgroundCostos" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modal_costos_ingreso" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30 flex items-center justify-center">
    <div class="relative top-10 mx-auto p-8 border w-[600px] shadow-2xl rounded-xl bg-white">
        <div class="mt-2">
            <h4 class="text-xl font-bold leading-6 text-gray-900 mb-4 border-b pb-2">Detalle de Ingreso</h4>
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-between mb-1"><span class="font-semibold text-gray-800">Referencia:</span> <span id="costos_ref" class="text-gray-900"></span></div>
                <div class="flex justify-between mb-1"><span class="font-semibold text-gray-800">Producto:</span> <span id="costos_producto" class="text-gray-900"></span></div>
                <div class="flex justify-between mb-1"><span class="font-semibold text-gray-800">Sucursal:</span> <span id="costos_sucursal" class="text-gray-900"></span></div>
                <div class="flex justify-between mb-1"><span class="font-semibold text-gray-800">Cantidad:</span> <span id="costos_cantidad" class="text-gray-900"></span></div>
                <div class="flex justify-between"><span class="font-semibold text-gray-800">Usuario:</span> <span id="costos_usuario" class="text-gray-900"></span></div>
            </div>
            <h5 class="text-md font-semibold text-gray-900 mb-2 mt-4 border-b pb-1">Costos y Precios</h5>
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-between mb-1"><span class="font-medium text-gray-800">Costo Total (c/IGV):</span> <span id="costos_precio_costo_igv" class="text-gray-900"></span></div>
                <div class="flex justify-between mb-1"><span class="font-medium text-gray-800">Precio Venta Unidad:</span> <span id="costos_precio_venta" class="text-gray-900"></span></div>
                <div class="flex justify-between mb-1"><span class="font-medium text-gray-800">Utilidad Esperada:</span> <span id="costos_utilidad_esperada" class="text-gray-900"></span></div>
                <div class="flex justify-between"><span class="font-medium text-gray-800">Utilidad Neta:</span> <span id="costos_utilidad_neta" class="text-gray-900"></span></div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="cerrarModalCostos()" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-6 rounded-lg shadow">Cerrar</button>
            </div>
        </div>
    </div>
</div> 