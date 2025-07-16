<?php
header('Content-Type: application/json');
include_once '../conexion/cone.php';
require_once './queries.php';

$catalogo_id = isset($_GET['catalogo_id']) ? intval($_GET['catalogo_id']) : (isset($_POST['catalogo_id']) ? intval($_POST['catalogo_id']) : 0);
$talla = isset($_GET['talla']) ? $_GET['talla'] : (isset($_POST['talla']) ? $_POST['talla'] : '');

if ($catalogo_id <= 0 || empty($talla)) {
    echo json_encode(['success' => false, 'error' => 'Parámetros inválidos']);
    exit;
}

$row = obtenerStockPorCatalogoYTalla($conn, $catalogo_id, $talla);

if ($row) {
    echo json_encode([
        'success' => true,
        'cantidad' => intval($row['cantidad']),
        'precio_venta' => floatval($row['precio_venta']),
        'precio_con_descuento' => floatval($row['precio_con_descuento'])
    ]);
} else {
    echo json_encode(['success' => false, 'cantidad' => 0]);
} 