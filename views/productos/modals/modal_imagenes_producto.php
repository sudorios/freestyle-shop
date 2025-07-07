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
            <label class="block text-sm font-semibold">Sube una imagen (Drag & Drop o haz clic)</label>
            <div id="dropArea" class="border-2 border-dashed border-indigo-400 rounded p-6 text-center cursor-pointer bg-gray-50 hover:bg-indigo-50 transition">
                <span id="dropText" class="text-gray-500">Arrastra una imagen aquí o haz clic para seleccionar</span>
                <input type="file" id="fileInput" name="file" accept="image/*" class="hidden" required>
            </div>
            <label class="block text-sm font-semibold mt-2">Tipo de vista</label>
            <select name="vista_producto" id="vistaProductoInput" class="border rounded px-3 py-2 w-full" required>
                <option value="1">Parte Frontal</option>
                <option value="2">Parte Posterior</option>
            </select>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded mt-2">Subir Imagen</button>
            <div id="preview" class="mt-2"></div>
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

// Drag & Drop y preview
const dropArea = document.getElementById('dropArea');
const fileInput = document.getElementById('fileInput');
const dropText = document.getElementById('dropText');
const preview = document.getElementById('preview');

dropArea.addEventListener('click', () => fileInput.click());
dropArea.addEventListener('dragover', e => {
    e.preventDefault();
    dropArea.classList.add('bg-indigo-100');
});
dropArea.addEventListener('dragleave', e => {
    e.preventDefault();
    dropArea.classList.remove('bg-indigo-100');
});
dropArea.addEventListener('drop', e => {
    e.preventDefault();
    dropArea.classList.remove('bg-indigo-100');
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        mostrarPreview(fileInput.files[0]);
    }
});
fileInput.addEventListener('change', () => {
    if (fileInput.files.length) {
        mostrarPreview(fileInput.files[0]);
    }
});

function mostrarPreview(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        preview.innerHTML = `<img src='${e.target.result}' class='w-32 h-32 object-cover rounded mx-auto'/>`;
    };
    reader.readAsDataURL(file);
}

document.getElementById('formNuevaImagenProducto').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!fileInput.files.length) {
        alert('Selecciona una imagen');
        return false;
    }
    const form = e.target;
    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('id_producto', document.getElementById('idProductoImagenForm').value);
    formData.append('vista_producto', document.getElementById('vistaProductoInput').value);
    
    // Feedback de carga
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Subiendo...';
    preview.innerHTML += '<div class="text-sm text-gray-400 mt-2">Subiendo imagen...</div>';

    fetch('views/productos/subir_imagen_producto.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = 'Subir Imagen';
        if (data.success) {
            preview.innerHTML = '';
            fileInput.value = '';
            cargarImagenesProducto();
            alert('Imagen subida correctamente');
        } else {
            preview.innerHTML = '';
            alert('Error: ' + (data.error || 'No se pudo subir la imagen.'));
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.textContent = 'Subir Imagen';
        preview.innerHTML = '';
        alert('Error de red al subir la imagen.');
    });
});

function cargarImagenesProducto() {
    const id = document.getElementById('idProductoImagenForm').value;
    const cont = document.getElementById('listaImagenesProducto');
    cont.innerHTML = '<p class="text-gray-500">Cargando imágenes...</p>';
    fetch('views/productos/obtener_imagenes_producto.php?id_producto=' + encodeURIComponent(id))
        .then(res => res.text())
        .then(html => cont.innerHTML = html);
}
</script> 