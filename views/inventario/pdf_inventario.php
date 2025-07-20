<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario por Sucursal</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .titulo { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla th, .tabla td { border: 1px solid #333; padding: 6px; text-align: left; }
        .tabla th { background: #eee; }
    </style>
</head>
<body>
    <div class="titulo">Inventario por Sucursal</div>
    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Sucursal</th>
                <th>Cantidad</th>
                <th>Fecha Actualización</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventario as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_producto'] . ($row['talla_producto'] ? '(' . $row['talla_producto'] . ')' : '')); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_sucursal']); ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td><?php echo isset($row['fecha_actualizacion']) ? date('d/m/Y H:i', strtotime($row['fecha_actualizacion'])) : '-'; ?></td>
                    <td><?php echo ($row['estado'] === true || $row['estado'] === 't' || $row['estado'] === 1 || $row['estado'] === '1') ? 'Activo' : ucfirst(strtolower($row['estado'])); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($inventario)): ?>
                <tr><td colspan="5" style="text-align:center;">No hay productos en inventario.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html> 