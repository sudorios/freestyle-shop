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

$nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
$email_usuario = trim($_POST['email_usuario'] ?? '');
$ref_usuario = trim($_POST['ref_usuario'] ?? '');
$telefono_usuario = trim($_POST['telefono_usuario'] ?? '');
$direccion_usuario = trim($_POST['direccion_usuario'] ?? '');
$rol_usuario = $_POST['rol_usuario'] ?? '';
$estado_usuario = $_POST['estado_usuario'] ?? '';

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
manejarResultadoConsulta($result, $conn, '../../usuario.php?success=2', '../../usuario.php?error=1');
?>