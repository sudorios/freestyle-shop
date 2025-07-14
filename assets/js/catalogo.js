

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

document.addEventListener('DOMContentLoaded', function() {
    initCatalogoBuscador();
}); 