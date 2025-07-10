<?php
session_start();
include_once './includes/head.php';
include_once './includes_client/header.php';
?>
<body class="bg-gray-100 min-h-screen">
    <main class="container mx-auto py-10 px-4 flex flex-col lg:flex-row gap-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">TU CARRITO</h1>
            <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                <span class="font-bold">OFERTAS CYBERNÉTICAS CON ENVÍO GRATIS</span><br>
                <span class="text-gray-600 text-sm">Los artículos de tu carrito no están reservados, realiza tu compra antes que se agoten.</span>
            </div>
            <div id="carrito-lista"></div>
        </div>
        <aside class="w-full lg:w-96 flex-shrink-0">
            <div class="lg:sticky top-24 bg-white rounded-lg shadow p-6" id="carrito-resumen">
            </div>
        </aside>
    </main>
    <?php include_once './includes/footer.php'; ?>
    <script>
    function renderCarrito() {
        fetch('carrito_datos.php')
            .then(res => res.json())
            .then(data => {
                const lista = document.getElementById('carrito-lista');
                if (!data.items || data.items.length === 0) {
                    lista.innerHTML = '<div class="text-center text-gray-500 text-lg">Tu carrito está vacío.</div>';
                } else {
                    let html = '';
                    data.items.forEach(item => {
                        html += `
                        <div class="flex flex-col md:flex-row items-center bg-white rounded-lg shadow mb-6 overflow-hidden" data-item-id="${item.id}">
                            <div class="flex-shrink-0 w-full md:w-56 h-56 flex items-center justify-center bg-gray-50">
                                <img src="${item.url_imagen || 'https://via.placeholder.com/80x80?text=Producto'}" alt="img" class="object-contain w-full h-full" />
                            </div>
                            <div class="flex-1 w-full p-6 flex flex-col gap-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-lg font-bold text-gray-900">${item.nombre_producto}</div>
                                        <div class="text-sm text-gray-600">Talla: <span class="font-semibold">${item.talla}</span></div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-gray-400 line-through">S/ ${Number(item.precio_venta).toFixed(2)}</span>
                                            <span class="text-pink-600 font-bold">S/ ${Number(item.precio_con_descuento).toFixed(2)}</span>
                                            <span class="text-green-500 font-semibold text-sm">-${Number(item.oferta).toFixed(2)}% OFF</span>
                                        </div>
                                    </div>
                                    <button class="eliminar-item-carrito text-2xl text-gray-400 hover:text-red-600 ml-4" title="Eliminar">&times;</button>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-sm">Cantidad:</span>
                                    <button class="cambiar-cantidad-carrito w-8 h-8 rounded bg-gray-200 hover:bg-gray-300" data-delta="-1">-</button>
                                    <input type="number" min="1" class="input-cantidad-carrito w-12 text-center border mx-1 rounded" value="${item.cantidad}" />
                                    <button class="cambiar-cantidad-carrito w-8 h-8 rounded bg-gray-200 hover:bg-gray-300" data-delta="1">+</button>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                    lista.innerHTML = html;
                }
                const resumen = document.getElementById('carrito-resumen');
                resumen.innerHTML = `
                    <h2 class="text-2xl font-bold mb-4">RESUMEN DEL PEDIDO</h2>
                    <div class="flex justify-between mb-2 text-gray-700"><span>${data.cantidadTotal} productos</span><span>S/ ${Number(data.total).toFixed(2)}</span></div>
                    <div class="flex justify-between mb-2 text-gray-500 text-sm"><span>Precio original</span><span>S/ ${Number(data.totalOriginal).toFixed(2)}</span></div>
                    <div class="flex justify-between mb-2 text-gray-500 text-sm"><span>Entrega</span><span>S/ 15.00</span></div>
                    <div class="mb-2 text-sm text-gray-700 font-semibold"><span>${data.total >= 99 ? '¡Disfruta del envío gratuito de tu pedido!' : 'Gasta S/ 99.00 más y disfruta del envío gratuito de tu pedido'}</span></div>
                    <div class="flex justify-between mb-2 text-gray-500 text-sm"><span>Descuento</span><span>- S/ ${Number(data.totalDescuento).toFixed(2)}</span></div>
                    <div class="flex justify-between mt-4 text-xl font-bold"><span>Total</span><span>S/ ${(data.total >= 99 ? Number(data.total) : Number(data.total) + 15).toFixed(2)}</span></div>
                    <div class="text-xs text-gray-400 mb-4">(IGV incluido)</div>
                    <button class="w-full bg-black hover:bg-gray-900 text-white font-bold py-3 rounded text-lg flex items-center justify-center gap-2 mb-4">IR A PAGAR <span class="ml-2">→</span></button>
                    <div class="flex gap-2 mt-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa" class="h-6" />
                    </div>
                `;
                asignarEventosCarrito();
                if (typeof actualizarContadorCarritoAjax === 'function') actualizarContadorCarritoAjax();
            });
    }
    function asignarEventosCarrito() {
        document.querySelectorAll('.eliminar-item-carrito').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const itemDiv = this.closest('[data-item-id]');
                const itemId = itemDiv.getAttribute('data-item-id');
                if (!itemId) return;
                if (!confirm('¿Seguro que deseas eliminar este producto del carrito?')) return;
                fetch('eliminar_del_carrito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'item_id=' + encodeURIComponent(itemId)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderCarrito();
                    } else {
                        alert(data.error || 'No se pudo eliminar el producto');
                    }
                })
                .catch(() => alert('Error de conexión con el servidor'));
            });
        });
        document.querySelectorAll('.cambiar-cantidad-carrito').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const itemDiv = this.closest('[data-item-id]');
                const itemId = itemDiv.getAttribute('data-item-id');
                const input = itemDiv.querySelector('.input-cantidad-carrito');
                let cantidad = parseInt(input.value) || 1;
                const delta = parseInt(this.getAttribute('data-delta'));
                cantidad = Math.max(1, cantidad + delta);
                actualizarCantidadCarrito(itemId, cantidad, input);
            });
        });
        document.querySelectorAll('.input-cantidad-carrito').forEach(function(input) {
            input.addEventListener('change', function() {
                const itemDiv = this.closest('[data-item-id]');
                const itemId = itemDiv.getAttribute('data-item-id');
                let cantidad = parseInt(this.value) || 1;
                cantidad = Math.max(1, cantidad);
                actualizarCantidadCarrito(itemId, cantidad, this);
            });
        });
    }
    function actualizarCantidadCarrito(itemId, cantidad, inputElem) {
        fetch('actualizar_cantidad_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'item_id=' + encodeURIComponent(itemId) + '&cantidad=' + encodeURIComponent(cantidad)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderCarrito();
            } else {
                alert(data.error || 'No se pudo actualizar la cantidad');
            }
        })
        .catch(() => alert('Error de conexión con el servidor'));
    }
    document.addEventListener('DOMContentLoaded', renderCarrito);
    </script>
</body> 