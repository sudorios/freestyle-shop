const catalogoId = window.catalogoId;
let stockDisponible = 0;
let tallaSeleccionada = null;

function actualizarBtnCarrito(habilitar) {
    const btn = document.getElementById('btnCarrito');
    if (habilitar) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-300', 'text-gray-400', 'cursor-not-allowed');
        btn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'cursor-pointer');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-300', 'text-gray-400', 'cursor-not-allowed');
        btn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'cursor-pointer');
    }
}
actualizarBtnCarrito(false);

function actualizarStock(talla) {
    fetch(`index.php?controller=producto&action=stock&catalogo_id=${catalogoId}&talla=${encodeURIComponent(talla)}`)
        .then(res => res.json())
        .then(data => {
            const cantidadInput = document.getElementById('cantidad');
            const btnCarrito = document.getElementById('btnCarrito');
            const stockMsg = document.getElementById('stockMsg');
            if (data.success && data.cantidad > 0) {
                stockDisponible = data.cantidad;
                cantidadInput.max = stockDisponible;
                if (parseInt(cantidadInput.value) > stockDisponible) {
                    cantidadInput.value = stockDisponible;
                }
                cantidadInput.disabled = false;
                actualizarBtnCarrito(true);
                btnCarrito.textContent = 'Añadir al carrito';
                stockMsg.textContent = `Stock disponible: ${stockDisponible}`;
                stockMsg.className = 'text-sm text-green-600 mt-1';
            } else {
                stockDisponible = 0;
                cantidadInput.value = 1;
                cantidadInput.max = 1;
                cantidadInput.disabled = true;
                actualizarBtnCarrito(false);
                btnCarrito.textContent = 'Sin stock';
                stockMsg.textContent = 'Sin stock para esta talla';
                stockMsg.className = 'text-sm text-red-600 mt-1';
            }
        });
}

document.querySelectorAll('.talla-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.talla-btn').forEach(b => b.classList.remove('bg-blue-600'));
        this.classList.add('bg-blue-600');
        tallaSeleccionada = this.getAttribute('data-talla');
        actualizarStock(tallaSeleccionada);
    });
});

function cambiarCantidad(delta) {
    const input = document.getElementById('cantidad');
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (stockDisponible > 0 && val > stockDisponible) val = stockDisponible;
    input.value = val;
}

document.getElementById('formCarrito').addEventListener('submit', function (e) {
    e.preventDefault();
    if (!tallaSeleccionada || stockDisponible < 1) {
        return;
    }
    const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
    fetch('index.php?controller=carrito&action=registrar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `catalogo_id=${encodeURIComponent(catalogoId)}&talla=${encodeURIComponent(tallaSeleccionada)}&cantidad=${encodeURIComponent(cantidad)}`
    })
        .then(res => res.json())
        .then(data => {
            const msg = document.getElementById('msgCarrito');
            if (data.success) {
                msg.textContent = '¡Producto agregado al carrito exitosamente!';
                msg.classList.remove('hidden', 'text-red-600');
                msg.classList.add('text-green-600');
                if (typeof actualizarContadorCarritoAjax === 'function') actualizarContadorCarritoAjax();
            } else {
                msg.textContent = data.error || 'Error al añadir al carrito';
                msg.classList.remove('hidden', 'text-green-600');
                msg.classList.add('text-red-600');
            }
            setTimeout(() => { msg.classList.add('hidden'); }, 3000);
        })
        .catch((err) => {
            const msg = document.getElementById('msgCarrito');
            msg.textContent = 'Error: ' + (err && err.message ? err.message : err);
            msg.classList.remove('hidden', 'text-green-600');
            msg.classList.add('text-red-600');
            console.error('Error al añadir al carrito:', err);
            setTimeout(() => { msg.classList.add('hidden'); }, 4000);
        });
}); 