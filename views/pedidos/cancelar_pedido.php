<?php
session_start();
include_once '../../conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_pedido'])) {
    header('Location: ../../pedido.php?error=Acceso inválido');
    exit();
}

$id_pedido = intval($_POST['id_pedido']);
if ($id_pedido <= 0) {
    header('Location: ../../pedido.php?error=Datos inválidos');
    exit();
}

// Cambiar estado a CANCELADO
$sql = "UPDATE pedido SET estado = 'CANCELADO' WHERE id_pedido = $1";
$res = pg_query_params($conn, $sql, [$id_pedido]);

// Devolver stock de los productos
$id_sucursal = 7; // Sucursal fija, ajusta si es necesario
$sql_det = "SELECT id_producto, cantidad FROM detalle_pedido WHERE id_pedido = $1";
$res_det = pg_query_params($conn, $sql_det, [$id_pedido]);
if ($res_det) {
    while ($row = pg_fetch_assoc($res_det)) {
        $sql_stock = "UPDATE inventario_sucursal SET cantidad = cantidad + $1 WHERE id_producto = $2 AND id_sucursal = $3";
        pg_query_params($conn, $sql_stock, [$row['cantidad'], $row['id_producto'], $id_sucursal]);
    }
}

if ($res) {
    header('Location: ../../pedido.php?success=2');
} else {
    header('Location: ../../pedido.php?error=No se pudo cancelar el pedido');
}
exit(); 