<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/categoria_queries.php';
require_once __DIR__ . '/categoria_utils.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../categoria.php');
    exit();
}

$nombre          = trim($_POST['txtnombre']        ?? '');
$descripcion     = trim($_POST['txtdescripcion']   ?? '');
$estado          = true; 
$creado_en       = date('Y-m-d H:i:s');

$errores = validarCamposCategoria($nombre, $descripcion, 'true');
if ($errores) {
    manejarErrores($errores, '../../categoria_add.php?error=1');
}

if (verificarExistenciaCategoria($conn, $nombre)) {
    header('Location: ../../categoria_add.php?error=1&msg=' . urlencode('La categoría ya existe'));
    exit();
}

$query  = insertCategoriaQuery();
$params = [$nombre, $descripcion, $estado, $creado_en];
$result = pg_query_params($conn, $query, $params);

if ($result) {
    header('Location: ../../categoria.php?success=1');
    exit();
} else {
    error_log('Categoría insert error: ' . pg_last_error($conn));
    header('Location: ../../categoria_add.php?error=1&msg=' . urlencode('Error al registrar la categoría'));
    exit();
}
