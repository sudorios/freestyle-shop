<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/conteos_ciclicos_queries.php';

$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
$id_sucursal = isset($_GET['id_sucursal']) ? intval($_GET['id_sucursal']) : 0;
$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';
$usuario = isset($_GET['usuario']) ? trim($_GET['usuario']) : '';
$estado = isset($_GET['estado']) ? trim($_GET['estado']) : '';

if ($id_producto <= 0 || $id_sucursal <= 0) {
    die('Parámetros inválidos.');
}

$sql = getConteosCiclicosFiltradosQuery($fecha_desde, $fecha_hasta, $usuario, $estado);
$params = [$id_producto, $id_sucursal];
if ($fecha_desde && $fecha_hasta) {
    $params[] = $fecha_desde;
    $params[] = $fecha_hasta;
} elseif ($fecha_desde) {
    $params[] = $fecha_desde;
} elseif ($fecha_hasta) {
    $params[] = $fecha_hasta;
}
if ($usuario) {
    $params[] = "%$usuario%";
}
if ($estado) {
    $params[] = $estado;
}
$result = pg_query_params($conn, $sql, $params);

$rows = [];
if ($result && pg_num_rows($result) > 0) {
    while ($row = pg_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=conteos_ciclicos.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Cantidad Real', 'Cantidad Sistema', 'Diferencia', 'Fecha Conteo', 'Usuario', 'Estado', 'Fecha Ajuste', 'Comentarios']);
foreach ($rows as $row) {
    fputcsv($output, [
        $row['id_conteo'],
        $row['cantidad_real'],
        $row['cantidad_sistema'],
        $row['diferencia'],
        date('d/m/Y', strtotime($row['fecha_conteo'])),
        $row['nombre_usuario'] ?? $row['usuario_id'],
        ucfirst($row['estado_conteo']),
        $row['fecha_ajuste'] ? date('d/m/Y', strtotime($row['fecha_ajuste'])) : '-',
        $row['comentarios']
    ]);
}
fclose($output);
exit(); 