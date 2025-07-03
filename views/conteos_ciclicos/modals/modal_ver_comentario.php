<?php /* Modal para ver comentario */ ?>
<div id="modalComentario" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-50" onclick="cerrarModalComentario()"></div>
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-8 z-10">
        <h3 class="text-xl font-bold mb-6 text-center">Comentario</h3>
        <div id="comentarioTexto" class="mb-6 text-gray-800 whitespace-pre-line"></div>
        <div class="flex justify-end">
            <button type="button" onclick="cerrarModalComentario()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Cerrar</button>
        </div>
    </div>
</div>
<script>
function verComentario(texto) {
    document.getElementById('comentarioTexto').textContent = texto || 'Sin comentario';
    document.getElementById('modalComentario').classList.remove('hidden');
}
function cerrarModalComentario() {
    document.getElementById('modalComentario').classList.add('hidden');
}
</script> 