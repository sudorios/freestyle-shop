<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Ingresos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .titulo { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla th, .tabla td { border: 1px solid #333; padding: 6px; text-align: left; }
        .tabla th { background: #eee; }
    </style>
</head>
<body>
    <div class="titulo">Listado de Ingresos</div>
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Referencia</th>
                <th>Producto</th>
                <th>Sucursal</th>
                <th>Cantidad</th>
                <th>Fecha Ingreso</th>
                <th>Usuario</th>
                <th>Precio Costo IGV</th>
                <th>Precio Venta</th>
                <th>Utilidad Esperada</th>
                <th>Utilidad Neta</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ingresos as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_ingreso'] ?? $row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['ref']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_producto'] . ($row['talla_producto'] ? ' (' . $row['talla_producto'] . ')' : '')); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_sucursal'] ?? 'Sin sucursal'); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_ingreso']); ?></td>
                    <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['precio_costo_igv']); ?></td>
                    <td><?php echo htmlspecialchars($row['precio_venta']); ?></td>
                    <td><?php echo htmlspecialchars($row['utilidad_esperada']); ?></td>
                    <td><?php echo htmlspecialchars($row['utilidad_neta']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($ingresos)): ?>
                <tr><td colspan="11" style="text-align:center;">No hay ingresos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html> 