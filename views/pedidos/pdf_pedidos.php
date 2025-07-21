<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Pedidos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .titulo { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla th, .tabla td { border: 1px solid #333; padding: 6px; text-align: left; }
        .tabla th { background: #eee; }
    </style>
</head>
<body>
    <div class="titulo">Listado de Pedidos</div>
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['nombre_usuario'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                    <td>S/ <?php echo number_format($pedido['total'], 2); ?></td>
                    <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($pedidos)): ?>
                <tr><td colspan="5" style="text-align:center;">No hay pedidos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html> 