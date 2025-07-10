<?php
session_start();
header('Content-Type: application/json');
include_once 'carrito_utils.php';
include_once 'carrito_queries.php';

$catalogo_id = isset($_POST['catalogo_id']) ? intval($_POST['catalogo_id']) : 0;
$talla = isset($_POST['talla']) ? trim($_POST['talla']) : '';
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
if ($catalogo_id <= 0 || empty($talla) || $cantidad < 1) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

$sql = query_info_producto_catalogo();
$res = pg_query_params($conn, $sql, [$catalogo_id]);
$prod = pg_fetch_assoc($res);
if (!$prod) {
    echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
    exit;
}
$producto_id = $prod['producto_id'];
$precio_unitario = $prod['precio_venta'];

$carrito_id = obtener_carrito_id($conn);
if (!$carrito_id) {
    $usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    $session_id = session_id();
    if ($usuario_id) {
        $sql = query_insertar_carrito_por_usuario();
        $params = [$usuario_id];
    } else {
        $sql = query_insertar_carrito_por_sesion();
        $params = [$session_id];
    }
    $result = pg_query_params($conn, $sql, $params);
    $carrito_id = pg_fetch_result($result, 0, 'id');
}
$sql = query_buscar_item_carrito();
$params = [$carrito_id, $producto_id, $talla];
$result = pg_query_params($conn, $sql, $params);
$row = pg_fetch_assoc($result);
if ($row) {
    $nuevo_total = $row['cantidad'] + $cantidad;
    $sql = query_actualizar_cantidad_item();
    pg_query_params($conn, $sql, [$nuevo_total, $row['id']]);
} else {
    $sql = query_insertar_item_carrito();
    pg_query_params($conn, $sql, [$carrito_id, $producto_id, $talla, $cantidad, $precio_unitario]);
}
echo json_encode(['success' => true]); 