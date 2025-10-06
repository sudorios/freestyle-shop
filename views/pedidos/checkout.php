<?php
include_once __DIR__ . '/../../includes/head.php';
$esCliente = true; include_once __DIR__ . '/../../includes/header.php';
?>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-xl mx-auto bg-white p-8 mt-10 rounded shadow">
        <?php
        if (isset($success) && $success) {
            echo '<div class="mb-4 p-3 bg-green-100 text-green-800 rounded font-semibold text-center">' . htmlspecialchars($msg ?? '¡Pedido registrado correctamente!') . '</div>';
            if (isset($id_pedido)) {
                echo '<div class="flex flex-col items-center gap-4 mt-6">';
                echo '<a href="index.php?controller=pedido&action=generarBoletaPDF&id=' . $id_pedido . '" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2" target="_blank"><i class="fa fa-file-pdf-o"></i> Descargar boleta PDF</a>';
                echo '<a href="index.php" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded flex items-center gap-2"><i class="fa fa-home"></i> Volver a la tienda</a>';
                echo '</div>';
            }
            echo '<meta http-equiv="refresh" content="10;url=index.php">';
        } elseif (isset($error) && $error) {
            echo '<div class="mb-4 p-3 bg-red-100 text-red-800 rounded font-semibold text-center">' . htmlspecialchars($msg ?? 'Ocurrió un error al registrar el pedido.') . '</div>';
            if (isset($debug_log)) {
                echo '<pre style="background:#222;color:#fff;padding:1em;overflow:auto;font-size:0.9em;">' . htmlspecialchars($debug_log) . '</pre>';
            }
        }
        ?>

        <?php if (isset($mostrar_formulario) && $mostrar_formulario): ?>
        <h1 class="text-2xl font-bold mb-6">Finalizar compra</h1>
        <form id="form-pedido" method="POST" action="index.php?controller=pedido&action=checkout">

            <div class="mb-4">
                <label for="nombre_completo" class="block font-semibold mb-1">Nombre completo</label>
                <input type="text" name="nombre_completo" id="nombre_completo" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="telefono" class="block font-semibold mb-1">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="direccion_envio" class="block font-semibold mb-1">Dirección de envío</label>
                <input type="text" name="direccion_envio" id="direccion_envio" class="w-full border p-2 rounded" placeholder="Av. Mercurio 544" required>
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="ciudad" class="block font-semibold mb-1">Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label for="codigo_postal" class="block font-semibold mb-1">Código postal</label>
                    <input type="text" name="codigo_postal" id="codigo_postal" class="w-full border p-2 rounded" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="departamento" class="block font-semibold mb-1">Departamento / Región</label>
                <input type="text" name="departamento" id="departamento" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="metodo_pago" class="block font-semibold mb-1">Método de pago</label>
                <select name="metodo_pago" id="metodo_pago" class="w-full border p-2 rounded" required>
                    <option value="tarjeta" selected>Tarjeta de crédito/débito</option>
                    <option value="paypal">PayPal</option>
                    <option value="yape">Yape / Plin / Transferencia</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="tipo_documento" class="block font-semibold mb-1">Tipo de documento</label>
                <select name="tipo_documento" id="tipo_documento" class="w-full border p-2 rounded">
                    <option value="dni">DNI</option>
                    <option value="ruc">RUC</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="numero_documento" class="block font-semibold mb-1">Número de documento</label>
                <input type="text" name="numero_documento" id="numero_documento" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label for="observaciones" class="block font-semibold mb-1">Notas / instrucciones adicionales</label>
                <textarea name="observaciones" id="observaciones" class="w-full border p-2 rounded" rows="3" placeholder="Ej: dejar el pedido en recepción"></textarea>
            </div>

            <div class="mb-6">
                <h2 class="font-bold mb-2">Resumen del carrito</h2>
                <div class="divide-y">
                <?php foreach ($carrito as $item): ?>
                    <div class="flex items-center py-3 gap-4">
                        <div class="w-16 h-16 flex-shrink-0 bg-gray-100 flex items-center justify-center rounded">
                            <img src="<?= htmlspecialchars($item['url_imagen'] ?? 'https://via.placeholder.com/64x64?text=IMG') ?>" alt="img" class="object-contain w-full h-full rounded" />
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900"><?= htmlspecialchars($item['nombre_producto']) ?></div>
                            <?php if (!empty($item['talla'])): ?>
                                <div class="text-xs text-gray-500">Talla: <?= htmlspecialchars($item['talla']) ?></div>
                            <?php endif; ?>
                            <div class="text-xs text-gray-500">Precio unitario: S/ <?= number_format($item['precio_unitario'], 2) ?></div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">x<?= $item['cantidad'] ?></div>
                            <div class="text-sm text-gray-700">S/ <?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?></div>
                        </div>
                        <input type="hidden" name="productos[]" value='<?= json_encode([
                            "id_producto" => $item["producto_id"],
                            "cantidad" => $item["cantidad"],
                            "precio_unitario" => $item["precio_unitario"],
                            "id_carrito_item" => $item["id"]
                        ]) ?>'>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="flex justify-between mt-4 text-lg font-bold">
                    <span>Subtotal</span>
                    <span>S/ <?= number_format($total, 2) ?></span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span>Envío</span>
                    <span><?= $envio == 0 ? 'Gratis' : 'S/ 15.00' ?></span>
                </div>
                <div class="flex justify-between mt-2 text-xl font-bold border-t pt-2">
                    <span>Total</span>
                    <span>S/ <?= number_format($total_final, 2) ?></span>
                </div>
                <div class="text-xs text-gray-400 mb-2">(IGV incluido)</div>
            </div>

            <input type="hidden" name="total" value="<?= $total_final ?>">

            <button type="submit" class="w-full bg-black hover:bg-gray-900 text-white font-bold py-3 rounded text-lg flex items-center justify-center gap-2 mb-2 cursor-pointer">
                CONFIRMAR PEDIDO <span class="ml-2">→</span>
            </button>
        </form>
        <?php endif; ?>

        <div id="mensaje-resultado" class="mt-4 text-center"></div>
    </div>
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
