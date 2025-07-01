const filasPorPaginaSub = 10;
let paginaActualSub = 1;
let ordenAscendenteSub = true;

function mostrarPaginaSubcategoria(pagina) {
    const filas = Array.from(document.querySelectorAll('tbody tr'));
    const filtro = document.getElementById('buscadorSubcategoria').value.toLowerCase();
    const filasFiltradas = filas.filter(tr => tr.textContent.toLowerCase().includes(filtro));
    filas.forEach(tr => tr.style.display = 'none');
    const inicio = (pagina - 1) * filasPorPaginaSub;
    const fin = inicio + filasPorPaginaSub;
    filasFiltradas.slice(inicio, fin).forEach(tr => tr.style.display = '');
    actualizarPaginacionSubcategoria(filasFiltradas.length, pagina);
}

function actualizarPaginacionSubcategoria(totalFilas, pagina) {
    const totalPaginas = Math.ceil(totalFilas / filasPorPaginaSub) || 1;
    const paginacion = document.getElementById('paginacionSubcategoria');
    paginacion.innerHTML = '';
    if (totalPaginas <= 1) return;
    const btnPrev = document.createElement('button');
    btnPrev.textContent = 'Anterior';
    btnPrev.disabled = pagina === 1;
    btnPrev.className = 'px-3 py-1 rounded bg-gray-200 mx-1 disabled:opacity-50' + (btnPrev.disabled ? '' : ' cursor-pointer');
    btnPrev.onclick = () => { paginaActualSub--; mostrarPaginaSubcategoria(paginaActualSub); };
    paginacion.appendChild(btnPrev);
    for (let i = 1; i <= totalPaginas; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'px-3 py-1 rounded mx-1 ' + (i === pagina ? 'bg-blue-500 text-white' : 'bg-gray-200 cursor-pointer');
        btn.onclick = () => { paginaActualSub = i; mostrarPaginaSubcategoria(paginaActualSub); };
        paginacion.appendChild(btn);
    }
    const btnNext = document.createElement('button');
    btnNext.textContent = 'Siguiente';
    btnNext.disabled = pagina === totalPaginas;
    btnNext.className = 'px-3 py-1 rounded bg-gray-200 mx-1 disabled:opacity-50' + (btnNext.disabled ? '' : ' cursor-pointer');
    btnNext.onclick = () => { paginaActualSub++; mostrarPaginaSubcategoria(paginaActualSub); };
    paginacion.appendChild(btnNext);
}

function initBuscadorSubcategoria() {
    document.getElementById('buscadorSubcategoria').addEventListener('input', function() {
        paginaActualSub = 1;
        mostrarPaginaSubcategoria(paginaActualSub);
    });
}

function initOrdenarIdSubcategoria() {
    document.getElementById('thOrdenarIdSub').addEventListener('click', function() {
        const tbody = document.querySelector('tbody');
        const filas = Array.from(tbody.querySelectorAll('tr'));
        filas.sort((a, b) => {
            const idA = parseInt(a.children[0].textContent.trim());
            const idB = parseInt(b.children[0].textContent.trim());
            return ordenAscendenteSub ? idA - idB : idB - idA;
        });
        filas.forEach(tr => tbody.appendChild(tr));
        ordenAscendenteSub = !ordenAscendenteSub;
        document.getElementById('iconoOrdenIdSub').textContent = ordenAscendenteSub ? '↑' : '↓';
        paginaActualSub = 1;
        mostrarPaginaSubcategoria(paginaActualSub);
    });
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

            abrirModal();
        });
    });
}

function initFormEditarSubcategoria() {
    $('#formEditarSubcategoria').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'views/subcategorias/subcategoria_edit.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                location.reload();
            }
        });
    });
}


function abrirModal() {
    document.getElementById('modalEditarSubcategoria').classList.remove('hidden');
    document.getElementById('modalBackgroundEditar').classList.remove('hidden');
}

function cerrarModal() {
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
    mostrarPaginaSubcategoria(paginaActualSub);
    initBuscadorSubcategoria();
    initOrdenarIdSubcategoria();
    initEditarSubcategoria();
    initFormEditarSubcategoria();
}

document.addEventListener('DOMContentLoaded', subcategoriasInit); 