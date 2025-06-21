<?php
include_once '../../conexion/cone.php';
include_once 'producto_utils.php';

header('Content-Type: application/json');

$ref = generarReferenciaUnicaBD($conn);
if ($ref === false) {
    echo json_encode(['success' => false, 'error' => 'No se pudo generar una referencia única.']);
    exit();
}
echo json_encode(['success' => true, 'referencia' => $ref]); 