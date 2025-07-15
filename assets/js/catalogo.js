

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

document.addEventListener('DOMContentLoaded', function() {
    initCatalogoBuscador();
    initCatalogoModalOferta();
    initCatalogoFormValidation();

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
}); 