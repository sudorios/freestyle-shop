<?php
session_start();
header('Content-Type: application/json');
include_once './conexion/cone.php';

// Validar datos recibidos
$catalogo_id = isset($_POST['catalogo_id']) ? intval($_POST['catalogo_id']) : 0;
$talla = isset($_POST['talla']) ? trim($_POST['talla']) : '';
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
if ($catalogo_id <= 0 || empty($talla) || $cantidad < 1) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Obtener info del producto (precio, producto_id)
$sql = "SELECT cp.producto_id, i.precio_venta FROM catalogo_productos cp JOIN ingreso i ON cp.ingreso_id = i.id WHERE cp.id = $1 LIMIT 1";
$res = pg_query_params($conn, $sql, [$catalogo_id]);
$prod = pg_fetch_assoc($res);
if (!$prod) {
    echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
    exit;
}
$producto_id = $prod['producto_id'];
$precio_unitario = $prod['precio_venta'];

// Determinar usuario o sesión
$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$session_id = session_id();

// Buscar carrito existente
if ($usuario_id) {
    $sql = "SELECT id FROM carrito WHERE usuario_id = $1";
    $params = [$usuario_id];
} else {
    $sql = "SELECT id FROM carrito WHERE session_id = $1";
    $params = [$session_id];
}
$result = pg_query_params($conn, $sql, $params);
$row = pg_fetch_assoc($result);
if ($row) {
    $carrito_id = $row['id'];
} else {
    // Crear carrito
    if ($usuario_id) {
        $sql = "INSERT INTO carrito (usuario_id) VALUES ($1) RETURNING id";
        $params = [$usuario_id];
    } else {
        $sql = "INSERT INTO carrito (session_id) VALUES ($1) RETURNING id";
        $params = [$session_id];
    }
    $result = pg_query_params($conn, $sql, $params);
    $carrito_id = pg_fetch_result($result, 0, 'id');
}

// Verificar si ya existe ese producto y talla en el carrito
$sql = "SELECT id, cantidad FROM carrito_items WHERE carrito_id = $1 AND producto_id = $2 AND talla = $3 AND estado = 'activo'";
$params = [$carrito_id, $producto_id, $talla];
$result = pg_query_params($conn, $sql, $params);
$row = pg_fetch_assoc($result);
if ($row) {
    // Actualizar cantidad
    $nuevo_total = $row['cantidad'] + $cantidad;
    $sql = "UPDATE carrito_items SET cantidad = $1 WHERE id = $2";
    pg_query_params($conn, $sql, [$nuevo_total, $row['id']]);
} else {
    // Insertar nuevo item
    $sql = "INSERT INTO carrito_items (carrito_id, producto_id, talla, cantidad, precio_unitario) VALUES ($1, $2, $3, $4, $5)";
    pg_query_params($conn, $sql, [$carrito_id, $producto_id, $talla, $cantidad, $precio_unitario]);
}
echo json_encode(['success' => true]); 