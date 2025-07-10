<?php
session_start();
include_once '../../conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_pedido'], $_POST['nuevo_estado'])) {
    header('Location: ../../pedido.php?error=Acceso inválido');
    exit();
}

$id_pedido = intval($_POST['id_pedido']);
$nuevo_estado = $_POST['nuevo_estado'];
$estados_validos = ['RECIBIDO', 'CANCELADO', 'PENDIENTE'];
if ($id_pedido <= 0 || !in_array($nuevo_estado, $estados_validos)) {
    header('Location: ../../pedido.php?error=Datos inválidos');
    exit();
}

$sql = "UPDATE pedido SET estado = $1 WHERE id_pedido = $2";
$res = pg_query_params($conn, $sql, [$nuevo_estado, $id_pedido]);
if ($res) {
    header('Location: ../../pedido.php?success=2');
} else {
    header('Location: ../../pedido.php?error=No se pudo actualizar el estado');
}
exit(); 