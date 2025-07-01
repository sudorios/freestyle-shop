const filasPorPagina = 10;
let paginaActual = 1;
let ordenAscendente = true;

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

function mostrarPaginaCategoria(pagina) {
    const filas = Array.from(document.querySelectorAll('tbody tr'));
    const filtro = document.getElementById('buscadorCategoria').value.toLowerCase();
    const filasFiltradas = filas.filter(tr => tr.textContent.toLowerCase().includes(filtro));
    filas.forEach(tr => tr.style.display = 'none');
    const inicio = (pagina - 1) * filasPorPagina;
    const fin = inicio + filasPorPagina;
    filasFiltradas.slice(inicio, fin).forEach(tr => tr.style.display = '');
    actualizarPaginacionCategoria(filasFiltradas.length, pagina);
}

function actualizarPaginacionCategoria(totalFilas, pagina) {
    const totalPaginas = Math.ceil(totalFilas / filasPorPagina) || 1;
    const paginacion = document.getElementById('paginacionCategoria');
    paginacion.innerHTML = '';

    if (totalPaginas <= 1) return;

    const btnPrev = document.createElement('button');
    btnPrev.textContent = 'Anterior';
    btnPrev.disabled = pagina === 1;
    btnPrev.className = 'px-3 py-1 rounded bg-gray-200 mx-1 disabled:opacity-50' + (btnPrev.disabled ? '' : ' cursor-pointer');
    btnPrev.onclick = () => { paginaActual--; mostrarPaginaCategoria(paginaActual); };
    paginacion.appendChild(btnPrev);

    for (let i = 1; i <= totalPaginas; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'px-3 py-1 rounded mx-1 ' + (i === pagina ? 'bg-blue-500 text-white' : 'bg-gray-200 cursor-pointer');
        btn.onclick = () => { paginaActual = i; mostrarPaginaCategoria(paginaActual); };
        paginacion.appendChild(btn);
    }

    const btnNext = document.createElement('button');
    btnNext.textContent = 'Siguiente';
    btnNext.disabled = pagina === totalPaginas;
    btnNext.className = 'px-3 py-1 rounded bg-gray-200 mx-1 disabled:opacity-50' + (btnNext.disabled ? '' : ' cursor-pointer');
    btnNext.onclick = () => { paginaActual++; mostrarPaginaCategoria(paginaActual); };
    paginacion.appendChild(btnNext);
}

function initTablaCategorias() {
    mostrarPaginaCategoria(paginaActual);
    document.getElementById('buscadorCategoria').addEventListener('input', function() {
        paginaActual = 1;
        mostrarPaginaCategoria(paginaActual);
    });
    document.getElementById('thOrdenarId').addEventListener('click', function() {
        const tbody = document.querySelector('tbody');
        const filas = Array.from(tbody.querySelectorAll('tr'));
        filas.sort((a, b) => {
            const idA = parseInt(a.children[0].textContent.trim());
            const idB = parseInt(b.children[0].textContent.trim());
            return ordenAscendente ? idA - idB : idB - idA;
        });
        filas.forEach(tr => tbody.appendChild(tr));
        ordenAscendente = !ordenAscendente;
        document.getElementById('iconoOrdenId').textContent = ordenAscendente ? '↑' : '↓';
        paginaActual = 1;
        mostrarPaginaCategoria(paginaActual);
    });
}


function categoriasInit() {
    initEditarCategoria();
    initCerrarModal();
    initTablaCategorias();
}

document.addEventListener('DOMContentLoaded', categoriasInit); 