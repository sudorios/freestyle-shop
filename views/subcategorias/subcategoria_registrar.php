<?php
session_start();
include_once '../../conexion/cone.php';
include_once 'subcategoria_queries.php';
include_once 'subcategoria_utils.php';

verificarSesionAdmin();
verificarMetodoPost();

$nombre = trim($_POST['nombre_subcategoria'] ?? '');
$descripcion = trim($_POST['descripcion_subcategoria'] ?? '');
$id_categoria = $_POST['id_categoria'] ?? '';

$errores = validarCamposSubcategoria($nombre, $id_categoria);
if (!empty($errores)) {
    $msg = urlencode(implode(', ', $errores));
    header('Location: ../../subcategoria.php?error=2&msg=' . $msg);
    exit();
}

$sql = $sql_insertar_subcategoria;
$params = array($nombre, $descripcion, $id_categoria);
$result = pg_query_params($conn, $sql, $params);
manejarResultadoConsulta($result, $conn, '../../subcategoria.php?success=2', '../../subcategoria.php?error=1');
?> 