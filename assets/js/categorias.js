let paginaActualCategoria = 1;
const filasPorPaginaCategoria = 10;

function abrirModal() {
    document.getElementById('modalEditar').classList.remove('hidden');
    document.getElementById('modalBackground').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalEditar').classList.add('hidden');
    document.getElementById('modalBackground').classList.add('hidden');
}

function abrirModalAgregarCategoria() {
    document.getElementById('modal_agregar_categoria').classList.remove('hidden');
    document.getElementById('modalBackground').classList.remove('hidden');
}

function cerrarModalAgregarCategoria() {
    document.getElementById('modal_agregar_categoria').classList.add('hidden');
    document.getElementById('modalBackground').classList.add('hidden');
}

function initEditarCategoria() {
    document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            const descripcion = this.dataset.descripcion;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_descripcion').value = descripcion;

            abrirModal();
        });
    });
}

function initCerrarModal() {
    document.getElementById('modalEditar').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });
    
    document.getElementById('modal_agregar_categoria').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalAgregarCategoria();
        }
    });
}

function initTablaCategoria() {
    document.getElementById('buscadorCategoria').addEventListener('input', function() {
        paginaActualCategoria = 1;
        mostrarPaginaTabla('tbody', 'buscadorCategoria', filasPorPaginaCategoria, paginaActualCategoria, 'paginacionCategoria');
    });
    mostrarPaginaTabla('tbody', 'buscadorCategoria', filasPorPaginaCategoria, paginaActualCategoria, 'paginacionCategoria');
}

function categoriasInit() {
    initEditarCategoria();
    initCerrarModal();
    initTablaCategoria();
}

document.addEventListener('DOMContentLoaded', categoriasInit); 