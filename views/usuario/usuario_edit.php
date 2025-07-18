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

$nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
$email_usuario = trim($_POST['email_usuario'] ?? '');
$ref_usuario = trim($_POST['ref_usuario'] ?? '');
$telefono_usuario = trim($_POST['telefono_usuario'] ?? '');
$direccion_usuario = trim($_POST['direccion_usuario'] ?? '');

$sql_get = "SELECT rol_usuario, estado_usuario FROM usuario WHERE id_usuario = $1";
$res_get = pg_query_params($conn, $sql_get, [$id_usuario]);
$row_get = pg_fetch_assoc($res_get);
$rol_usuario = $row_get['rol_usuario'];
$estado_usuario = $row_get['estado_usuario'];

$errores = validarCamposUsuario($nombre_usuario, $email_usuario, $ref_usuario, $telefono_usuario, $direccion_usuario, $rol_usuario, $estado_usuario);

$sql_check_combined = getUsuarioByEmailAndNickname();
$result_check_combined = pg_query_params($conn, $sql_check_combined, array($email_usuario, $ref_usuario, $id_usuario));

$sql = updateUsuario();
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

if ($result && isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == $id_usuario) {
    $_SESSION['usuario'] = $nombre_usuario;
    $_SESSION['email_usuario'] = $email_usuario;
    $_SESSION['ref_usuario'] = $ref_usuario;
}

manejarResultadoConsulta($result, $conn, '../../usuario.php?success=2', '../../usuario.php?error=1');
?>