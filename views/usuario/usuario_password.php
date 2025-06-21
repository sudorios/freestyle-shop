<?php
session_start();
include_once __DIR__ . '/../../conexion/cone.php';
include_once __DIR__ . '/usuario_queries.php';
include_once __DIR__ . '/usuario_utils.php';

verificarSesionAdmin();
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
manejarResultadoConsulta($result, $conn, '../../usuario.php?success=2', '../../usuario.php?error=1');
