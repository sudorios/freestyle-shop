<?php
header('Content-Type: application/json');
include_once './conexion/cone.php';

$catalogo_id = isset($_GET['catalogo_id']) ? intval($_GET['catalogo_id']) : (isset($_POST['catalogo_id']) ? intval($_POST['catalogo_id']) : 0);
$talla = isset($_GET['talla']) ? $_GET['talla'] : (isset($_POST['talla']) ? $_POST['talla'] : '');

if ($catalogo_id <= 0 || empty($talla)) {
    echo json_encode(['success' => false, 'error' => 'Parámetros inválidos']);
    exit;
}

$sql = "SELECT 
    cp.id,
    p.nombre_producto,
    p.talla_producto,
    isuc.cantidad,
    ip.url_imagen,
    i.precio_venta,
    (i.precio_venta * (1 - (cp.oferta / 100))) AS precio_con_descuento
FROM 
    catalogo_productos cp
JOIN 
    producto p ON cp.producto_id = p.id_producto
JOIN 
    ingreso i ON cp.ingreso_id = i.id
JOIN 
    imagenes_producto ip ON cp.imagen_id = ip.id
JOIN 
    inventario_sucursal isuc ON p.id_producto = isuc.id_producto
WHERE
    cp.id = $1 AND
    p.talla_producto = $2 AND
    isuc.cantidad > 0 AND
    cp.sucursal_id = 7
ORDER BY 
    p.nombre_producto ASC
LIMIT 1;";

$result = pg_query_params($conn, $sql, [$catalogo_id, $talla]);
$row = pg_fetch_assoc($result);

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