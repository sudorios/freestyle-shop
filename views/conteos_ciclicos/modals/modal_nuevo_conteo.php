<div id="modalNuevoConteo" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-50" onclick="cerrarModalConteo()"></div>
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-8 z-10">
        <h3 class="text-xl font-bold mb-6 text-center">Nuevo Conteo Cíclico</h3>
        <form id="formNuevoConteo" method="POST" action="index.php?controller=conteociclico&action=registrar">
            <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto_id); ?>">
            <input type="hidden" name="id_sucursal" value="<?php echo htmlspecialchars($sucursal_id); ?>">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                <input type="text" value="<?php echo htmlspecialchars($nombre_usuario); ?>" class="uppercase mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-gray-100 text-gray-700" readonly>
            </div>
            <div class="mb-4">
                <label for="fecha_conteo" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" name="fecha_conteo" id="fecha_conteo" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad del Sistema</label>
                <input type="number" name="cantidad_sistema" value="<?php echo htmlspecialchars($cantidad_sistema); ?>" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-gray-100 text-gray-700" readonly>
            </div>
            <div class="mb-4">
                <label for="cantidad_real" class="block text-sm font-medium text-gray-700 mb-1">Cantidad Real</label>
                <input type="number" name="cantidad_real" id="cantidad_real" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition" required min="0">
            </div>
            <div class="mb-4">
                <label for="estado_conteo" class="block text-sm font-medium text-gray-700 mb-1">Estado del Conteo</label>
                <select name="estado_conteo" id="estado_conteo" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Completado">Completado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="comentarios" class="block text-sm font-medium text-gray-700 mb-1">Comentarios</label>
                <textarea name="comentarios" id="comentarios" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition"></textarea>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="cerrarModalConteo()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>
<script>
    const modalConteo = document.getElementById('modalNuevoConteo');
    document.getElementById('btnNuevoConteo').onclick = function() {
        modalConteo.classList.remove('hidden');
    };
    function cerrarModalConteo() {
        modalConteo.classList.add('hidden');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModalConteo();
    });
</script>