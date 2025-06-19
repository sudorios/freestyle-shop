<?php
session_start();
include_once './conexion/cone.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: usuario.php?error=2');
    exit();
}

if (!isset($_POST['id_usuario']) || empty($_POST['id_usuario'])) {
    header('Location: usuario.php?error=2');
    exit();
}

$id_usuario = $_POST['id_usuario'];

if (!is_numeric($id_usuario)) {
    header('Location: usuario.php?error=2');
    exit();
}

$sql_check = "SELECT id_usuario, nombre_usuario FROM usuario WHERE id_usuario = $1";
$result_check = pg_query_params($conn, $sql_check, array($id_usuario));

if (!$result_check || pg_num_rows($result_check) == 0) {
    header('Location: usuario.php?error=3');
    exit();
}

$usuario = pg_fetch_assoc($result_check);

$password_nueva = $_POST['password_nueva'] ?? '';
$password_confirmar = $_POST['password_confirmar'] ?? '';

$errores = array();

if (empty($password_nueva)) {
    $errores[] = "La nueva contraseña es obligatoria";
}

if (strlen($password_nueva) < 6) {
    $errores[] = "La contraseña debe tener al menos 6 caracteres";
}

if ($password_nueva !== $password_confirmar) {
    $errores[] = "Las contraseñas no coinciden";
}

if (!empty($errores)) {
    $error_msg = implode(', ', $errores);
    header('Location: usuario.php?error=1&msg=' . urlencode($error_msg));
    exit();
}

$password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);

$sql = "UPDATE usuario SET pass_usuario = $1 WHERE id_usuario = $2";

$params = array($password_hash, $id_usuario);

$result = pg_query_params($conn, $sql, $params);

if ($result) {
    header('Location: usuario.php?success=3');
    exit();
} else {
    $error_msg = pg_last_error($conn);
    header('Location: usuario.php?error=1&msg=' . urlencode('Error al cambiar la contraseña: ' . $error_msg));
    exit();
}
?> 