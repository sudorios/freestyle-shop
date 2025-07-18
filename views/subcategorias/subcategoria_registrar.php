<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/subcategoria_utils.php';
require_once __DIR__ . '/subcategoria_queries.php';

verificarMetodoPost();

$nombre = trim($_POST['nombre_subcategoria'] ?? '');
$descripcion = trim($_POST['descripcion_subcategoria'] ?? '');
$id_categoria = $_POST['id_categoria'] ?? '';
$estado = true;

$errores = validarCamposSubcategoria($nombre, $id_categoria);
if (!empty($errores)) {
    manejarErrores($errores, '../../subcategoria_add.php?error=1');
}

$query = insertSubcategoriaQuery();
$params = array($nombre, $descripcion, $id_categoria, $estado);
$result = pg_query_params($conn, $query, $params);

manejarResultadoConsulta($result, $conn, '../../subcategoria.php?success=2', '../../subcategoria.php?error=1');

if (isset($_GET['eliminar'])) {
    require_once __DIR__ . '/subcategoria_utils.php';
    $id = $_GET['eliminar'];
    verificarIdSubcategoria($id);
    require_once __DIR__ . '/subcategoria_queries.php';
    $sql = deleteSubcategoriaQuery();
    $result = pg_query_params($conn, $sql, array($id));
    manejarResultadoConsulta($result, $conn, '../../subcategoria.php?success=2', '../../subcategoria.php?error=1');
    exit();
}

?> 