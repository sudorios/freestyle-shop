function abrirModal() {
    document.getElementById('modalEditarSucursal').classList.remove('hidden');
    document.getElementById('modalBackgroundEditarSucursal').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalEditarSucursal').classList.add('hidden');
    document.getElementById('modalBackgroundEditarSucursal').classList.add('hidden');
}

function abrirModalAgregarSucursal() {
    document.getElementById('modal_agregar_sucursal').classList.remove('hidden');
    document.getElementById('modalBackground').classList.remove('hidden');
}

function cerrarModalAgregarSucursal() {
    document.getElementById('modal_agregar_sucursal').classList.add('hidden');
    document.getElementById('modalBackground').classList.add('hidden');
}

function initEditarSucursal() {
    document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            const direccion = this.dataset.direccion;
            const tipo = this.dataset.tipo;
            const supervisor = this.dataset.supervisor;

            document.getElementById('edit_id_sucursal').value = id;
            document.getElementById('edit_nombre_sucursal').value = nombre;
            document.getElementById('edit_direccion_sucursal').value = direccion;
            document.getElementById('edit_tipo_sucursal').value = tipo;
            document.getElementById('edit_id_supervisor').value = supervisor;

            abrirModal();
        });
    });
}

document.addEventListener('DOMContentLoaded', initEditarSucursal); 