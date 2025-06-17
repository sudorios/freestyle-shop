<?php
session_start();
include_once 'cone.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['txtusu']);
    $contrasenia = trim($_POST['txtpass']);

    $query = "SELECT id_usuario, ref_usuario, pass_usuario, rol_usuario FROM usuario WHERE ref_usuario = $1 LIMIT 1";
    $result = pg_query_params($conn, $query, array($usuario));

    if ($result) {
        if (pg_num_rows($result) === 1) {
            $row = pg_fetch_assoc($result);
            $hash = $row['pass_usuario'];

            if (password_verify($contrasenia, $hash)) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['id'] = $row['id_usuario'];
                $_SESSION['rol'] = $row['rol_usuario'];
                header('Location: ../index.php');
                exit();
            } else {
                echo "Contraseña incorrecta";
            }
        } else {
            echo "Usuario no encontrado";
        }
    } else {
        echo "Error en la consulta: " . pg_last_error($conn);
    }

    pg_free_result($result);
    pg_close($conn);
}