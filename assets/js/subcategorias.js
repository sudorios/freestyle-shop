const filasPorPaginaSub = 10;
let paginaActualSub = 1;
let ordenAscendenteSub = true;

function initTablaSubcategoria() {
    document.getElementById('buscadorSubcategoria').addEventListener('input', function() {
        paginaActualSub = 1;
        mostrarPaginaTabla('tbody', 'buscadorSubcategoria', filasPorPaginaSub, paginaActualSub, 'paginacionSubcategoria');
    });
    mostrarPaginaTabla('tbody', 'buscadorSubcategoria', filasPorPaginaSub, paginaActualSub, 'paginacionSubcategoria');
}

function initEditarSubcategoria() {
    document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            const descripcion = this.dataset.descripcion;
            const categoria = this.dataset.categoria;

            document.getElementById('edit_id_subcategoria').value = id;
            document.getElementById('edit_nombre_subcategoria').value = nombre;
            document.getElementById('edit_descripcion_subcategoria').value = descripcion;
            document.getElementById('edit_id_categoria').value = categoria;

            abrirModalEditarSubcategoria();
        });
    });
}


function abrirModalEditarSubcategoria() {
    document.getElementById('modalEditarSubcategoria').classList.remove('hidden');
    document.getElementById('modalBackgroundEditar').classList.remove('hidden');
}

function cerrarModalEditarSubcategoria() {
    document.getElementById('modalEditarSubcategoria').classList.add('hidden');
    document.getElementById('modalBackgroundEditar').classList.add('hidden');
}

function abrirModalAgregarSubcategoria() {
    document.getElementById('modalAgregarSubcategoria').classList.remove('hidden');
    document.getElementById('modalBackgroundAgregar').classList.remove('hidden');
}

function cerrarModalAgregarSubcategoria() {
    document.getElementById('modalAgregarSubcategoria').classList.add('hidden');
    document.getElementById('modalBackgroundAgregar').classList.add('hidden');
}

function subcategoriasInit() {
    initTablaSubcategoria();
    initEditarSubcategoria();
}

document.addEventListener('DOMContentLoaded', subcategoriasInit); 