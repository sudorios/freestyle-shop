<?php
session_start();
include_once '../../conexion/cone.php';
include_once 'pedido_queries.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../login.php?error=2&msg=No autenticado');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../checkout.php?error=2&msg=Acceso inválido');
    exit();
}

$id_usuario = $_SESSION['id'];

$productos = [];
if (isset($_POST['productos']) && is_array($_POST['productos'])) {
    foreach ($_POST['productos'] as $prod_json) {
        $prod = json_decode($prod_json, true);
        if ($prod) $productos[] = $prod;
    }
}
$total = $_POST['total'] ?? null;
$direccion_envio = $_POST['direccion_envio'] ?? null;
$metodo_pago = $_POST['metodo_pago'] ?? null;
$observaciones = $_POST['observaciones'] ?? null;

if (empty($productos) || !$total) {
    header('Location: ../../checkout.php?error=2&msg=Datos incompletos');
    exit();
}

$sql = query_insertar_pedido();
$result = pg_query_params($conn, $sql, [$id_usuario, $total, $direccion_envio, $metodo_pago, $observaciones]);
if (!$result) {
    $msg = urlencode('Error al registrar pedido: ' . pg_last_error($conn));
    header('Location: ../../checkout.php?error=1&msg=' . $msg);
    exit();
}
$row = pg_fetch_assoc($result);
$id_pedido = $row['id_pedido'] ?? null;
if (!$id_pedido) {
    $msg = urlencode('No se pudo obtener el ID del pedido.');
    header('Location: ../../checkout.php?error=1&msg=' . $msg);
    exit();
}

$sql_detalle = query_insertar_detalle_pedido();
$sql_stock = query_actualizar_stock_producto();
$id_sucursal = 7;
foreach ($productos as $prod) {
    if (!isset($prod['id_producto'], $prod['cantidad'], $prod['precio_unitario'])) {
        $msg = urlencode('Datos de producto incompletos');
        header('Location: ../../checkout.php?error=1&msg=' . $msg);
        exit();
    }
    $res_det = pg_query_params($conn, $sql_detalle, [$id_pedido, $prod['id_producto'], $prod['cantidad'], $prod['precio_unitario']]);
    $res_stock = pg_query_params($conn, $sql_stock, [$prod['cantidad'], $prod['id_producto'], $id_sucursal]);
    if (!$res_det || !$res_stock) {
        $msg = urlencode('Error al registrar detalle o actualizar stock: ' . pg_last_error($conn));
        header('Location: ../../checkout.php?error=1&msg=' . $msg);
        exit();
    }
}

foreach ($productos as $prod) {
    if (isset($prod['id_carrito_item'])) {
        pg_query_params($conn, "UPDATE carrito_items SET estado = 'procesado' WHERE id = $1", [$prod['id_carrito_item']]);
    }
}

header('Location: ../../checkout.php?success=1&msg=Pedido registrado correctamente');
exit(); 