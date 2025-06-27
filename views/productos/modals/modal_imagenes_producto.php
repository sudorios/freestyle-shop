<?php
?>
<div id="modalBackgroundImagenesProducto" class="fixed inset-0 hidden bg-black opacity-50 z-40"></div>
<div id="modalImagenesProducto" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-lg rounded-md bg-white">
        <button onclick="cerrarModalImagenesProducto()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        <h3 class="text-xl font-bold mb-4">Imágenes del Producto</h3>
        <button id="btnMostrarFormImagen" class="hidden mb-4 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
            <i class="fas fa-sync-alt"></i> Cambiar imágenes
        </button>
        <form id="formNuevaImagenProducto" class="mb-4 flex flex-col gap-2 hidden">
            <input type="hidden" name="id_producto" id="idProductoImagenForm">
            <label class="block text-sm font-semibold">URL de la imagen (Cloudinary)</label>
            <input type="url" name="url_imagen" id="urlImagenInput" class="border rounded px-3 py-2 w-full" placeholder="https://res.cloudinary.com/..." required>
            <label class="block text-sm font-semibold mt-2">Tipo de vista</label>
            <select name="vista_producto" id="vistaProductoInput" class="border rounded px-3 py-2 w-full" required>
                <option value="1">Parte Frontal</option>
                <option value="2">Parte Posterior</option>
            </select>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded mt-2">Subir Imagen</button>
        </form>
        <div id="listaImagenesProducto" class="mt-4">
            <p class="text-gray-500">Cargando imágenes...</p>
        </div>
    </div>
</div>
<script>
function mostrarFormImagenProducto() {
    document.getElementById('formNuevaImagenProducto').classList.remove('hidden');
    document.getElementById('btnMostrarFormImagen').classList.add('hidden');
}
document.getElementById('btnMostrarFormImagen').onclick = mostrarFormImagenProducto;
</script> 