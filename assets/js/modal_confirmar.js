let confirmarCallback = null;

function abrirModalConfirmar({mensaje, action, id, idField = 'id'}) {
    document.getElementById('modalConfirmarMensaje').textContent = mensaje || '¿Seguro que deseas continuar?';
    document.getElementById('formConfirmar').action = action;
    document.getElementById('modalConfirmarId').name = idField;
    document.getElementById('modalConfirmarId').value = id;
    document.getElementById('modalConfirmar').classList.remove('hidden');
    document.getElementById('modalConfirmarBackground').classList.remove('hidden');
}
function cerrarModalConfirmar() {
    document.getElementById('modalConfirmar').classList.add('hidden');
    document.getElementById('modalConfirmarBackground').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const btnSi = document.getElementById('btnConfirmarSi');
    if (btnSi) {
        btnSi.onclick = function() {
            cerrarModalConfirmar();
            if (confirmarCallback) confirmarCallback();
        };
    }
}); 