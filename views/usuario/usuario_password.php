<?php
session_start();
include_once __DIR__ . '/../../conexion/cone.php';
include_once __DIR__ . '/usuario_queries.php';
include_once __DIR__ . '/usuario_utils.php';


verificarMetodoPost();
verificarIdUsuario($_POST['id_usuario']);

$id_usuario = $_POST['id_usuario'];

$sql_check = getUsuarioById();
$result_check = pg_query_params($conn, $sql_check, array($id_usuario));

verificarResultadoConsulta($result_check, 'usuario.php', 3);
$usuario = pg_fetch_assoc($result_check);

$password_nueva = $_POST['password_nueva'] ?? '';
$password_confirmar = $_POST['password_confirmar'] ?? '';

$errores = validarCamposPassword($password_nueva, $password_confirmar);
$password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
$sql = updateUsuarioPassword();

$params = array($password_hash, $id_usuario);
$result = pg_query_params($conn, $sql, $params);

$referer = $_POST['referer'] ?? '';
if (strpos($referer, 'cliente.php') !== false) {
    $success_url = '../../cliente.php?success=3';
    $error_url = '../../cliente.php?error=5';
} else {
    $success_url = '../../usuario.php?success=3';
    $error_url = '../../usuario.php?error=5';
}

manejarResultadoConsulta($result, $conn, $success_url, $error_url);
