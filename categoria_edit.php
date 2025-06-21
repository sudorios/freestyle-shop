<?php
session_start();
include_once './conexion/cone.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: categoria.php?error=2');
    exit();
}

if (!isset($_POST['id_categoria']) || empty($_POST['id_categoria'])) {
    header('Location: categoria.php?error=2');
    exit();
}

$id_categoria = $_POST['id_categoria'];

if (!is_numeric($id_categoria)) {
    header('Location: categoria.php?error=2');
    exit();
}

$sql_check = "SELECT id_categoria FROM categoria WHERE id_categoria = $1";
$result_check = pg_query_params($conn, $sql_check, array($id_categoria));

if (!$result_check || pg_num_rows($result_check) == 0) {
    header('Location: categoria.php?error=3');
    exit();
}

$nombre_categoria = trim($_POST['nombre_categoria'] ?? '');
$descripcion_categoria = trim($_POST['descripcion_categoria'] ?? '');
$estado_categoria = $_POST['estado_categoria'] ?? '';

$errores = array();

if (empty($nombre_categoria)) {
    $errores[] = "El nombre de la categoría es obligatorio";
}

if (empty($descripcion_categoria)) {
    $errores[] = "La descripción es obligatoria";
}

if (!in_array($estado_categoria, ['true', 'false'])) {
    $errores[] = "El estado no es válido";
}

if (!empty($errores)) {
    $error_msg = implode(', ', $errores);
    header('Location: categoria.php?error=1&msg=' . urlencode($error_msg));
    exit();
}

$sql_nombre_check = "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1 AND id_categoria != $2";
$result_nombre_check = pg_query_params($conn, $sql_nombre_check, array($nombre_categoria, $id_categoria));

if ($result_nombre_check && pg_num_rows($result_nombre_check) > 0) {
    header('Location: categoria.php?error=1&msg=' . urlencode('Ya existe una categoría con ese nombre'));
    exit();
}

$sql = "UPDATE categoria SET 
        nombre_categoria = $1,
        descripcion_categoria = $2,
        estado_categoria = $3
        WHERE id_categoria = $4";

$params = array(
    $nombre_categoria,
    $descripcion_categoria,
    $estado_categoria,
    $id_categoria
);

$result = pg_query_params($conn, $sql, $params);

if ($result) {
    header('Location: categoria.php?success=2');
    exit();
} else {
    $error_msg = pg_last_error($conn);
    header('Location: categoria.php?error=1&msg=' . urlencode('Error al actualizar: ' . $error_msg));
    exit();
}
?> 