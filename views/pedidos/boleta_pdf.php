<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta Pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .titulo { font-size: 22px; font-weight: bold; margin-bottom: 10px; text-align: center; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla th, .tabla td { border: 1px solid #333; padding: 6px; text-align: left; }
        .tabla th { background: #eee; }
        .total { text-align: right; font-size: 16px; font-weight: bold; margin-top: 20px; }
        .datos { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="titulo">BOLETA DE VENTA<br>Pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?></div>
    <div class="datos"><b>Cliente:</b> <?php echo htmlspecialchars($pedido['nombre_usuario'] ?? '-'); ?></div>
    <div class="datos"><b>Email:</b> <?php echo htmlspecialchars($pedido['email_usuario'] ?? '-'); ?></div>
    <div class="datos"><b>Fecha:</b> <?php echo htmlspecialchars($pedido['fecha']); ?></div>
    <div class="datos"><b>Estado:</b> <?php echo htmlspecialchars($pedido['estado']); ?></div>
    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                    <td><?php echo htmlspecialchars($item['talla_producto']); ?></td>
                    <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                    <td>S/ <?php echo number_format($item['precio_unitario'], 2); ?></td>
                    <td>S/ <?php echo number_format($item['subtotal'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($detalles)): ?>
                <tr><td colspan="5" style="text-align:center;">Sin productos</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="total">Total: S/ <?php echo number_format($pedido['total'], 2); ?></div>
</body>
</html> 