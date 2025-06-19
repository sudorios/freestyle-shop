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

$sql_check = "SELECT id_usuario FROM usuario WHERE id_usuario = $1";
$result_check = pg_query_params($conn, $sql_check, array($id_usuario));

if (!$result_check || pg_num_rows($result_check) == 0) {
    header('Location: usuario.php?error=3');
    exit();
}

$nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
$email_usuario = trim($_POST['email_usuario'] ?? '');
$ref_usuario = trim($_POST['ref_usuario'] ?? '');
$telefono_usuario = trim($_POST['telefono_usuario'] ?? '');
$direccion_usuario = trim($_POST['direccion_usuario'] ?? '');
$rol_usuario = $_POST['rol_usuario'] ?? '';
$estado_usuario = $_POST['estado_usuario'] ?? '';

$errores = array();

if (empty($nombre_usuario)) {
    $errores[] = "El nombre es obligatorio";
}

if (empty($email_usuario)) {
    $errores[] = "El email es obligatorio";
} elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El formato del email no es válido";
}

if (empty($ref_usuario)) {
    $errores[] = "El nickname es obligatorio";
}

if (empty($telefono_usuario)) {
    $errores[] = "El teléfono es obligatorio";
}

if (empty($direccion_usuario)) {
    $errores[] = "La dirección es obligatoria";
}

if (!in_array($rol_usuario, ['cliente', 'admin'])) {
    $errores[] = "El rol no es válido";
}

if (!in_array($estado_usuario, ['true', 'false'])) {
    $errores[] = "El estado no es válido";
}

if (!empty($errores)) {
    $error_msg = implode(', ', $errores);
    header('Location: usuario.php?error=1&msg=' . urlencode($error_msg));
    exit();
}

$sql_email_check = "SELECT id_usuario FROM usuario WHERE email_usuario = $1 AND id_usuario != $2";
$result_email_check = pg_query_params($conn, $sql_email_check, array($email_usuario, $id_usuario));

if ($result_email_check && pg_num_rows($result_email_check) > 0) {
    header('Location: usuario.php?error=1&msg=' . urlencode('El email ya está registrado por otro usuario'));
    exit();
}

$sql_nickname_check = "SELECT id_usuario FROM usuario WHERE ref_usuario = $1 AND id_usuario != $2";
$result_nickname_check = pg_query_params($conn, $sql_nickname_check, array($ref_usuario, $id_usuario));

if ($result_nickname_check && pg_num_rows($result_nickname_check) > 0) {
    header('Location: usuario.php?error=1&msg=' . urlencode('El nickname ya está registrado por otro usuario'));
    exit();
}

$sql = "UPDATE usuario SET 
        nombre_usuario = $1,
        email_usuario = $2,
        ref_usuario = $3,
        telefono_usuario = $4,
        direccion_usuario = $5,
        rol_usuario = $6,
        estado_usuario = $7
        WHERE id_usuario = $8";

$params = array(
    $nombre_usuario,
    $email_usuario,
    $ref_usuario,
    $telefono_usuario,
    $direccion_usuario,
    $rol_usuario,
    $estado_usuario,
    $id_usuario
);

$result = pg_query_params($conn, $sql, $params);

if ($result) {
    header('Location: usuario.php?success=2');
    exit();
} else {
    $error_msg = pg_last_error($conn);
    header('Location: usuario.php?error=1&msg=' . urlencode('Error al actualizar: ' . $error_msg));
    exit();
}
?> 