<?php include_once './includes/head.php'; ?>
<?php include_once './includes_client/header.php'; ?>
<body class="bg-gray-100 min-h-screen">
    <main class="container mx-auto py-10 px-4 flex flex-col lg:flex-row gap-8">
        <!-- Columna principal -->
        <div class="flex-1 min-w-0">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">TU CARRITO</h1>
            <div id="carritoResumen" class="mb-4 text-lg text-gray-700 font-semibold"></div>
            <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                <span class="font-bold">OFERTAS CYBERNÉTICAS CON ENVÍO GRATIS</span><br>
                <span class="text-gray-600 text-sm">Los artículos de tu carrito no están reservados, realiza tu compra antes que se agoten.</span>
            </div>
            <div id="carritoVacio" class="text-center text-gray-500 text-lg hidden">Tu carrito está vacío.</div>
            <div id="carritoLista"></div>
        </div>
        <!-- Resumen sticky -->
        <aside class="w-full lg:w-96 flex-shrink-0">
            <div class="lg:sticky top-24 bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">RESUMEN DEL PEDIDO</h2>
                <div class="flex justify-between mb-2 text-gray-700"><span id="resumenCantidad">0 productos</span><span id="resumenTotal">S/ 0.00</span></div>
                <div class="flex justify-between mb-2 text-gray-500 text-sm"><span>Precio original</span><span id="resumenOriginal">S/ 0.00</span></div>
                <div class="flex justify-between mb-2 text-gray-500 text-sm"><span>Entrega</span><span id="resumenEnvio">S/ 15.00</span></div>
                <div class="mb-2 text-sm text-gray-700 font-semibold"><span id="envioGratisMsg">Gasta S/ 99.00 más y disfruta del envío gratuito de tu pedido</span></div>
                <div class="flex justify-between mb-2 text-gray-500 text-sm"><span>Descuento</span><span id="resumenDescuento">- S/ 0.00</span></div>
                <div class="flex justify-between mt-4 text-xl font-bold"><span>Total</span><span id="resumenTotalFinal">S/ 0.00</span></div>
                <div class="text-xs text-gray-400 mb-4">(IGV incluido)</div>
                <div class="mb-4">
                    <input type="text" placeholder="Código promocional" class="w-full border rounded px-3 py-2 mb-2" />
                    <button class="underline text-sm font-semibold">Usa un código promocional</button>
                </div>
                <button class="w-full bg-black hover:bg-gray-900 text-white font-bold py-3 rounded text-lg flex items-center justify-center gap-2 mb-4">IR A PAGAR <span class="ml-2">→</span></button>
                <div class="flex gap-2 mt-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa" class="h-6" />
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/0e/Mastercard-logo.png" alt="Mastercard" class="h-6" />
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/30/American_Express_logo_%282018%29.svg" alt="Amex" class="h-6" />
                </div>
            </div>
        </aside>
    </main>
    <?php include_once './includes/footer.php'; ?>
    <script>
    function renderCarrito() {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const lista = document.getElementById('carritoLista');
        const vacio = document.getElementById('carritoVacio');
        const resumenCantidad = document.getElementById('resumenCantidad');
        const resumenTotal = document.getElementById('resumenTotal');
        const resumenOriginal = document.getElementById('resumenOriginal');
        const resumenDescuento = document.getElementById('resumenDescuento');
        const resumenTotalFinal = document.getElementById('resumenTotalFinal');
        const envioGratisMsg = document.getElementById('envioGratisMsg');
        let total = 0, totalOriginal = 0, totalDescuento = 0, cantidadTotal = 0;
        lista.innerHTML = '';
        if (carrito.length === 0) {
            vacio.classList.remove('hidden');
            resumenCantidad.textContent = '0 productos';
            resumenTotal.textContent = 'S/ 0.00';
            resumenOriginal.textContent = 'S/ 0.00';
            resumenDescuento.textContent = '- S/ 0.00';
            resumenTotalFinal.textContent = 'S/ 0.00';
            envioGratisMsg.textContent = 'Gasta S/ 99.00 más y disfruta del envío gratuito de tu pedido';
            return;
        }
        vacio.classList.add('hidden');
        carrito.forEach((item, idx) => {
            let precio = item.precio_con_descuento || 0;
            let precioOriginal = item.precio_venta || precio;
            let nombre = item.nombre_producto || 'Producto #' + item.id;
            let imagen = item.url_imagen || 'https://via.placeholder.com/80x80?text=Producto';
            let subtotal = (precio * item.cantidad) || 0;
            let subtotalOriginal = (precioOriginal * item.cantidad) || 0;
            let descuento = subtotalOriginal - subtotal;
            cantidadTotal += item.cantidad;
            total += subtotal;
            totalOriginal += subtotalOriginal;
            totalDescuento += descuento;
            lista.innerHTML += `
                <div class="flex flex-col md:flex-row items-center bg-white rounded-lg shadow mb-6 overflow-hidden">
                    <div class="flex-shrink-0 w-full md:w-56 h-56 flex items-center justify-center bg-gray-50">
                        <img src="${imagen}" alt="img" class="object-contain w-full h-full" />
                    </div>
                    <div class="flex-1 w-full p-6 flex flex-col gap-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-lg font-bold text-gray-900">${nombre}</div>
                                <div class="text-sm text-gray-600">Talla: <span class="font-semibold">${item.talla}</span></div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-gray-400 line-through">S/ ${precioOriginal.toFixed(2)}</span>
                                    <span class="text-pink-600 font-bold">S/ ${precio.toFixed(2)}</span>
                                    <span class="text-green-500 font-semibold text-sm">-${((descuento / subtotalOriginal) * 100 || 0).toFixed(2)}% OFF</span>
                                </div>
                            </div>
                            <button onclick="eliminarDelCarrito(${idx})" class="text-2xl text-gray-400 hover:text-red-600 ml-4">&times;</button>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-sm">Cantidad:</span>
                            <button onclick="cambiarCantidadCarrito(${idx}, -1)" class="w-8 h-8 rounded bg-gray-200 hover:bg-gray-300">-</button>
                            <input type="number" min="1" value="${item.cantidad}" class="w-12 text-center border mx-1 rounded" onchange="cambiarCantidadCarrito(${idx}, 0, this.value)">
                            <button onclick="cambiarCantidadCarrito(${idx}, 1)" class="w-8 h-8 rounded bg-gray-200 hover:bg-gray-300">+</button>
                        </div>
                    </div>
                </div>
            `;
        });
        resumenCantidad.textContent = cantidadTotal + (cantidadTotal === 1 ? ' producto' : ' productos');
        resumenTotal.textContent = 'S/ ' + total.toFixed(2);
        resumenOriginal.textContent = 'S/ ' + totalOriginal.toFixed(2);
        resumenDescuento.textContent = '- S/ ' + totalDescuento.toFixed(2);
        resumenTotalFinal.textContent = 'S/ ' + (total + 15).toFixed(2); // Suma envío
        if (total >= 99) {
            envioGratisMsg.textContent = '¡Disfruta del envío gratuito de tu pedido!';
            resumenTotalFinal.textContent = 'S/ ' + total.toFixed(2);
        } else {
            envioGratisMsg.textContent = 'Gasta S/ 99.00 más y disfruta del envío gratuito de tu pedido';
        }
    }

    function cambiarCantidadCarrito(idx, delta, valorManual) {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[idx]) {
            if (valorManual !== undefined) {
                carrito[idx].cantidad = Math.max(1, parseInt(valorManual) || 1);
            } else {
                carrito[idx].cantidad = Math.max(1, carrito[idx].cantidad + delta);
            }
            localStorage.setItem('carrito', JSON.stringify(carrito));
            renderCarrito();
            if (typeof actualizarContadorCarrito === 'function') actualizarContadorCarrito();
        }
    }

    function eliminarDelCarrito(idx) {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        carrito.splice(idx, 1);
        localStorage.setItem('carrito', JSON.stringify(carrito));
        renderCarrito();
        if (typeof actualizarContadorCarrito === 'function') actualizarContadorCarrito();
    }

    document.addEventListener('DOMContentLoaded', renderCarrito);
    </script>
</body>
<?php include_once './includes/footer.php'; ?> 