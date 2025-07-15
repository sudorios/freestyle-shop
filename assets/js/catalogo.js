
const filasPorPagina = 10;
let paginaActual = 1;

function initTablaCatalogo() {
  document
    .getElementById("buscadorCatalogo")
    .addEventListener("input", function () {
      paginaActual = 1;
      mostrarPaginaTabla(
        "tbody",
        "buscadorCatalogo",
        filasPorPagina,
        paginaActual,
        "paginacionCatalogo"
      );
    });
  mostrarPaginaTabla(
    "tbody",
    "buscadorCatalogo",
    filasPorPagina,
    paginaActual,
    "paginacionCatalogo"
  );
}

function initCatalogoBuscador() {
    const buscadorProducto = document.getElementById('buscador_producto');
    const sugerenciasDiv = document.getElementById('sugerencias_producto');

    if (!buscadorProducto || !sugerenciasDiv || typeof productos === 'undefined') return;

    buscadorProducto.addEventListener('input', function() {
        mostrarSugerenciasCatalogo(this.value, sugerenciasDiv, buscadorProducto);
    });

    buscadorProducto.addEventListener('focus', function() {
        if (this.value.trim().length > 0) {
            mostrarSugerenciasCatalogo(this.value, sugerenciasDiv, buscadorProducto);
        }
    });

    buscadorProducto.addEventListener('blur', function() {
        setTimeout(() => sugerenciasDiv.classList.add('hidden'), 120); // Permite click en sugerencia
    });
}

function mostrarSugerenciasCatalogo(valor, sugerenciasDiv, buscadorProducto) {
    const filtro = valor.trim().toLowerCase();
    sugerenciasDiv.innerHTML = '';
    if (filtro.length === 0) {
        sugerenciasDiv.classList.add('hidden');
        return;
    }
    const coincidencias = productos.filter(p => p.nombre_producto.toLowerCase().includes(filtro));
    if (coincidencias.length === 0) {
        sugerenciasDiv.classList.add('hidden');
        return;
    }
    coincidencias.forEach(p => {
        const div = document.createElement('div');
        div.className = 'px-3 py-2 cursor-pointer hover:bg-blue-100 border-b last:border-b-0';
        div.textContent = p.nombre_producto + (p.talla_producto ? ' (Talla: ' + p.talla_producto + ')' : '');
        div.addEventListener('mousedown', function(e) {
            e.preventDefault();
            seleccionarProductoCatalogo(p, buscadorProducto, sugerenciasDiv);
        });
        sugerenciasDiv.appendChild(div);
    });
    sugerenciasDiv.classList.remove('hidden');
}

function seleccionarProductoCatalogo(producto, buscadorProducto, sugerenciasDiv) {
    buscadorProducto.value = producto.nombre_producto;
    document.getElementById('producto_id').value = producto.id_producto;
    document.getElementById('ingreso_id').value = producto.ingreso_id;
    document.getElementById('imagen_id').value = producto.imagen_id;
    document.getElementById('precio_venta_display').innerText = producto.precio_venta ? '$' + producto.precio_venta : 'Seleccione un producto para ver el precio';
    var imgElement = document.getElementById('imagen_producto');
    var placeholder = document.getElementById('placeholder_img');
    if (producto.url_imagen) {
        imgElement.src = producto.url_imagen;
        imgElement.style.display = '';
        placeholder.style.display = 'none';
    } else {
        imgElement.src = '';
        imgElement.style.display = 'none';
        placeholder.style.display = '';
    }
    const infoProducto = document.getElementById('info_producto');
    if (producto.descripcion_producto && producto.talla_producto) {
        infoProducto.innerHTML = `
            <p><strong>Descripción:</strong> ${producto.descripcion_producto}</p>
            <p><strong>Talla:</strong> ${producto.talla_producto}</p>
            <p><strong>Precio:</strong> $${producto.precio_venta ? producto.precio_venta : '-'}</p>
        `;
    } else {
        infoProducto.innerHTML = '<p>Selecciona un producto para ver su información detallada</p>';
    }
    sugerenciasDiv.classList.add('hidden');
}

function actualizarOfertaSelectUI() {
    const estadoOfertaInput = document.getElementById('estado_oferta');
    const limiteOfertaInput = document.getElementById('limite_oferta');
    const ofertaInput = document.getElementById('oferta');
    if (!estadoOfertaInput || !limiteOfertaInput || !ofertaInput) return;
    if (estadoOfertaInput.value === 'true') {
        limiteOfertaInput.disabled = false;
        ofertaInput.disabled = false;
        limiteOfertaInput.classList.remove('bg-gray-100', 'text-gray-400');
        ofertaInput.classList.remove('bg-gray-100', 'text-gray-400');
    } else {
        limiteOfertaInput.disabled = true;
        ofertaInput.disabled = true;
        limiteOfertaInput.classList.add('bg-gray-100', 'text-gray-400');
        ofertaInput.classList.add('bg-gray-100', 'text-gray-400');
    }
}

function initCatalogoModalOferta() {
    const estadoOfertaInput = document.getElementById('estado_oferta');
    const limiteOfertaDiv = document.getElementById('limite_oferta')?.parentElement;
    const ofertaDiv = document.getElementById('oferta')?.parentElement;
    const limiteOferta = document.getElementById('limite_oferta');
    const oferta = document.getElementById('oferta');

    if (!estadoOfertaInput || !limiteOfertaDiv || !ofertaDiv || !limiteOferta || !oferta) return;

    function actualizarOfertaUI() {
        if (estadoOfertaInput.value === 'true') {
            limiteOfertaDiv.style.display = 'block';
            ofertaDiv.style.display = 'block';
            limiteOferta.required = true;
            oferta.required = true;
        } else {
            limiteOfertaDiv.style.display = 'none';
            ofertaDiv.style.display = 'none';
            limiteOferta.required = false;
            oferta.required = false;
        }
    }

    estadoOfertaInput.addEventListener('change', actualizarOfertaUI);
    actualizarOfertaUI();
}

function initCatalogoFormValidation() {
    const form = document.getElementById('formAgregarCatalogo');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        const estadoOferta = document.getElementById('estado_oferta').value;
        const limiteOferta = document.getElementById('limite_oferta').value;
        const oferta = document.getElementById('oferta').value;

        if (estadoOferta === 'true') {
            if (!limiteOferta) {
                e.preventDefault();
                alert('La fecha límite de oferta es requerida cuando está en oferta');
                return false;
            }
            if (!oferta) {
                e.preventDefault();
                alert('El porcentaje de descuento es requerido cuando está en oferta');
                return false;
            }
            if (oferta < 0 || oferta > 100) {
                e.preventDefault();
                alert('El porcentaje de descuento debe estar entre 0 y 100');
                return false;
            }
        }
    });
}

function abrirModalAgregarCatalogo() {
    document.getElementById('modalBackgroundAgregarCatalogo').classList.remove('hidden');
    document.getElementById('modalAgregarCatalogo').classList.remove('hidden');
}

function cerrarModalAgregarCatalogo() {
    document.getElementById('modalBackgroundAgregarCatalogo').classList.add('hidden');
    document.getElementById('modalAgregarCatalogo').classList.add('hidden');
}

function mostrarImagenModal(url) {
    document.getElementById('imagenModalGrande').src = url;
    document.getElementById('modalImagen').classList.remove('hidden');
}

function cerrarModalImagen() {
    document.getElementById('modalImagen').classList.add('hidden');
    document.getElementById('imagenModalGrande').src = '';
}

function filtrarTablaCatalogo() {
    const buscador = document.getElementById('buscadorCatalogo');
    const filtroEstadoOferta = document.getElementById('filtroEstadoOferta');
    const filtroPrecioMin = document.getElementById('filtroPrecioMin');
    const filtroPrecioMax = document.getElementById('filtroPrecioMax');
    const filtroLimiteOferta = document.getElementById('filtroLimiteOferta');
    const tabla = document.querySelector('table');
    if (!tabla) return;

    const texto = buscador.value.toLowerCase();
    const estadoOferta = filtroEstadoOferta.value;
    const precioMin = parseFloat(filtroPrecioMin.value) || 0;
    const precioMax = parseFloat(filtroPrecioMax.value) || Infinity;
    const limiteOferta = filtroLimiteOferta.value;

    Array.from(tabla.tBodies[0].rows).forEach(row => {
        const nombre = row.cells[1].textContent.toLowerCase();
        const precio = parseFloat(row.cells[2].textContent.replace(/[^\d.]/g, '')) || 0;
        const estado = row.cells[4].textContent.includes('En Oferta') ? 'true' : (row.cells[4].textContent.includes('Sin oferta') ? 'false' : '');
        const limite = row.cells[4].querySelector('small')?.textContent.match(/\d{2}\/\d{2}\/\d{4}/)?.[0] || '';

        let visible = true;
        if (texto && !nombre.includes(texto)) visible = false;
        if (estadoOferta && estado !== estadoOferta) visible = false;
        if (precio < precioMin || precio > precioMax) visible = false;
        if (limiteOferta && limite !== '' && !fechaIgual(limite, limiteOferta)) visible = false;

        row.style.display = visible ? '' : 'none';
    });
}

function fechaIgual(fechaStr, inputDate) {
    if (!fechaStr || !inputDate) return false;
    const [dia, mes, anio] = fechaStr.split('/');
    const [inputAnio, inputMes, inputDia] = inputDate.split('-');
    return parseInt(dia, 10) === parseInt(inputDia, 10) &&
           parseInt(mes, 10) === parseInt(inputMes, 10) &&
           parseInt(anio, 10) === parseInt(inputAnio, 10);
}

document.addEventListener('DOMContentLoaded', function() {
    initCatalogoBuscador();
    initCatalogoModalOferta();
    initCatalogoFormValidation();
    initTablaCatalogo();

    const bgAgregar = document.getElementById('modalBackgroundAgregarCatalogo');
    if (bgAgregar) {
        bgAgregar.addEventListener('click', cerrarModalAgregarCatalogo);
    }

    const modalImagen = document.getElementById('modalImagen');
    if (modalImagen) {
        modalImagen.addEventListener('click', function(e) {
            if (e.target === modalImagen) {
                cerrarModalImagen();
            }
        });
    }
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalImagen();
        }
    });
    const estadoOfertaInput = document.getElementById('estado_oferta');
    if (estadoOfertaInput) {
        estadoOfertaInput.addEventListener('change', actualizarOfertaSelectUI);
        actualizarOfertaSelectUI();
    }
    const btnFiltrar = document.getElementById('btnFiltrarCatalogo');
    const buscador = document.getElementById('buscadorCatalogo');
    const filtroEstadoOferta = document.getElementById('filtroEstadoOferta');
    const filtroPrecioMin = document.getElementById('filtroPrecioMin');
    const filtroPrecioMax = document.getElementById('filtroPrecioMax');
    const filtroLimiteOferta = document.getElementById('filtroLimiteOferta');
    if (btnFiltrar) btnFiltrar.addEventListener('click', filtrarTablaCatalogo);
    [buscador, filtroEstadoOferta, filtroPrecioMin, filtroPrecioMax, filtroLimiteOferta].forEach(el => {
        if (el) {
            el.addEventListener('change', filtrarTablaCatalogo);
            el.addEventListener('input', filtrarTablaCatalogo);
        }
    });

    // Toggle estado catálogo
    document.querySelectorAll('table .fa-toggle-on, table .fa-toggle-off').forEach(function(btn) {
        btn.parentElement.addEventListener('click', function(e) {
            e.preventDefault();
            const row = btn.closest('tr');
            const id = row ? row.dataset.id : null;
            if (!id) return;
            const estadoActual = btn.classList.contains('fa-toggle-on');
            fetch('/freestyle-shop/views/catalogo/catalogo_desactivar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${encodeURIComponent(id)}&estado=${estadoActual}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let estadoCell = null;
                    row.querySelectorAll('td').forEach(td => {
                        if (td.textContent.includes('Activo') || td.textContent.includes('Inactivo')) {
                            estadoCell = td;
                        }
                    });
                    if (estadoCell) {
                        const estadoSpan = estadoCell.querySelector('span');
                        if (estadoActual) {
                            btn.classList.remove('fa-toggle-on', 'text-green-600', 'hover:text-green-800');
                            btn.classList.add('fa-toggle-off', 'text-gray-400', 'hover:text-green-600');
                            if (estadoSpan) {
                                estadoSpan.className = 'text-red-600';
                                estadoSpan.textContent = 'Inactivo';
                            }
                        } else {
                            btn.classList.remove('fa-toggle-off', 'text-gray-400', 'hover:text-green-600');
                            btn.classList.add('fa-toggle-on', 'text-green-600', 'hover:text-green-800');
                            if (estadoSpan) {
                                estadoSpan.className = 'text-green-600';
                                estadoSpan.textContent = 'Activo';
                            }
                        }
                    }
                } else {
                    alert('Error al actualizar el estado: ' + data.msg);
                }
            });
        });
    });
}); 