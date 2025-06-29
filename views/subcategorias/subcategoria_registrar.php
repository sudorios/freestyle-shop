<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '/../../conexion/cone.php';
require_once '/subcategoria_queries.php';
require_once '/subcategoria_utils.php';

verificarSesionAdmin();

verificarMetodoPost();

$nombre = trim($_POST['nombre_subcategoria'] ?? '');
$descripcion = trim($_POST['descripcion_subcategoria'] ?? '');
$id_categoria = $_POST['id_categoria'] ?? '';

$errores = validarCamposSubcategoria($nombre, $id_categoria);
if (!empty($errores)) {
    manejarErrores($errores, '../../subcategoria_add.php?error=1');
}

$query = insertSubcategoriaQuery();
$params = array($nombre, $descripcion, $id_categoria);
$result = pg_query_params($conn, $sql, $params);

manejarResultadoConsulta($result, $conn, '../../subcategoria.php?success=2', '../../subcategoria.php?error=1');

?> 