<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Conteos Cíclicos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .titulo { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla th, .tabla td { border: 1px solid #333; padding: 6px; text-align: left; }
        .tabla th { background: #eee; }
    </style>
</head>
<body>
    <div class="titulo">Listado de Conteos Cíclicos</div>
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cantidad Real</th>
                <th>Cantidad Sistema</th>
                <th>Diferencia</th>
                <th>Fecha Conteo</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Fecha Ajuste</th>
                <th>Comentarios</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($conteos as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_conteo']); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad_real']); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad_sistema']); ?></td>
                    <td><?php echo htmlspecialchars($row['diferencia']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_conteo']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_usuario'] ?? $row['usuario_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['estado_conteo']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_ajuste']); ?></td>
                    <td><?php echo htmlspecialchars($row['comentarios']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($conteos)): ?>
                <tr><td colspan="9" style="text-align:center;">No hay conteos cíclicos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html> 